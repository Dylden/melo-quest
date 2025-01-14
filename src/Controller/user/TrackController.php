<?php

namespace App\Controller\user;

use App\Entity\Comment;
use App\Entity\Track;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\TrackType;
use App\Repository\CommentRepository;
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
        $user = $this->getUser();
        $tracks = $trackRepository->findAll();

        return $this->render('user/track/index.html.twig', [
            'user' => $user,
            'tracks' => $tracks,
        ]);
    }

    #[Route('/user/{id}/tracks', name: 'tracks_user_list', requirements: ['id' => '\d+'])]
    public function listUserTracks(User $user,TrackRepository $trackRepository): Response{
        $tracks = $trackRepository->findBy(['user' => $user]);

        return $this->render('user/track/user_tracks.html.twig', [
            'tracks' => $tracks,
            'user' => $user,
        ]);
    }

    #[Route('/user/track/create', name: 'track_create')]
    public function createTrack(Request $request, EntityManagerInterface $entityManager, ParameterBagInterface $parameterBag, UniqueFilenameGenerator $filenameGenerator): Response{
        $track = new Track();

        $form = $this->createForm(TrackType::class, $track);

        $form->handleRequest($request);

        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {

            $trackFile = $form->get('filename')->getData();
            $cover = $form->get('cover')->getData();

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

            //Gestion du nom des fichiers d'images pour les tracks
            if($cover){

                $coverName = $cover->getClientOriginalName();
                $coverExtension = $cover->getClientOriginalExtension();

                $rootDir = $parameterBag->get('kernel.project_dir');
                $coverDir = $rootDir . '/public/assets/uploads/images';

                $coverNewName = $filenameGenerator->generateUniqueFilename($coverName, $coverExtension);
                $cover->move($coverDir, $coverNewName);

                $track->setCover($coverNewName);

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

    #[Route('/user/track/{id}/update', name: 'track_update', requirements: ['id' => '\d+'])]
    function updateTrack(Request $request, EntityManagerInterface $entityManager, Track $track): Response{
        $user = $this->getUser();

        $form = $this->createForm(TrackType::class, $track);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($track);
            $entityManager->flush();

            return $this->redirectToRoute('track');
        }
        $form_view = $form->createView();

        return $this->render('user/track/update.html.twig', [
            'form_view' => $form_view,
            'user' => $user,
            'track' => $track
        ]);
    }

    #[Route('/user/track/{id}/show', name: 'track_show', requirements: ['id' => '\d+'])]
    public function showTrack(int $id, Request $request, TrackRepository $trackRepository, CommentRepository $commentRepository, EntityManagerInterface $entityManager): Response{

        $user = $this->getUser();
        $track = $trackRepository->find($id);


        if(!$track){
            throw $this->createNotFoundException("Track not found");
        }

        $comments = $commentRepository->findBy(['track' => $track]);

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $comment->setUser($user);
        $comment->setTrack($track);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('track_show', ['id' => $id]);
        }


        return $this->render('user/track/show.html.twig', [
            'user' => $user,
            'track' => $track,
            'comments' => $comments,
            'commentForm' => $form->createView(),
        ]);
    }

    #[Route('/user/track/{id}/delete', name: 'track_delete', requirements: ['id' => '\d+'])]
    public function deleteTrack(int $id,Request $request, EntityManagerInterface $entityManager, TrackRepository $trackRepository): Response{

        $track = $trackRepository->find($id);

        $entityManager->remove($track);
        $entityManager->flush();

        return $this->redirectToRoute('track');
    }
}
