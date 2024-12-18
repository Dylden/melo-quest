<?php

namespace App\Controller\user;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
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

    #[Route('/comment/create', name: 'comment_create')]
    public function createComment(Request $request, EntityManagerInterface $entityManager, CommentRepository $commentRepository): Response
    {

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('comments');
        }

        $form_view = $form->createView();

        return $this->render('comment/create.html.twig', [
            'form_view' => $form_view,
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
