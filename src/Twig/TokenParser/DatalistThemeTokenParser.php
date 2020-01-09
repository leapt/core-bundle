<?php

namespace Leapt\CoreBundle\Twig\TokenParser;

use Leapt\CoreBundle\Twig\Node\DatalistThemeNode;
use Twig\Node\Expression\ArrayExpression;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

/**
 * Class DatalistThemeTokenParser.
 */
final class DatalistThemeTokenParser extends AbstractTokenParser
{
    public function parse(Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();

        $datalist = $this->parser->getExpressionParser()->parseExpression();

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

        return new DatalistThemeNode($datalist, $resources, $lineno, $this->getTag());
    }

    public function getTag()
    {
        return 'datalist_theme';
    }
}
