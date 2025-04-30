<?php

namespace App\Controller\Pub;

use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    #[Route('/news/{slug}', name: 'news_show', methods: ['GET'])]
    public function show(string $slug, NewsRepository $repo): Response
    {
        // Convertir el slug a título para buscar
        $title = str_replace('-', ' ', $slug);
        $title = ucwords($title);
        
        $news = $repo->findOneBy(['title' => $title]);

        if (!$news) {
            throw $this->createNotFoundException('Noticia no encontrada');
        }

        return $this->render('home/show.html.twig', [
            'news' => $news,
        ]);
    }
}
