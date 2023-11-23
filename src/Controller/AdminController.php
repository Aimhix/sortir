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

        $this->addFlash('success', 'Utilisateur créé avec succès !');
        // Redirection ou autre réponse
        return $this->redirectToRoute('app_activity_index');
    }

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

                $this->addFlash('success', 'Utilisateurs importés avec succès !');
            }

            return $this->redirectToRoute('app_activity_index');
        }

        // Passer les deux formulaires au template
        return $this->render('admin/new_user.html.twig', [
            'registrationForm' => $registrationForm->createView(),
            'csvForm' => $csvForm->createView(),
        ]);
    }
}
