<?php

namespace App\Controller\user;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserHomeController extends AbstractController
{
    #[Route('/user/home', name: 'user_home')]
    public function index(): Response
    {
        return $this->render('user/home/index.html.twig', [
            'controller_name' => 'UserHomeController',
        ]);
    }
}
