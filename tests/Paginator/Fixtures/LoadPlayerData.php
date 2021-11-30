<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Paginator\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Leapt\CoreBundle\Tests\Paginator\Entity\Player;

class LoadPlayerData extends AbstractFixture
{
    public function __construct(private int $limit)
    {
    }

    public function load(ObjectManager $manager): void
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
