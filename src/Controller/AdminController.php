<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\User;
use App\Form\UserRegistrationFormType;
use App\Services\CsvImporter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController
{

    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

//    public function createUserManually(Request $request): Response
//    {
//
//        // Récupération manuelle du prénom de l'utilisateur par l'administrateur
//        $firstName = $request->request->get('user_registration_form')['firstname'];; // récupération du prénom depuis le formulaire
//        $lastName= $request->request->get('user_registration_form')['lastname'];
//        $phone= $request->request->get('user_registration_form')['phone'];
//        $email = $request->request->get('user_registration_form')['email'];
//        $password= $request->request->get('user_registration_form')['password'];
//        $campus = $request->request->get('user_registration_form')['campus'];
//       // $profilePicture= $request->request->get('user_registration_form')['profilePicture'];
//
//        // Génération du pseudo basé sur le prénom du nouvel utilisateur
//        $pseudo = strtolower($firstName); // Pseudo en minuscules
//
//        // Création d'un nouvel utilisateur avec le pseudo généré
//        $newUser = new User();
//        // Hasher le mot de passe
//        $hashedPassword = $this->passwordHasher->hashPassword($newUser, $password);
//
//        // Vérification si le pseudo existe déjà dans la base de données
//        $entityManager = $this->getDoctrine()->getManager();
//        $userRepository = $entityManager->getRepository(User::class);
//
//        $i = 1;
//        $uniquePseudo = $pseudo;
//        while ($userRepository->findOneBy(['pseudo' => $uniquePseudo])) {
//            // Si le pseudo existe déjà, ajouter un numéro d'incrémentation pour le rendre unique
//            $uniquePseudo = $pseudo . '_' . $i;
//            $i++;
//        }
//
//
//
//
//// campus le transformer en objet avec repository A FAIREEEEEEE
//
//        $newUser->setFirstName($firstName);
//        $newUser->setLastname($lastName);
//        $newUser->setPhone($phone);
//        $newUser->setEmail($email);
//        $newUser->setPassword($hashedPassword);
//        $newUser->setCampus($campus);
//        //$newUser->setProfilePicture($profilePicture);
//        $newUser->setPseudo($uniquePseudo);
//
//
//
//        // Enregistrement du nouvel utilisateur dans la base de données
//        $entityManager = $this->getDoctrine()->getManager();
//        $entityManager->persist($newUser);
//        $entityManager->flush();
//
//
//
//        // Retourne une réponse, puis redirection vers une autre page
//        return $this->redirectToRoute('app_activity_index');
//    }

    public function createUserManually(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $userFormData = $request->request->get('user_registration_form'); // Assurez-vous que cela correspond au nom de votre formulaire

        $newUser = new User();

        $newUser->setFirstname($userFormData['firstname']);
        $newUser->setLastname($userFormData['lastname']);
        $newUser->setPhone($userFormData['phone']);
        $newUser->setEmail($userFormData['email']);
        $newUser->setActiveStatus(true);

        // Génération et assignation du pseudo
        $pseudo = strtolower($userFormData['firstname']);
        $uniquePseudo = $this->generateUniquePseudo($pseudo, $entityManager);
        $newUser->setPseudo($uniquePseudo);

        // Hashage du mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($newUser, $userFormData['password']);
        $newUser->setPassword($hashedPassword);

        // Récupération et assignation du campus
        $campus = $entityManager->getRepository(Campus::class)->find($userFormData['campus']);
        if ($campus) {
            $newUser->setCampus($campus);
        } else {
            // Gérer le cas où le campus n'est pas trouvé
        }

        $defaultProfilePicture = '/public/images/defaultpicture.jpg'; // Chemin relatif ou absolu vers l'image
        $newUser->setProfilePicture($defaultProfilePicture);

        $entityManager->persist($newUser);
        $entityManager->flush();

        // Redirection ou autre réponse
        return $this->redirectToRoute('app_activity_index');
    }
//
//    #[Route('/admin/upload-csv', name: 'admin_upload_csv')]
//    public function uploadCsv(Request $request, CsvImporter $csvImporter): Response
//    {
//        $csvForm = $this->createFormBuilder()
//            ->add('file', FileType::class)
//            ->getForm();
//
//        $csvForm->handleRequest($request);
//
//        if ($csvForm->isSubmitted() && $csvForm->isValid()) {
//            $file = $csvForm['file']->getData();
//            if ($file) {
//                $csvImporter->importFromCsv($file);
//            }
//
//            return $this->redirectToRoute('some_admin_route');
//        }
//
//        return $this->render('admin/new_user.html.twig', [
//            'csvForm' => $csvForm->createView(),
//        ]);
//    }


//    #[Route('/admin/new-user', name: 'admin_new_user')]
//    public function newUser(Request $request): Response
//    {
//        // Si le formulaire est soumis, utilise la méthode createUserManually
//        if ($request->isMethod('POST')) {
//            return $this->createUserManually($request);
//        }
//
//        $user = new User();
//        $form = $this->createForm(UserRegistrationFormType::class, $user);
//
//        return $this->render('admin/new_user.html.twig', [
//            'registrationForm' => $form->createView(),
//        ]);
//    }

    private function generateUniquePseudo(string $basePseudo, EntityManagerInterface $entityManager): string
    {
        $userRepository = $entityManager->getRepository(User::class);
        $i = 1;
        $uniquePseudo = $basePseudo;
        while ($userRepository->findOneBy(['pseudo' => $uniquePseudo])) {
            $uniquePseudo = $basePseudo . '_' . $i;
            $i++;
        }
        return $uniquePseudo;
    }

    #[Route('/admin/new-user', name: 'admin_new_user')]
    public function newUser(Request $request, CsvImporter $csvImporter): Response
    {
        // Formulaire d'inscription manuelle
        $user = new User();
        $registrationForm = $this->createForm(UserRegistrationFormType::class, $user);
        $registrationForm->handleRequest($request);

        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            return $this->createUserManually($request);

//            return $this->redirectToRoute('');
        }

        // Formulaire d'upload CSV
        $csvForm = $this->createFormBuilder()
            ->add('file', FileType::class)
            ->getForm();
        $csvForm->handleRequest($request);

        if ($csvForm->isSubmitted() && $csvForm->isValid()) {
            $file = $csvForm['file']->getData();
            if ($file) {
                $csvImporter->importFromCsv($file);
            }

            return $this->redirectToRoute('app_activity_index');
        }

        // Passer les deux formulaires au template
        return $this->render('admin/new_user.html.twig', [
            'registrationForm' => $registrationForm->createView(),
            'csvForm' => $csvForm->createView(),
        ]);
    }



/*
    private function generateUniquePassword(): string
    {
        // Génère une chaîne aléatoire
        $randomString = base64_encode(random_bytes(12));

        return $randomString;
    }*/






//    #[Route('/admin/new-user', name: 'admin_new_user')]              celle de base
//    public function newUser(Request $request): Response
//    {
//        $user = new User();
//        $form = $this->createForm(UserRegistrationFormType::class, $user);
//
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            // Sauvegarder le nouvel utilisateur dans la base de données
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($user);
//            $entityManager->flush();
//
//            // Redirection vers une autre page après la création réussie de l'utilisateur
//            return $this->redirectToRoute('app_activity_index');
//        }
//
//        return $this->render('admin/new_user.html.twig', [
//            'registrationForm' => $form->createView(),
//        ]);
//    }




//    public function createUserManually(Request $request): Response
//    {
//        // Récupération manuelle du prénom de l'utilisateur par l'administrateur
//        $firstName = $request->request->get('firstName'); // Exemple: récupération du prénom depuis le formulaire
//
//        // Génération du pseudo basé sur le prénom du nouvel utilisateur
//        $pseudo = strtolower($firstName); // Pseudo en minuscules
//
//        // Vérification si le pseudo existe déjà dans la base de données
//        $entityManager = $this->getDoctrine()->getManager();
//        $userRepository = $entityManager->getRepository(User::class);
//
//        $i = 1;
//        $uniquePseudo = $pseudo;
//        while ($userRepository->findOneBy(['pseudo' => $uniquePseudo])) {
//            // Si le pseudo existe déjà, ajouter un numéro d'incrémentation pour le rendre unique
//            $uniquePseudo = $pseudo . '_' . $i;
//            $i++;
//        }
//
//        // Création d'un nouvel utilisateur avec le pseudo généré
//        $newUser = new User();
//        $newUser->setFirstName($firstName);
//        $newUser->setPseudo($uniquePseudo); // Assurez-vous que votre entité User a une méthode setPseudo() pour définir le pseudo
//
//        // Enregistrement du nouvel utilisateur dans la base de données
//        $entityManager->persist($newUser);
//        $entityManager->flush();
//
//        // Autres actions à effectuer après la création de l'utilisateur (redirection, affichage de messages, etc.)
//
//        // Retourne une réponse, par exemple, une redirection vers une autre page
//        return $this->redirectToRoute('some_route_name');
//    }
//}
}
