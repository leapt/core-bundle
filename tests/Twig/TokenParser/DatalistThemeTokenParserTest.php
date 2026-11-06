<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Twig\TokenParser;

use Leapt\CoreBundle\Twig\Extension\DatalistExtension;
use Leapt\CoreBundle\Twig\Node\DatalistThemeNode;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Expression\Variable\ContextVariable;
use Twig\Node\Node;
use Twig\Source;

final class DatalistThemeTokenParserTest extends TestCase
{
    /**
     * @param class-string<Node> $expectedResourcesClass
     */
    #[DataProvider('provideTemplates')]
    public function testParse(string $template, string $expectedResourcesClass, ?int $expectedResourcesCount): void
    {
        $env = new Environment(new ArrayLoader());
        $env->addExtension(new DatalistExtension(new RequestStack()));

        $node = $this->findDatalistThemeNode($env->parse($env->tokenize(new Source($template, 'index'))));

        self::assertInstanceOf(DatalistThemeNode::class, $node);
        self::assertInstanceOf(ContextVariable::class, $node->getNode('datalist'));
        self::assertSame('datalist', $node->getNode('datalist')->getAttribute('name'));

        $resources = $node->getNode('resources');
        self::assertInstanceOf($expectedResourcesClass, $resources);
        if (null !== $expectedResourcesCount) {
            \assert($resources instanceof ArrayExpression);
            self::assertCount($expectedResourcesCount, $resources->getKeyValuePairs());
        }
    }

    public static function provideTemplates(): iterable
    {
        yield 'single resource' => ["{% datalist_theme datalist 'theme.html.twig' %}", ArrayExpression::class, 1];
        yield 'multiple resources' => ["{% datalist_theme datalist 'first.html.twig' 'second.html.twig' %}", ArrayExpression::class, 2];
        yield 'with array' => ["{% datalist_theme datalist with ['first.html.twig', 'second.html.twig'] %}", ArrayExpression::class, 2];
        yield 'with variable' => ["{% datalist_theme datalist with themes %}", ContextVariable::class, null];
    }

    private function findDatalistThemeNode(Node $node): ?DatalistThemeNode
    {
        if ($node instanceof DatalistThemeNode) {
            return $node;
        }

        foreach ($node as $child) {
            if (null !== $found = $this->findDatalistThemeNode($child)) {
                return $found;
            }
        }

        return null;
    }
}
