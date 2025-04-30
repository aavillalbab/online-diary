<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/secured/user', name: 'indexUser')]
    public function userIndex(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'Dashboard',
        ]);
    }

    #[Route('/news', name: 'news', methods: ['GET', 'POST'])]
    public function news(): Response
    {
        return $this->redirectToRoute('admin_news_index');
    }

    #[Route('/categories', name: 'categories', methods: ['GET', 'POST'])]
    public function categories(): Response
    {
        return $this->redirectToRoute('admin_category_index');
    }
}
