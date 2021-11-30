<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Feed;

class FeedManager
{
    protected array $feeds = [];

    public function registerFeed(string $alias, FeedInterface $feed): void
    {
        $this->feeds[$alias] = $feed;
    }

    public function getFeed(string $feedName): FeedInterface
    {
        if (!isset($this->feeds[$feedName])) {
            throw new \InvalidArgumentException(sprintf('Unknown feed "%s"', $feedName));
        }

        return $this->feeds[$feedName];
    }
}
