<?php

namespace Leapt\CoreBundle\Feed;

/**
 * Class FeedManager
 * @package Leapt\CoreBundle\Feed
 */
class FeedManager
{
    /**
     * @var array
     */
    protected $feeds = array();

    /**
     * Register a feed in the manager
     *
     * @param string $alias
     * @param FeedInterface $feed
     */
    public function registerFeed($alias, FeedInterface $feed)
    {
        $this->feeds[$alias]= $feed;
    }

    /**
     * Get a feed by name
     *
     * @param string $feedName
     * @return FeedInterface
     * @throws \InvalidArgumentException
     */
    public function getFeed($feedName) {
        if (!isset($this->feeds[$feedName])) {
            throw new \InvalidArgumentException(sprintf('Unknown feed "%s"', $feedName));
        }
        return $this->feeds[$feedName];
    }
}