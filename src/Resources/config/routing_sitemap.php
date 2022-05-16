<?php

declare(strict_types=1);

use Leapt\CoreBundle\Controller\SitemapController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes): void {
    $routes->add('leapt_core_sitemap_default', '/sitemap.xml')
        ->controller([SitemapController::class, 'defaultAction'])
        ->defaults(['_format' => 'xml'])
        ->requirements(['_format' => 'xml'])
    ;

    $routes->add('leapt_core_sitemap_sitemap', '/sitemap_{sitemap}.xml')
        ->controller([SitemapController::class, 'sitemapAction'])
        ->defaults(['_format' => 'xml'])
        ->requirements(['_format' => 'xml'])
    ;
};
