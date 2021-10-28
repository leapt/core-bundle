<?php

namespace Leapt\CoreBundle\Feed;

/**
 * Class FeedManager.
 */
class FeedManager
{
    protected array $feeds = [];

    /**
     * Register a feed in the manager.
     */
    public function registerFeed(string $alias, FeedInterface $feed)
    {
        $this->feeds[$alias] = $feed;
    }

    /**
     * Get a feed by name.
     *
     * @return FeedInterface
     *
     * @throws \InvalidArgumentException
     */
    public function getFeed(string $feedName)
    {
        if (!isset($this->feeds[$feedName])) {
            throw new \InvalidArgumentException(sprintf('Unknown feed "%s"', $feedName));
        }

        return $this->feeds[$feedName];
    }
}
