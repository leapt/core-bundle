<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Twig\TokenParser;

use Leapt\CoreBundle\Twig\Extension\PaginatorExtension;
use Leapt\CoreBundle\Twig\Node\PaginatorThemeNode;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Expression\Variable\ContextVariable;
use Twig\Node\Node;
use Twig\Source;

final class PaginatorThemeTokenParserTest extends TestCase
{
    /**
     * @param class-string<Node> $expectedResourcesClass
     */
    #[DataProvider('provideTemplates')]
    public function testParse(string $template, string $expectedResourcesClass, ?int $expectedResourcesCount): void
    {
        $env = new Environment(new ArrayLoader());
        $env->addExtension(new PaginatorExtension('@LeaptCore/Paginator/paginator_default_layout.html.twig', new RequestStack()));

        $node = $this->findPaginatorThemeNode($env->parse($env->tokenize(new Source($template, 'index'))));

        self::assertInstanceOf(PaginatorThemeNode::class, $node);
        self::assertInstanceOf(ContextVariable::class, $node->getNode('paginator'));
        self::assertSame('paginator', $node->getNode('paginator')->getAttribute('name'));

        $resources = $node->getNode('resources');
        self::assertInstanceOf($expectedResourcesClass, $resources);
        if (null !== $expectedResourcesCount) {
            \assert($resources instanceof ArrayExpression);
            self::assertCount($expectedResourcesCount, $resources->getKeyValuePairs());
        }
    }

    public static function provideTemplates(): iterable
    {
        yield 'single resource' => ["{% paginator_theme paginator 'theme.html.twig' %}", ArrayExpression::class, 1];
        yield 'multiple resources' => ["{% paginator_theme paginator 'first.html.twig' 'second.html.twig' %}", ArrayExpression::class, 2];
        yield 'with array' => ["{% paginator_theme paginator with ['first.html.twig', 'second.html.twig'] %}", ArrayExpression::class, 2];
        yield 'with variable' => ["{% paginator_theme paginator with themes %}", ContextVariable::class, null];
    }

    private function findPaginatorThemeNode(Node $node): ?PaginatorThemeNode
    {
        if ($node instanceof PaginatorThemeNode) {
            return $node;
        }

        foreach ($node as $child) {
            if (null !== $found = $this->findPaginatorThemeNode($child)) {
                return $found;
            }
        }

        return null;
    }
}
