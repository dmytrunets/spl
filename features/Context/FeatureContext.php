<?php

namespace Context;

use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\Behat\Event\SuiteEvent;

use Behat\Behat\Context\Step\Given;
use Behat\Behat\Context\Step\When;
use Behat\Behat\Context\Step\Then;

use Behat\MinkExtension\Context\MinkContext;
use Symfony\Component\HttpKernel\KernelInterface;

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

/**
 * Features context.
 */
class FeatureContext extends MinkContext implements KernelAwareInterface
{
    /**
     * @var \Symfony\Component\HttpKernel\KernelInterface $kernel
     */
    private $kernel = null;

    /** 
     * @BeforeSuite 
     */
    public static function setup(SuiteEvent $event)
    {
        // `php app/console doctrine:database:drop --force --env=test`;
        // `php app/console doctrine:database:create --env=test`;
        // `php app/console doctrine:schema:create --env=test`;
    }

    /** 
     * @AfterSuite 
     */
    public static function teardown(SuiteEvent $event)
    {
        // `php app/console doctrine:database:drop --force --env=test`;
    }

    /**
     * @param \Behat\Behat\Event\ScenarioEvent|\Behat\Behat\Event\OutlineExampleEvent $event
     *
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
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * Returns Container instance.
     *
     * @return ContainerInterface
     */
    private function getContainer()
    {
        return $this->kernel->getContainer();
    }

    /**
     * @return array
     */
    protected function getConnections()
    {
        return $this->getContainer()->get('doctrine')->getConnections();
    }
}
