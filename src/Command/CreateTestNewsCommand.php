<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\News;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-test-news',
    description: 'Crea una noticia de prueba',
)]
class CreateTestNewsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Crear o obtener la categoría de prueba
        $category = $this->em->getRepository(Category::class)->findOneBy(['name' => 'General']);
        if (!$category) {
            $category = new Category();
            $category->setName('General');
            $category->setSlug('general');
            $this->em->persist($category);
        }

        $news = new News();
        $news->setTitle('Noticia de prueba');
        $news->setDescription('Esta es una noticia de prueba para verificar que el sistema funciona correctamente.');
        $news->setPublishedAt(new \DateTime());
        $news->setIsActive(true);
        $news->setCategory($category);

        $this->em->persist($news);
        $this->em->flush();

        $output->writeln('Noticia de prueba creada con éxito!');
        $output->writeln('ID: ' . $news->getId());
        $output->writeln('Título: ' . $news->getTitle());
        $output->writeln('Categoría: ' . $category->getName());

        return Command::SUCCESS;
    }
} 