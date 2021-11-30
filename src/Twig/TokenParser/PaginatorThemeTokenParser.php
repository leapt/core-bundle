<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Twig\TokenParser;

use Leapt\CoreBundle\Twig\Node\PaginatorThemeNode;
use Twig\Node\Expression\ArrayExpression;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class PaginatorThemeTokenParser extends AbstractTokenParser
{
    public function parse(Token $token): PaginatorThemeNode
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();

        $paginator = $this->parser->getExpressionParser()->parseExpression();

        if ($this->parser->getStream()->test(Token::NAME_TYPE, 'with')) {
            $this->parser->getStream()->next();
            $resources = $this->parser->getExpressionParser()->parseExpression();
        } else {
            $resources = new ArrayExpression([], $stream->getCurrent()->getLine());
            do {
                $resources->addElement($this->parser->getExpressionParser()->parseExpression());
            } while (!$stream->test(Token::BLOCK_END_TYPE));
        }

        $stream->expect(Token::BLOCK_END_TYPE);

        return new PaginatorThemeNode($paginator, $resources, $lineno, $this->getTag());
    }

    public function getTag(): string
    {
        return 'paginator_theme';
    }
}
