<?php

namespace App\Controller\Api;

use App\Form\Api\NewsSearchType;
use App\Repository\NewsRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/news', name: 'app_api_news_')]
class NewsController extends ApiAbstractController
{

    public function __construct(private NewsRepository $repository) { }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $searchForm = $this->createForm(NewsSearchType::class);

        if (!$searchForm->submit($request->query->all())->isValid()) {
            return new Response('Parámetros inválidos', 400);
        }

        $filters = $searchForm->getData();

        $newsList = $this->repository->searchNews(
            $filters['title'] ?? null,
            $filters['from_date'] ?? null,
            $filters['to_date'] ?? null,
            $filters['is_active'] ?? null,
            $filters['category'] ?? null,
            $request->query->getInt('limit', 10),
            $request->query->getInt('offset', 0)
        );

        return $this->serializedResponse($newsList, ['news_list']);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $news = $this->repository->find($id);
        
        if (!$news) {
            throw $this->createNotFoundException('News not found');
        }

        return $this->serializedResponse($news, ['news_list']);
    }
}
