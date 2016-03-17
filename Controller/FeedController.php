<?php

namespace Leapt\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FeedController
 * @package Leapt\CoreBundle\Controller
 */
class FeedController extends Controller
{
    /**
     * @param Request $request
     * @param string $feedName
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \ErrorException
     */
    public function indexAction(Request $request, $feedName)
    {
        $feedManager = $this->get('leapt_core.feed_manager');
        $feed = $feedManager->getFeed($feedName);

        $builtFeedItems = [];
        $items = $feed->getItems();
        foreach ($items as $item) {
            $builtItem = $feed->buildItem($item);
            $errors = $this->get('validator')->validate($builtItem);
            if (0 < count($errors)) {
                throw new \ErrorException('Invalid feed item');
            }
            $builtFeedItems[] = $builtItem;
        }

        return $this->render('LeaptCoreBundle:Feed:index.' . $request->getRequestFormat() . '.twig', [
            'feed'     => $feed,
            'feedName' => $feedName,
            'locale'   => $request->getLocale(),
            'items'    => $builtFeedItems
        ]);
    }
}