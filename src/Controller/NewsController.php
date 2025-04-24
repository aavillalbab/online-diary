<?php

namespace App\Controller;

use App\Repository\NewsRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    #[Route('/noticia/{slug}', name: 'news_show', methods: ['GET'])]
    public function show(string $slug, NewsRepository $repo): Response
    {
        $news = $repo->findOneBy(['slug' => $slug]);

        if (!$news) {
            throw $this->createNotFoundException('Noticia no encontrada');
        }

        return $this->render('news/show.html.twig', [
            'news' => $news,
        ]);
    }
}
