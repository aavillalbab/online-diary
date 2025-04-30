<?php

namespace App\Controller\Pub;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadedFileController extends AbstractController
{
    #[Route('/uploaded-file', name: 'app_uploaded_file')]
    public function index(): Response
    {
        return $this->render('uploaded_file/index.html.twig', [
            'controller_name' => 'UploadedFileController',
        ]);
    }
}
