<?php

namespace App\Tests\DataFixtures;

use Dsarhoya\FeatureFixtures\AbstractCase;

class ConfigurableFixture extends AbstractCase
{
    protected array $config = [];

    public static function new(): static
    {
        return new static();
    }

    public function config(array $config): static
    {
        $this->config = $config;

        return $this;
    }

    protected function getConfiguration(): array
    {
        return $this->config;
    }
}
