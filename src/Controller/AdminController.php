<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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


    #[Route('/admin/new-user', name: 'admin_new_user')]
    public function newUser(Request $request): Response
    {
        // Si le formulaire est soumis, utilise la méthode createUserManually
        if ($request->isMethod('POST')) {
            return $this->createUserManually($request);
        }

        $user = new User();
        $form = $this->createForm(UserRegistrationFormType::class, $user);

        return $this->render('admin/new_user.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    public function createUserManually(Request $request): Response
    {

        // Récupération manuelle du prénom de l'utilisateur par l'administrateur
        $firstName = $request->request->get('user_registration_form')['firstname'];; // récupération du prénom depuis le formulaire
        $lastName= $request->request->get('user_registration_form')['lastname'];
        $phone= $request->request->get('user_registration_form')['phone'];
        $email = $request->request->get('user_registration_form')['email'];
        $password= $request->request->get('user_registration_form')['password'];
        $campus = $request->request->get('user_registration_form')['campus'];
       // $profilePicture= $request->request->get('user_registration_form')['profilePicture'];

        // beug trouver la bonne key pour le firstname car sinon il le mets en null, à voir si c'est bon car beug sur email

        // Génération du pseudo basé sur le prénom du nouvel utilisateur
        $pseudo = strtolower($firstName); // Pseudo en minuscules

        // Création d'un nouvel utilisateur avec le pseudo généré
        $newUser = new User();
        // Hasher le mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($newUser, $password);

        // Vérification si le pseudo existe déjà dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $entityManager->getRepository(User::class);

        $i = 1;
        $uniquePseudo = $pseudo;
        while ($userRepository->findOneBy(['pseudo' => $uniquePseudo])) {
            // Si le pseudo existe déjà, ajouter un numéro d'incrémentation pour le rendre unique
            $uniquePseudo = $pseudo . '_' . $i;
            $i++;
        }


// campus le transformer en objet avec repository A FAIREEEEEEE

        $newUser->setFirstName($firstName);
        $newUser->setLastname($lastName);
        $newUser->setPhone($phone);
        $newUser->setEmail($email);
        $newUser->setPassword($hashedPassword);
        $newUser->setCampus($campus);
        //$newUser->setProfilePicture($profilePicture);
        $newUser->setPseudo($uniquePseudo);



        // Enregistrement du nouvel utilisateur dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($newUser);
        $entityManager->flush();



        // Retourne une réponse, puis redirection vers une autre page
        return $this->redirectToRoute('app_activity_index');
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
