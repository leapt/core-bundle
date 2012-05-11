<?php

namespace Snowcap\CoreBundle\Feed;

class FeedManager {

    /**
     * @var array
     */
    protected $feeds = array();

    /**
     * @param string $alias
     * @param FeedInterface $feed
     */
    public function registerFeed($alias, FeedInterface $feed) {
        $this->feeds[$alias]= $feed;
    }

    public function getFeed($feedName) {
        if(!isset($this->feeds[$feedName])) {
            throw new \InvalidArgumentException(sprintf('Unknown feed "%s"', $feedName));
        }
        return $this->feeds[$feedName];
    }
}