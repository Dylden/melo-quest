<?php

namespace App\Controller\public;

use App\Repository\TrackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class LibraryController extends AbstractController{

    #[Route('/tracks/library', name: 'tracks_library')]
    public function listAllTracks(TrackRepository $trackRepository, EntityManagerInterface $entityManager){

        $tracks = $trackRepository->findAll();

        return $this->render('public/library.html.twig', ['tracks' => $tracks]);

    }
}