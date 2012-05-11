<?php

namespace Snowcap\CoreBundle\Feed;

interface FeedInterface {
    public function getChannelTitle();
    public function getChannelDescription();
}