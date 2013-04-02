<?php

namespace Snowcap\CoreBundle\Test;

use Doctrine\ORM\Tools\SchemaTool;

require_once dirname(__DIR__).'/../../../../../app/AppKernel.php';

abstract class IntegrationTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Symfony\Component\HttpKernel\KernelInterface
     */
    protected $kernel;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    protected $container;

    public function setUp()
    {
        // Boot the AppKernel in the test environment and with the debug.
        $this->kernel = new \AppKernel('test', true);
        $this->kernel->boot();

        // Store the container and the entity manager in test case properties
        $this->container = $this->kernel->getContainer();
        $this->entityManager = $this->container->get('doctrine')->getEntityManager();

        // Build the schema for sqlite
        $this->generateSchema();

        parent::setUp();
    }

    public function tearDown()
    {
        // Shutdown the kernel.
        $this->kernel->shutdown();

        parent::tearDown();
    }

    protected function generateSchema()
    {
        // Get the metadatas of the application to create the schema.
        $metadatas = $this->getMetadatas();

        if ( ! empty($metadatas)) {
            // Create SchemaTool
            $tool = new SchemaTool($this->entityManager);
            try {
                $tool->dropDatabase();
            } catch(\Exception $e) {
                // the database should not exist, lets create it
            }
            //$tool->
            $tool->createSchema($metadatas);
        } else {
            throw new \Doctrine\DBAL\Schema\SchemaException('No Metadata Classes to process.');
        }
    }

    /**
     * Overwrite this method to get specific metadatas.
     *
     * @return Array
     */
    protected function getMetadatas()
    {
        return $this->entityManager->getMetadataFactory()->getAllMetadata();
    }

    /**
     * @param string $fixturesDirectory
     * @throws \InvalidArgumentException
     */
    protected function loadFixtures($fixturesDirectory)
    {
        $loader = new \Doctrine\Common\DataFixtures\Loader;
        $loader->loadFromDirectory($fixturesDirectory);
        $purger = new \Doctrine\Common\DataFixtures\Purger\ORMPurger($this->entityManager);
        $executor = new \Doctrine\Common\DataFixtures\Executor\ORMExecutor($this->entityManager, $purger);
        $executor->execute($loader->getFixtures());
    }

    protected function loadFixture($fixture)
    {
        $loader = new \Doctrine\Common\DataFixtures\Loader;
        $loader->addFixture($fixture);
        $purger = new \Doctrine\Common\DataFixtures\Purger\ORMPurger($this->entityManager);
        $executor = new \Doctrine\Common\DataFixtures\Executor\ORMExecutor($this->entityManager, $purger);
        $executor->execute($loader->getFixtures());
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry;
     */
    public function getDoctrine()
    {
        return $this->container->get('doctrine');
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface;
     */
    public function getEventDispatcher()
    {
        return $this->container->get('event_dispatcher');
    }
}