<?php

namespace App\Controller\public;

use App\Repository\TrackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(TrackRepository $trackRepository): Response
    {
        $user = $this->getUser();
        $recentTracks = $trackRepository->findBy([], ['createdAt' => 'DESC'], 8);

        return $this->render('public/home/index.html.twig', [
            'recentTracks' => $recentTracks,
            'user' => $user
        ]);
    }
}
