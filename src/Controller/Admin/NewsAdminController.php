<?php

namespace App\Controller\Admin;

use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/secured/superadmin/news', name: 'admin_news_')]
class NewsAdminController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(NewsRepository $repository): Response
    {
        $newsList = $repository->findBy([], ['publishedAt' => 'DESC']);
        return $this->render('admin/news/index.html.twig', [
            'news_list' => $newsList,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $news = new News();
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($news);
            $em->flush();
            return $this->redirectToRoute('admin_news_index');
        }
        return $this->render('admin/news/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(News $news, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('admin_news_index');
        }
        return $this->render('admin/news/edit.html.twig', [
            'form' => $form->createView(),
            'news' => $news,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, News $news, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $news->getId(), $request->request->get('_token'))) {
            $em->remove($news);
            $em->flush();
        }
        return $this->redirectToRoute('admin_news_index');
    }
}
