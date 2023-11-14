<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class Connection extends AbstractController


{#[Route('/login', name: 'app_login')]

public function login (AuthenticationUtils $authenticationUtils): Response
{

    $error = $authenticationUtils->getLastAuthenticationError();
    $emailUser = $authenticationUtils->getEmailUser();  // fait appel méthode dans authentification

    return $this->render('connection/login.html.twig', ['email_user' => $emailUser, 'error' => $error]);



}

}