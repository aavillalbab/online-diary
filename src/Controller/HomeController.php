<?php

namespace App\Controller;

use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(NewsRepository $repo): Response
    {
        $latest = $repo->findBy([], ['publishedAt' => 'DESC'], 10);
        return $this->render('home/index.html.twig', [
            'news_list' => $latest,
        ]);
    }
}
