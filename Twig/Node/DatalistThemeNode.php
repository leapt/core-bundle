<?php

namespace Leapt\CoreBundle\Twig\Node;

/**
 * Class DatalistThemeNode
 * @package Leapt\CoreBundle\Twig\Node
 */
final class DatalistThemeNode extends \Twig_Node
{
    /**
     * @param \Twig_Node $datalist
     * @param \Twig_Node $resources
     * @param $lineno
     * @param null $tag
     */
    public function __construct(\Twig_Node $datalist, \Twig_Node $resources, $lineno, $tag = null)
    {
        parent::__construct(['datalist' => $datalist, 'resources' => $resources], [], $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param \Twig_Compiler $compiler A Twig_Compiler instance
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write('$this->env->getExtension(\'Leapt\CoreBundle\Twig\Extension\DatalistExtension\')->setTheme(')
            ->subcompile($this->getNode('datalist'))
            ->raw(', ')
            ->subcompile($this->getNode('resources'))
            ->raw(");\n");
        ;
    }
}