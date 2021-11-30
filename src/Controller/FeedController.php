<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Controller;

use Leapt\CoreBundle\Feed\FeedManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twig\Environment;

class FeedController
{
    public function __construct(
        private FeedManager $feedManager,
        private ValidatorInterface $validator,
        private Environment $twig,
    ) {
    }

    public function indexAction(Request $request, string $feedName): Response
    {
        $feed = $this->feedManager->getFeed($feedName);

        $builtFeedItems = [];
        $items = $feed->getItems();
        foreach ($items as $item) {
            $builtItem = $feed->buildItem($item);
            $errors = $this->validator->validate($builtItem);
            if (0 < \count($errors)) {
                throw new HttpException(500, 'Invalid feed item');
            }
            $builtFeedItems[] = $builtItem;
        }

        return new Response($this->twig->render('@LeaptCore/Feed/index.' . $request->getRequestFormat() . '.twig', [
            'feed'     => $feed,
            'feedName' => $feedName,
            'locale'   => $request->getLocale(),
            'items'    => $builtFeedItems,
        ]));
    }
}
