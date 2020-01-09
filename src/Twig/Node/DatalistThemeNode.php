<?php

namespace Leapt\CoreBundle\Twig\Node;

use Twig\Compiler;
use Twig\Node\Node;

/**
 * Class DatalistThemeNode.
 */
final class DatalistThemeNode extends Node
{
    /**
     * @param $lineno
     * @param null $tag
     */
    public function __construct(Node $datalist, Node $resources, $lineno, $tag = null)
    {
        parent::__construct(['datalist' => $datalist, 'resources' => $resources], [], $lineno, $tag);
    }

    public function compile(Compiler $compiler)
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
