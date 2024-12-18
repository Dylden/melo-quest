<?php

namespace App\Controller\public;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function index(): Response
    {
        return $this->render('public/login.html.twig', [
        ]);
    }

    #[Route('/sign_in', name: 'sign_in')]
    public function signIn(): Response
    {
        return $this->render('public/sign-in.html.twig', [
        ]);
    }
}
