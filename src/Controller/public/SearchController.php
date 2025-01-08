<?php

namespace App\Controller\public;

use App\Repository\TrackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{

    #[Route('/search', 'search')]
    public function searchTrack(Request $request, TrackRepository $trackRepository, Security $security): Response
    {

        $searchTerm = $request->query->get('search', '');



            $template = 'public/search.html.twig';

        $tracks = $trackRepository->findBySearchTerm($searchTerm);

        return $this->render($template, [
            'tracks' => $tracks,
        ]);
    }

    public function searchTrackByGenre()
    {

    }
}
