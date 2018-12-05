<?php

namespace Leapt\CoreBundle\Controller;

use Leapt\CoreBundle\Feed\FeedManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class FeedController
 * @package Leapt\CoreBundle\Controller
 */
class FeedController extends AbstractController
{
    /**
     * @var FeedManager
     */
    private $feedManager;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(FeedManager $feedManager, ValidatorInterface $validator)
    {
        $this->feedManager = $feedManager;
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @param string $feedName
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \ErrorException
     */
    public function indexAction(Request $request, $feedName)
    {
        $feed = $this->feedManager->getFeed($feedName);

        $builtFeedItems = [];
        $items = $feed->getItems();
        foreach ($items as $item) {
            $builtItem = $feed->buildItem($item);
            $errors = $this->validator->validate($builtItem);
            if (0 < \count($errors)) {
                throw new \ErrorException('Invalid feed item');
            }
            $builtFeedItems[] = $builtItem;
        }

        return $this->render('@LeaptCore/Feed/index.' . $request->getRequestFormat() . '.twig', [
            'feed'     => $feed,
            'feedName' => $feedName,
            'locale'   => $request->getLocale(),
            'items'    => $builtFeedItems,
        ]);
    }
}
