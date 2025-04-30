<?php

namespace App\Tests\Utils;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\ORM\EntityManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;

abstract class FixtureAwareTestCase extends WebTestCase
{
    use DSYClientTrait;

    private ?ORMExecutor $fixtureExecutor = null;
    private ?ContainerAwareLoader $fixtureLoader = null;

    public function setUp(): void
    {
        self::bootKernel();
    }

    protected function addFixture(FixtureInterface $fixture): void
    {
        $this->getPrivateFixtureLoader()->addFixture($fixture);
    }

    protected function executeFixtures(): void
    {
        $this->getFixtureExecutor()->execute($this->getPrivateFixtureLoader()->getFixtures());
    }

    private function getFixtureExecutor(): ORMExecutor
    {
        if (!$this->fixtureExecutor) {
            /** @var EntityManager $entityManager */
            $entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
            $this->fixtureExecutor = new ORMExecutor($entityManager, new ORMPurger($entityManager));
        }

        return $this->fixtureExecutor;
    }

    protected function getPrivateFixtureLoader(): ContainerAwareLoader
    {
        if (!$this->fixtureLoader) {
            $this->fixtureLoader = new ContainerAwareLoader(self::$kernel->getContainer());
        }

        return $this->fixtureLoader;
    }

    public function getReferenceRepository(): ReferenceRepository
    {
        return $this->fixtureExecutor->getReferenceRepository();
    }
}
