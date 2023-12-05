<?php

declare(strict_types=1);

use Leapt\CoreBundle\Controller\FeedController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes): void {
    $routes->add('leapt_core_feed', '/{feedName}.{_format}')
        ->controller([FeedController::class, 'indexAction'])
        ->defaults(['_format' => 'rss'])
        ->requirements(['_format' => 'rss|atom'])
    ;
};
