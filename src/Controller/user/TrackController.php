<?php

namespace App\Controller\user;

use App\Entity\Track;
use App\Form\TrackType;
use App\Repository\TrackRepository;
use App\service\UniqueFilenameGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TrackController extends AbstractController
{
    #[Route('/track', name: 'track')]
    public function listTracks(TrackRepository $trackRepository, EntityManagerInterface $entityManager): Response
    {

        $tracks = $trackRepository->findAll();


        return $this->render('user/track/index.html.twig', [
            'tracks' => $tracks,
        ]);
    }

    #[Route('/track/create', name: 'track_create')]
    public function createTrack(Request $request, EntityManagerInterface $entityManager, ParameterBagInterface $parameterBag, UniqueFilenameGenerator $filenameGenerator): Response{
        $track = new Track();

        $form = $this->createForm(TrackType::class, $track);

        $form->handleRequest($request);

        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {

            $trackFile = $form->get('filename')->getData();

            //Gestion du nom des fichiers tracks + route pour les uploads
            if($trackFile){

                $trackFileName = $trackFile->getClientOriginalName();
                $trackFileExtension = $trackFile->getClientOriginalExtension();

                $trackFileNewName = $filenameGenerator->generateUniqueFilename($trackFileName, $trackFileExtension);

                $rootDir = $parameterBag->get('kernel.project_dir');
                $uploadsDir = $rootDir . '/public/assets/uploads/tracks';

                $trackFile->move($uploadsDir, $trackFileNewName);

                $track->setFilename($trackFileNewName);
            }



            $track->setUser($user);

            $entityManager->persist($track);
            $entityManager->flush();
            return $this->redirectToRoute('track');
        }

        $form_view = $form->createView();




        return $this->render('/user/track/create.html.twig', [
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


    #[Route('/track/{id}/show', name: 'track_show', requirements: ['id' => '\d+'])]
    public function showTrack(int $id, TrackRepository $trackRepository): Response{

        $track = $trackRepository->find($id);

        return $this->render('user/track/show.html.twig', [
            'track' => $track,

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
