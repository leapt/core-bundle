<?php

namespace Snowcap\CoreBundle\Twig\Node;

class PaginatorThemeNode extends \Twig_Node
{
    /**
     * @param \Twig_NodeInterface $datalist
     * @param \Twig_NodeInterface $resources
     * @param $lineno
     * @param null $tag
     */
    public function __construct(\Twig_NodeInterface $paginator, \Twig_NodeInterface $resources, $lineno, $tag = null)
    {
        parent::__construct(array('paginator' => $paginator, 'resources' => $resources), array(), $lineno, $tag);
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
            ->write('$this->env->getExtension(\'snowcap_core_paginator\')->setTheme(')
            ->subcompile($this->getNode('paginator'))
            ->raw(', ')
            ->subcompile($this->getNode('resources'))
            ->raw(");\n");
        ;
    }
}