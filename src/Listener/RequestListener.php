<?php

namespace Leapt\CoreBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;

if (!class_exists(RequestEvent::class)) {
    class_alias(GetResponseEvent::class, RequestEvent::class);
}

/**
 * Class RequestListener.
 */
class RequestListener
{
    public function onKernelRequest(RequestEvent $event)
    {
        $event->getRequest()->setFormat('rss', 'application/rss+xml');
    }
}
