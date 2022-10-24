<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Sitemap;

use Leapt\CoreBundle\Sitemap\AbstractSitemap;
use Symfony\Component\Routing\RouterInterface;

final class SecondSitemap extends AbstractSitemap
{
    public function build(RouterInterface $router): void
    {
        $this->addUrl($router->generate('leapt_core_feed', ['feedName' => 'fake1'], RouterInterface::ABSOLUTE_URL));
        $this->addUrl($router->generate('leapt_core_feed', ['feedName' => 'fake2'], RouterInterface::ABSOLUTE_URL));
    }
}
