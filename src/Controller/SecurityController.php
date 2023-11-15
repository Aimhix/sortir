<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('target_path');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();  // fait appel méthode dans authentification
       // return $this->redirectToRoute('app_activity_index');
       return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }



    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): RedirectResponse
    {
        // Redirection vers la page de connexion après la déconnexion
        return $this->redirectToRoute('app_login');
    }

    //    public function logout(): void
//    {
//        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
//    }
}
