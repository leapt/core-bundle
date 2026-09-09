<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Alias\ArrayPushFixer;
use PhpCsFixer\Fixer\Alias\ModernizeStrposFixer;
use PhpCsFixer\Fixer\Alias\NoAliasFunctionsFixer;
use PhpCsFixer\Fixer\Alias\SetTypeToCastFixer;
use PhpCsFixer\Fixer\Basic\NonPrintableCharacterFixer;
use PhpCsFixer\Fixer\Basic\PsrAutoloadingFixer;
use PhpCsFixer\Fixer\CastNotation\ModernizeTypesCastingFixer;
use PhpCsFixer\Fixer\ClassNotation\NoPhp4ConstructorFixer;
use PhpCsFixer\Fixer\ClassNotation\NoUnneededFinalMethodFixer;
use PhpCsFixer\Fixer\ClassNotation\OrderedTraitsFixer;
use PhpCsFixer\Fixer\ClassNotation\SelfAccessorFixer;
use PhpCsFixer\Fixer\ConstantNotation\NativeConstantInvocationFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUselessElseFixer;
use PhpCsFixer\Fixer\FunctionNotation\CombineNestedDirnameFixer;
use PhpCsFixer\Fixer\FunctionNotation\ImplodeCallFixer;
use PhpCsFixer\Fixer\FunctionNotation\NativeFunctionInvocationFixer;
use PhpCsFixer\Fixer\FunctionNotation\NoUselessSprintfFixer;
use PhpCsFixer\Fixer\FunctionNotation\StaticLambdaFixer;
use PhpCsFixer\Fixer\FunctionNotation\VoidReturnFixer;
use PhpCsFixer\Fixer\LanguageConstruct\DirConstantFixer;
use PhpCsFixer\Fixer\LanguageConstruct\FunctionToConstantFixer;
use PhpCsFixer\Fixer\LanguageConstruct\GetClassToClassKeywordFixer;
use PhpCsFixer\Fixer\LanguageConstruct\IsNullFixer;
use PhpCsFixer\Fixer\Naming\NoHomoglyphNamesFixer;
use PhpCsFixer\Fixer\Operator\LogicalOperatorsFixer;
use PhpCsFixer\Fixer\Operator\LongToShorthandOperatorFixer;
use PhpCsFixer\Fixer\Operator\TernaryToElvisOperatorFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitConstructFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitMockShortWillReturnFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitSetUpTearDownVisibilityFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestAnnotationFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\Strict\StrictComparisonFixer;
use PhpCsFixer\Fixer\Strict\StrictParamFixer;
use PhpCsFixer\Fixer\StringNotation\StringLengthToEmptyFixer;
use PhpCsFixer\Fixer\StringNotation\StringLineEndingFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withRootFiles()
    ->withConfiguredRule(NativeConstantInvocationFixer::class, ['strict' => false])
    ->withConfiguredRule(NativeFunctionInvocationFixer::class, [
        'include' => ['@compiler_optimized'],
        'scope' => 'namespaced',
        'strict' => true,
    ])
    ->withConfiguredRule(VoidReturnFixer::class, ['fix_lambda' => false])
    ->withRules([
        ArrayPushFixer::class,
        CombineNestedDirnameFixer::class,
        DeclareStrictTypesFixer::class,
        DirConstantFixer::class,
        FunctionToConstantFixer::class,
        GetClassToClassKeywordFixer::class,
        ImplodeCallFixer::class,
        IsNullFixer::class,
        LogicalOperatorsFixer::class,
        LongToShorthandOperatorFixer::class,
        ModernizeStrposFixer::class,
        ModernizeTypesCastingFixer::class,
        NoAliasFunctionsFixer::class,
        NoHomoglyphNamesFixer::class,
        NoPhp4ConstructorFixer::class,
        NoUnneededFinalMethodFixer::class,
        NoUselessElseFixer::class,
        NoUselessSprintfFixer::class,
        NonPrintableCharacterFixer::class,
        OrderedTraitsFixer::class,
        PhpUnitConstructFixer::class,
        PhpUnitMockShortWillReturnFixer::class,
        PhpUnitSetUpTearDownVisibilityFixer::class,
        PhpUnitTestAnnotationFixer::class,
        PsrAutoloadingFixer::class,
        SelfAccessorFixer::class,
        SetTypeToCastFixer::class,
        StaticLambdaFixer::class,
        StrictComparisonFixer::class,
        StrictParamFixer::class,
        StringLengthToEmptyFixer::class,
        StringLineEndingFixer::class,
        TernaryToElvisOperatorFixer::class,
    ])
    ->withPreparedSets(perCs: true);
