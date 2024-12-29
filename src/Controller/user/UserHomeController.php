<?php

namespace App\Controller\user;

use App\Repository\TrackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserHomeController extends AbstractController
{
    #[Route('/user/home', name: 'user_home')]
    public function homeUser(TrackRepository $trackRepository): Response
    {
        $user = $this->getUser();
        $recentTracks = $trackRepository->findBy([], ['createdAt' => 'DESC'], 4);

        return $this->render('user/home/index.html.twig', [
            'user' => $user,
            'recentTracks' => $recentTracks,
        ]);
    }

    #[Route('/user/profile', name: 'user_profile')]
    public function profileUser(): Response{

        $user = $this->getUser();

        return $this->render('user/profile.html.twig', [
            'user' => $user
        ]);
    }
}
