<?php

namespace Snowcap\CoreBundle\Tests\Paginator;

use Doctrine\ORM\Query;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

use Faker\Factory as FakerFactory;

use Snowcap\CoreBundle\Paginator\DoctrineORMPaginator;
use Snowcap\CoreBundle\Tests\Paginator\Fixtures\LoadPlayerData;

class DoctrineORMPaginatorTest extends AbstractPaginatorTest
{
    /**
     * @var EntityManager
     */
    static protected $em;

    /**
     * Class initialization
     *
     */
    public static function setUpBeforeClass()
    {
        $dbParams = array(
            'driver'   => 'pdo_sqlite',
            'memory'   => true
        );

        $config = Setup::createAnnotationMetadataConfiguration(array(static::getEntityPath()), false);
        $cache = new \Doctrine\Common\Cache\ArrayCache();
        $driverImpl = $config->newDefaultAnnotationDriver(static::getEntityPath(), false);

        $config->setMetadataCacheImpl($cache);
        $config->setMetadataDriverImpl($driverImpl);
        $config->setQueryCacheImpl($cache);
        $proxiesIdentifier = uniqid('Proxies', true);
        $config->setProxyDir(sys_get_temp_dir() . '/' . $proxiesIdentifier);
        $config->setProxyNamespace('MyProject\Proxies\\' . $proxiesIdentifier);
        $config->setAutoGenerateProxyClasses(true);

        $em = EntityManager::create($dbParams, $config);

        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);

        $classes = array_map(function($className) use($em) {
            return $em->getClassMetadata($className);
        }, static::getEntityClasses());
        $tool->createSchema($classes);

        static::$em = $em;
    }

    /**
     * Test the IteratorAggregate implementation
     *
     */
    public function testIteration()
    {
        $this->assertTrue(true);
    }

    /**
     * Build a populated paginator instance
     *
     * @param int $limit
     * @return \Snowcap\CoreBundle\Paginator\PaginatorInterface
     */
    protected function buildPaginator($limit)
    {
        $this->loadFixture(new LoadPlayerData($limit));
        $dql = <<<DQL
            SELECT p FROM Snowcap\CoreBundle\Tests\Paginator\Entity\Player p
DQL;
        $query = static::$em->createQuery($dql)->setMaxResults($limit);

        $paginator = new DoctrineORMPaginator($query);

        return $paginator;
    }

    /**
     * Load the given fixture
     *
     * @param \Doctrine\Common\DataFixtures\FixtureInterface $fixture
     */
    protected function loadFixture(FixtureInterface $fixture)
    {
        $loader = new Loader();
        $loader->addFixture($fixture);
        $purger = new ORMPurger();
        $executor = new ORMExecutor(static::$em, $purger);
        $executor->execute($loader->getFixtures());
    }


    /**
     * Return an array of classes for which metadata should be loaded
     *
     * @return array
     */
    static protected function getEntityClasses()
    {
        return array('Snowcap\CoreBundle\Tests\Paginator\Entity\Player');
    }

    /**
     * Return the full path to the Entity directory
     *
     * @return string
     */
    static protected function getEntityPath()
    {
        return __DIR__ . "/Entity";
    }
}