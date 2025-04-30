<?php

namespace App\Controller\Pub;

use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'public_home')]
    public function index(NewsRepository $newsRepository): Response
    {
        $latest_news = $newsRepository->findBy(
            ['isActive' => true],
            ['publishedAt' => 'DESC'],
            10
        );

        return $this->render('home/index.html.twig', [
            'latest_news' => $latest_news,
        ]);
    }
}
