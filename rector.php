<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Php80\Rector\Class_\StringableForToStringRector;
use Rector\Php80\Rector\ClassMethod\FinalPrivateToPrivateVisibilityRector;
use Rector\Php80\Rector\Identical\StrEndsWithRector;
use Rector\Php80\Rector\NotIdentical\StrContainsRector;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [
        __DIR__ . '/src'
    ]);

    // Define what rule sets will be applied
//    $containerConfigurator->import(SetList::DEAD_CODE);

    // get services (needed for register a single rule)
    $services = $containerConfigurator->services();
//    $services->set(FinalPrivateToPrivateVisibilityRector::class);
    $services->set(\Rector\Php80\Rector\Switch_\ChangeSwitchToMatchRector::class);

    // register a single rule
    // $services->set(TypedPropertyRector::class);
};
