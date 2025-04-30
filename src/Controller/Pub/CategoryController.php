<?php

namespace App\Controller\Pub;

use App\Repository\CategoryRepository;
use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/categoria', name: 'category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/categoria/{slug}', name: 'category_show', methods: ['GET'])]
    public function show(string $slug, CategoryRepository $categoryRepository, NewsRepository $newsRepository): Response
    {
        $category = $categoryRepository->findOneBy(['slug' => $slug]);

        if (!$category) {
            throw $this->createNotFoundException('Categoría no encontrada');
        }

        $news = $newsRepository->findBy(
            ['category' => $category, 'isActive' => true],
            ['publishedAt' => 'DESC']
        );

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'news' => $news,
        ]);
    }
}
