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

#[Route('/news', name: 'admin_news_')]
class NewsAdminController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
        private NewsRepository $repository
    ) { }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $newsList = $this->repository->findBy([], ['publishedAt' => 'DESC']);

        return $this->render('admin/news/index.html.twig', [
            'news_list' => $newsList,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $news = new News();

        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->has('file')) {
                $file = $form->get('file')->getData();
                if ($file) {
                    $news->setFile($file);
                }
            }
            
            $this->em->persist($news);
            $this->em->flush();
            return $this->redirectToRoute('admin_news_index');
        }

        return $this->render('admin/news/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id): Response
    {
        $news = $this->repository->find($id);

        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->has('file')) {
                $file = $form->get('file')->getData();
                if ($file) {
                    $news->setFile($file);
                }
            }
            
            $this->em->flush();
            return $this->redirectToRoute('admin_news_index');
        }

        return $this->render('admin/news/edit.html.twig', [
            'form' => $form->createView(),
            'news' => $news,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, int $id): Response
    {
        $news = $this->repository->find($id);

        if ($this->isCsrfTokenValid('delete' . $news->getId(), $request->request->get('_token'))) {
            $this->em->remove($news);
            $this->em->flush();
        }

        return $this->redirectToRoute('admin_news_index');
    }
}
