<?php

namespace App\Controller\Api;

use App\Form\Api\CategorySearchType;
use App\Repository\CategoryRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'app_api_categories_')]
class CategoryController extends ApiAbstractController
{

    public function __construct(private CategoryRepository $repository) { }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $searchForm = $this->createForm(CategorySearchType::class);

        if (!$searchForm->submit($request->query->all())->isValid()) {
            return new Response('Parámetros inválidos', 400);
        }

        $filters = $searchForm->getData();

        $categoriesList = $this->repository->searchCategories(
            $filters['name'] ?? null,
            $request->query->getInt('limit', 10),
            $request->query->getInt('offset', 0)
        );

        return $this->serializedResponse($categoriesList, ['category_list']);
    }
}
