<?php

namespace Leapt\CoreBundle\Feed;

/**
 * Class FeedManager.
 */
class FeedManager
{
    /**
     * @var array
     */
    protected $feeds = [];

    /**
     * Register a feed in the manager.
     *
     * @param string $alias
     */
    public function registerFeed($alias, FeedInterface $feed)
    {
        $this->feeds[$alias] = $feed;
    }

    /**
     * Get a feed by name.
     *
     * @param string $feedName
     *
     * @return FeedInterface
     *
     * @throws \InvalidArgumentException
     */
    public function getFeed($feedName)
    {
        if (!isset($this->feeds[$feedName])) {
            throw new \InvalidArgumentException(sprintf('Unknown feed "%s"', $feedName));
        }

        return $this->feeds[$feedName];
    }
}
