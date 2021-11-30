<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Literal;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class BitwiseAnd extends FunctionNode
{
    private Literal $firstValue;

    private Literal $secondValue;

    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER); // (2)
        $parser->match(Lexer::T_OPEN_PARENTHESIS); // (3)
        $this->firstValue = $parser->ArithmeticPrimary(); // (4)
        $parser->match(Lexer::T_COMMA); // (5)
        $this->secondValue = $parser->ArithmeticPrimary(); // (6)
        $parser->match(Lexer::T_CLOSE_PARENTHESIS); // (3)
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return $this->firstValue->dispatch($sqlWalker) . ' & ' . $this->secondValue->dispatch($sqlWalker); // (7)
    }
}
