<?php

namespace Context;

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Testwork\Hook\Scope\AfterSuiteScope;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Features context.
 */
class DefaultContext extends RawMinkContext implements Context, KernelAwareContext
{
    /**
     * @var \Symfony\Component\HttpKernel\KernelInterface $kernel
     */
    private $kernel = null;

    /**
     * @BeforeSuite
     *
     * @param BeforeSuiteScope $event
     */
    public static function setup(BeforeSuiteScope $event)
    {
        // `php app/console doctrine:database:drop --force --env=test`;
        // `php app/console doctrine:database:create --env=test`;
        // `php app/console doctrine:schema:create --env=test`;
    }

    /**
     * @AfterSuite
     *
     * @param AfterSuiteScope $event
     */
    public static function teardown(AfterSuiteScope $event)
    {
        // `php app/console doctrine:database:drop --force --env=test`;
    }

    /**
     * @AfterScenario
     *
     * @return null
     */
    public function closeDBALConnections($event)
    {
        /** @var EntityManager $entityManager */
        foreach ($this->getEntityManagers() as $entityManager) {
            $entityManager->clear();
        }

        /** @var Connection $connection */
        foreach ($this->getConnections() as $connection) {
            $connection->close();
        }
    }

    /**
     * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
     *
     * @return null
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @param EntityManager $entityManager
     *
     * @return array
     */
    protected function getMetadata(EntityManager $entityManager)
    {
        return $entityManager->getMetadataFactory()->getAllMetadata();
    }

    /**
     * @return array
     */
    protected function getEntityManagers()
    {
        return $this->getContainer()->get('doctrine')->getManagers();
    }

    /**
     * Returns EntityManager
     *
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @return array
     */
    protected function getConnections()
    {
        return $this->getContainer()->get('doctrine')->getConnections();
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private function getContainer()
    {
        return $this->kernel->getContainer();
    }
}
