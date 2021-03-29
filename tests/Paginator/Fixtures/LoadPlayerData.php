<?php

namespace Leapt\CoreBundle\Tests\Paginator\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Leapt\CoreBundle\Tests\Paginator\Entity\Player;

class LoadPlayerData extends AbstractFixture
{
    /**
     * @var int
     */
    private $limit;

    /**
     * @param int $limit
     */
    public function __construct($limit)
    {
        $this->limit = $limit;
    }

    /**
     * Load data fixtures with the passed EntityManager.
     */
    public function load(ObjectManager $manager)
    {
        $faker = FakerFactory::create();
        for ($i = 1; $i <= $this->limit; ++$i) {
            $player = new Player();
            $player->setFirstName($faker->firstName());
            $player->setLastName($faker->lastName());
            $manager->persist($player);
        }

        $manager->flush();
    }
}
