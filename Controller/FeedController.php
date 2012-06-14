<?php

namespace Snowcap\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Locale\Locale;

class FeedController extends Controller {

    /**
     * @Template
     */
    public function indexAction($feedName)
    {
        $feedManager = $this->get('snowcap_core.feed_manager');
        $feed = $feedManager->getFeed($feedName);

        $builtFeedItems = array();
        $items = $feed->getItems();
        foreach($items as $item) {
            $builtItem = $feed->buildItem($item);
            $errors = $this->get('validator')->validate($builtItem);
            if(count($errors) > 0) {
                throw new \ErrorException('Invalid feed item');
            }
            $builtFeedItems[]= $builtItem;
        }

        $locale = $this->getRequest()->getLocale();

        return array(
            'feed'=> $feed,
            'feedName' => $feedName,
            'locale' => $locale,
            'items' => $builtFeedItems
        );
    }
}