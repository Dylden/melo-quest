<?php

namespace App\Controller\admin;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GenreController extends AbstractController
{
    #[Route('/admin/genre', name: 'genre')]
    public function index(GenreRepository $genreRepository): Response
    {
        $genres = $genreRepository->findAll();

        return $this->render('admin/genre/index.html.twig', [
            'genres' => $genres,
        ]);
    }

    #[Route('/admin/genre/create',name: 'genre_create')]
    public function createGenre(Request $request, EntityManagerInterface $entityManager): Response{

        $genre = new Genre();

        $form = $this->createForm(GenreType::class, $genre);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($genre);
            $entityManager->flush();

            return $this->redirectToRoute('genre');
        }

        $form_view = $form->createView();

        $user = $this->getUser();

        return $this->render('admin/genre/create.html.twig', [
            'form_view' => $form_view,
            'user' => $user,
        ]);
    }
}
