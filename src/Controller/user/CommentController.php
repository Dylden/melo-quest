<?php

namespace App\Controller\user;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\TrackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommentController extends AbstractController
{
    #[Route('/comments', name: 'comments')]
    public function index(): Response
    {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }

    #[Route('/comment/create', name: 'comment_create', methods: ['POST'])]
    public function createComment(Request $request, EntityManagerInterface $entityManager, CommentRepository $commentRepository, TrackRepository $trackRepository, int $trackId): Response
    {
        //Lien avec la track
        $trackId = $request->get('trackId');
        $track = $trackRepository->find($trackId);

        if(!$track) {
            throw $this->createNotFoundException('track not found');
        }

        //Création du commentaire
        $comment = new Comment();
        $comment->setTrack($track);
        $comment->setUser($this->getUser());

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commentaire a été ajouté !');

            //Evite de resoumettre le formulaire
            return $this->redirectToRoute('track_show', ['id' => $track->getId()]);
        }

        return $this->render('track_show', [
            'id' => $track->getId(),
        ]);
    }

    #[Route('/comment/{id}/update', name: 'comment_update', requirements: ['id' => '\d+'])]
    public function updateComment(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {


        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('comments');
        }

        $form_view = $form->createView();

        return $this->render('comment/update.html.twig', [
            'form_view' => $form_view,
        ]);
    }

    #[Route('/comment/{id}/delete', name: 'comment_delete', requirements: ['id' => '\d+'])]
    public function deleteComment(int $id, Request $request, CommentRepository $commentRepository, EntityManagerInterface $entityManager): Response
    {

        $comment = $commentRepository->find($id);

        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->redirectToRoute('comments');
    }
}
