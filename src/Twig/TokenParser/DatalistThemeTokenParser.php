<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Twig\TokenParser;

use Leapt\CoreBundle\Twig\Node\DatalistThemeNode;
use Twig\Node\Expression\ArrayExpression;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

final class DatalistThemeTokenParser extends AbstractTokenParser
{
    public function parse(Token $token): DatalistThemeNode
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();

        if (method_exists($this->parser, 'parseExpression')) {
            // Since Twig 3.21
            $datalist = $this->parser->parseExpression();
        } else {
            $datalist = $this->parser->getExpressionParser()->parseExpression();
        }

        if ($this->parser->getStream()->test(Token::NAME_TYPE, 'with')) {
            $this->parser->getStream()->next();
            if (method_exists($this->parser, 'parseExpression')) {
                // Since Twig 3.21
                $resources = $this->parser->parseExpression();
            } else {
                $resources = $this->parser->getExpressionParser()->parseExpression();
            }
        } else {
            $resources = new ArrayExpression([], $stream->getCurrent()->getLine());
            do {
                if (method_exists($this->parser, 'parseExpression')) {
                    // Since Twig 3.21
                    $resources->addElement($this->parser->parseExpression());
                } else {
                    $resources->addElement($this->parser->getExpressionParser()->parseExpression());
                }
            } while (!$stream->test(Token::BLOCK_END_TYPE));
        }

        $stream->expect(Token::BLOCK_END_TYPE);

        return new DatalistThemeNode($datalist, $resources, $lineno);
    }

    public function getTag(): string
    {
        return 'datalist_theme';
    }
}
