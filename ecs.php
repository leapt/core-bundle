<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ControlStructure\NoUselessElseFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\ReturnNotation\NoUselessReturnFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\Strict\StrictComparisonFixer;
use PhpCsFixer\Fixer\Strict\StrictParamFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withRootFiles()
    ->withRules([
        NoUselessElseFixer::class,
        NoUselessReturnFixer::class,
        StrictComparisonFixer::class,
        StrictParamFixer::class,
        DeclareStrictTypesFixer::class,
        NoUnusedImportsFixer::class,
    ])
    ->withPhpCsFixerSets(perCS20: true, symfonyRisky: true)
;
