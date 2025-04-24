<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/categoria/{slug}', name: 'category_news', methods: ['GET'])]
    public function list(string $slug, CategoryRepository $catRepo): Response
    {
        $cat = $catRepo->findOneBy(['slug' => $slug]);
        if (!$cat) {
            throw $this->createNotFoundException('Categoría no encontrada');
        }

        $newsList = $cat->getNews();

        return $this->render('category/list.html.twig', [
            'category' => $cat,
            'news_list' => $newsList,
        ]);
    }
}
