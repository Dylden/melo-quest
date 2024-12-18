<?php

namespace App\Controller\user;

use App\Entity\Track;
use App\Form\TrackType;
use App\Repository\TrackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TrackController extends AbstractController
{
    #[Route('/track', name: 'track')]
    public function index(): Response
    {
        return $this->render('track/index.html.twig', [
            'controller_name' => 'TrackController',
        ]);
    }

    #[Route('/track/create', name: 'track_create')]
    public function createTrack(Request $request, EntityManagerInterface $entityManager): Response{
        $track = new Track();

        $form = $this->createForm(TrackType::class, $track);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($track);
            $entityManager->flush();
            return $this->redirectToRoute('track');
        }

        $form_view = $form->createView();

        $user = $this->getUser();

        return $this->render('track/index.html.twig', [
            'form_view' => $form_view,
            'user' => $user,

        ]);
    }

    #[Route('/track/{id}/update', name: 'track_update', requirements: ['id' => '\d+'])]
    function updateTrack(Request $request, EntityManagerInterface $entityManager, Track $track): Response{

        $form = $this->createForm(TrackType::class, $track);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($track);
            $entityManager->flush();

            return $this->redirectToRoute('track');
        }
        $form_view = $form->createView();

        return $this->render('track/update.html.twig', [
            'form_view' => $form_view,
        ]);
    }

    #[Route('/track/{id}/delete', name: 'track_delete', requirements: ['id' => '\d+'])]
    public function deleteTrack(int $id,Request $request, EntityManagerInterface $entityManager, TrackRepository $trackRepository): Response{

        $track = $trackRepository->find($id);

        $entityManager->remove($track);
        $entityManager->flush();

        return $this->redirectToRoute('track');
    }
}
