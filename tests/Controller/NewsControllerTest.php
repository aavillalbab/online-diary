<?php

namespace App\Tests\Controller;

use App\Entity\Category;
use App\Entity\News;
use App\Tests\DataFixtures\ConfigurableFixture;
use App\Tests\Utils\DSYClientTrait;
use App\Tests\Utils\FixtureAwareTestCase;

use Dsarhoya\FeatureFixtures\GenericFixtureBuilder;

class NewsControllerTest extends FixtureAwareTestCase
{
    use DSYClientTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->addFixture(ConfigurableFixture::new()->config([
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
        ]));

        $this->executeFixtures();
    }

    public function testGetNewsList(): void
    {
        $category = $this->getReferenceRepository()->getReference('category_sports');
        $categoryId = $category->getId();

        $dSYClient = $this->makeAnonymousClient();

        $dSYClient->getJson(
            '/api/v1/news',
            [
                'page' => 1,
                'limit' => 10,
                'category_id' => $categoryId,
            ],
            [
                'Accept' => 'application/json',
            ]
        );

        $response = $dSYClient->getResponseAsArray();

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertCount(2, $response);
    }
}
