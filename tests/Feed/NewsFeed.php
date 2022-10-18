<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Feed;

use Leapt\CoreBundle\Feed\FeedInterface;
use Leapt\CoreBundle\Feed\FeedItem;

final class NewsFeed implements FeedInterface
{
    public function getId(): string
    {
        return 'https://website.com/';
    }

    public function getLink(): string
    {
        return 'https://website.com/';
    }

    public function getTitle(): string
    {
        return 'ACME website';
    }

    public function getDescription(): string
    {
        return 'ACME Description';
    }

    public function getUpdatedAt(): \DateTime
    {
        return iterator_to_array($this->getItems())[0]['publicationDate'];
    }

    public function getItems(): iterable
    {
        $faker = \Faker\Factory::create();

        for ($i = 1; 10 >= $i; ++$i) {
            yield [
                'title'           => $faker->sentence(),
                'content'         => $faker->paragraph(),
                'slug'            => $faker->slug(),
                'publicationDate' => $faker->dateTime(),
                'lastUpdate'      => $faker->dateTime(),
                'author'          => $faker->userName(),
                'email'           => $faker->email(),
            ];
        }
    }

    public function buildItem($item): FeedItem
    {
        $link = 'https://website.com/' . $item['slug'];

        $feedItem = new FeedItem();
        $feedItem->id = $link;
        $feedItem->title = $item['title'];
        $feedItem->description = $item['content'];
        $feedItem->createdAt = $item['publicationDate'];
        $feedItem->updatedAt = $item['lastUpdate'];
        $feedItem->link = $link;
        $feedItem->author = ['name' => $item['author'], 'email' => $item['email']];

        return $feedItem;
    }
}
