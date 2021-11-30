<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Listener;

use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestListener
{
    public function onKernelRequest(RequestEvent $event)
    {
        $event->getRequest()->setFormat('rss', 'application/rss+xml');
    }
}
