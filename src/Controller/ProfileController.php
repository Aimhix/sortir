<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfileType;
use App\Services\FileUploader;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function showProfile(): Response
    {
        //Récupérer user
        $user = $this->getUser();

        //Vérifier si user est connecté
        if (!$user) {
            //On redirige vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        return $this->render('profile/showProfile.html.twig');
    }

    #[Route('/organizer/profile/{id}', name: 'app_organizer_profile')]
    public function showOrganizerProfile($id): Response
    {
        $organizer = $this->getDoctrine()->getRepository(User::class)->find($id);

        if (!$organizer) {
            throw $this->createNotFoundException('Organisateur non trouvé');
        }

        return $this->render('profile/showOrganizerProfile.html.twig', ['organizer' => $organizer]);
    }

    #[Route('/participant/profile/{id}', name: 'app_participant_profile')]
    public function showParticipantProfile($id): Response
    {
        $participant = $this->getDoctrine()->getRepository(User::class)->find($id);

        if (!$participant) {
            throw $this->createNotFoundException('Participant non trouvé');
        }

        return $this->render('profile/showParticipantProfile.html.twig', ['participant' => $participant]);
    }


    #[Route('/profile/editprofile', name: 'app_editprofile')]
    public function edit(Request $request, FileUploader $fileUploader, UserPasswordEncoderInterface $passwordEncoder): Response
    {

        $user = $this->getUser();

        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour accéder à cette page');
        }

        // Formulaire ProfileType
        $form = $this->createForm(EditProfileType::class, $user);

        // Soumission formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form['profilePicture']->getData();

            if ($imageFile) {
                $newFilename = $fileUploader->upload($imageFile, $this->getParameter('kernel.project_dir') . '/public/images');
                $user->setProfilePicture($newFilename);
            }

            // Vérifier pseudo
            $pseudo = $form->get('pseudo')->getData();
            $existingUser = $this->getDoctrine()->getRepository(User::class)->findOneBy(['pseudo' => $pseudo]);

            if ($existingUser && $existingUser->getId() !== $user->getId()) {
                $this->addFlash('error', 'Le pseudo est déjà pris');
            } else {

                // Hashage MDP
                $newPlainPassword = $form->get('password')->getData();
                $encodedPassword = $passwordEncoder->encodePassword($user, $newPlainPassword);
                $user->setPassword($encodedPassword);

                //Enregistrer les modifications dans la BDD
                $profileManager = $this->getDoctrine()->getManager();
                $profileManager->flush();

                $this->addFlash('success', 'Profil mis à jour');

                return $this->redirectToRoute('app_profile');
            }
        }

        return $this->render('profile/editProfile.html.twig',
            ['form' => $form->createView(),]);
    }

}

