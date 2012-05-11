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

        $locale = $this->getRequest()->getLocale();

        return array(
            'feed'=> $feed,
            'locale' => $locale,
            'items' => $feed->getItems($this->getDoctrine()->getEntityManager())
        );
    }
}