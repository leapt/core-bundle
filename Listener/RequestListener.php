<?php

namespace Leapt\CoreBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class RequestListener
 * @package Leapt\CoreBundle\Listener
 */
class RequestListener
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        $event->getRequest()->setFormat('rss', 'application/rss+xml');
    }
}