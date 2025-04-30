<?php

namespace App\DataFixtures\Case;

use App\Entity\Category;
use App\Entity\News;

use Dsarhoya\FeatureFixtures\AbstractCase;
use Dsarhoya\FeatureFixtures\GenericFixtureBuilder;

class BaseCase extends AbstractCase
{
    protected function getConfiguration(): array
    {
        return [
            GenericFixtureBuilder::config(Category::class, [
                'reference' => 'category_sports',
                'name' => 'Deportes',
                'slug' => 'deportes',
            ]),
            GenericFixtureBuilder::config(News::class, [
                'title' => 'Gran victoria del equipo local',
                'description' => 'El equipo local logró una importante victoria en el último partido.',
                'isActive' => true,
                'publishedAt' => new \DateTime('2023-10-01 12:00:00'),
                'category' => 'ref-category_sports',
            ]),
            GenericFixtureBuilder::config(News::class, [
                'title' => 'Nuevo récord mundial',
                'description' => 'Un atleta estableció un nuevo récord mundial en la competencia.',
                'isActive' => true,
                'publishedAt' => new \DateTime('2023-10-02 12:00:00'),
                'category' => 'ref-category_sports',
            ]),
        ];
    }
}
