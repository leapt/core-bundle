<?php

namespace Snowcap\CoreBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RequestListener
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        $event->getRequest()->setFormat('rss', 'application/rss+xml');
    }
}