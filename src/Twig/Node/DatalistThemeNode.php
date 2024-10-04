<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Twig\Node;

use Twig\Attribute\YieldReady;
use Twig\Compiler;
use Twig\Node\Node;

#[YieldReady]
final class DatalistThemeNode extends Node
{
    public function __construct(Node $datalist, Node $resources, int $lineno)
    {
        parent::__construct(['datalist' => $datalist, 'resources' => $resources], [], $lineno);
    }

    public function compile(Compiler $compiler): void
    {
        $compiler
            ->addDebugInfo($this)
            ->write('$this->env->getExtension(\'Leapt\CoreBundle\Twig\Extension\DatalistExtension\')->setTheme(')
            ->subcompile($this->getNode('datalist'))
            ->raw(', ')
            ->subcompile($this->getNode('resources'))
            ->raw(");\n");
    }
}
