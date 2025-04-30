<?php

namespace App\DataFixtures\ORM;

use App\Entity\Category;

use Dsarhoya\FeatureFixtures\GenericFixtureBuilder;
use Exception;

class CategoryFixtureBuilder extends GenericFixtureBuilder
{

    private static int $index = 1;

    public function getClass(): string
    {
        return Category::class;
    }

    public function getData()
    {
        return array_merge([
        ], $this->data);
    }

    /**
     * @throws Exception
     */
    protected function createInstance(): object
    {
        ++self::$index;
        return parent::createInstance();
    }
}
