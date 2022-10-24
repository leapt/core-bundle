<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Sitemap;

use Leapt\CoreBundle\Sitemap\AbstractSitemap;
use Symfony\Component\Routing\RouterInterface;

final class FirstSitemap extends AbstractSitemap
{
    public function build(RouterInterface $router): void
    {
        $this->addUrl($router->generate('leapt_core_feed', ['feedName' => 'news'], RouterInterface::ABSOLUTE_URL));
    }
}
