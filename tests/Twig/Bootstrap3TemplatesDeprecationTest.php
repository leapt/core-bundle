<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Twig;

use Leapt\CoreBundle\Datalist\DatalistFactory;
use Leapt\CoreBundle\Datalist\Datasource\ArrayDatasource;
use Leapt\CoreBundle\Datalist\Field\Type\TextFieldType;
use Leapt\CoreBundle\Datalist\Type\DatalistType;
use Leapt\CoreBundle\Paginator\ArrayPaginator;
use Leapt\CoreBundle\Tests\Twig\Extension\Mocks\TranslatorMock;
use Leapt\CoreBundle\Twig\Extension\DatalistExtension;
use Leapt\CoreBundle\Twig\Extension\DateExtension;
use Leapt\CoreBundle\Twig\Extension\PaginatorExtension;
use Leapt\CoreBundle\Twig\Extension\TextExtension;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class Bootstrap3TemplatesDeprecationTest extends TestCase
{
    private const string DEPRECATION_REGEX = '/^Since leapt\/core-bundle 6\.5: Bootstrap 3 templates are deprecated and will be removed in 7\.0, use the Bootstrap 5 ones instead\. in "%s" at line \d+\.$/';

    private Environment $env;
    private DatalistExtension $datalistExtension;
    private PaginatorExtension $paginatorExtension;
    private RouterInterface $router;

    protected function setUp(): void
    {
        $loader = new FilesystemLoader();
        $loader->addPath(__DIR__ . '/../../templates', 'LeaptCore');
        $this->env = new Environment($loader);

        $this->router = $this->createStub(RouterInterface::class);
        $this->router->method('generate')->willReturn('');

        $request = new Request();
        $request->attributes->set('_route', 'test');
        $requestStack = new RequestStack();
        $requestStack->push($request);

        $this->datalistExtension = new DatalistExtension($requestStack);
        $this->paginatorExtension = new PaginatorExtension('@LeaptCore/Paginator/paginator_default_layout.html.twig', $requestStack);
        $this->env->addExtension($this->datalistExtension);
        $this->env->addExtension($this->paginatorExtension);
        $this->env->addExtension(new RoutingExtension($this->router));
        $this->env->addExtension(new TranslationExtension());
        $this->env->addExtension(new TextExtension());
        $this->env->addExtension(new AssetExtension(new Packages()));
        $this->env->addExtension(new FormExtension());
        $this->env->addExtension(new DateExtension(new TranslatorMock()));
    }

    #[DataProvider('provideDatalistTemplates')]
    #[IgnoreDeprecations]
    public function testDatalistTemplateIsDeprecated(string $template): void
    {
        $formBuilder = $this->createStub(FormBuilderInterface::class);
        $formBuilder->method('getForm')->willReturn($this->createStub(FormInterface::class));
        $formFactory = $this->createStub(FormFactoryInterface::class);
        $formFactory->method('createNamedBuilder')->willReturn($formBuilder);

        $datalist = (new DatalistFactory($formFactory, $this->router))->createBuilder(DatalistType::class)
            ->addField('title', TextFieldType::class)
            ->getDatalist();
        $datalist->setDatasource(new ArrayDatasource([['title' => 'test']]));
        $this->datalistExtension->setTheme($datalist, [$template]);

        $this->expectUserDeprecationMessageMatches(\sprintf(self::DEPRECATION_REGEX, preg_quote($template, '/')));

        $this->datalistExtension->renderDatalistWidget($this->env, $datalist);
    }

    public static function provideDatalistTemplates(): iterable
    {
        yield 'grid' => ['@LeaptCore/Datalist/datalist_bootstrap3_grid_layout.html.twig'];
        yield 'tiled' => ['@LeaptCore/Datalist/datalist_bootstrap3_tiled_layout.html.twig'];
    }

    #[IgnoreDeprecations]
    public function testPaginatorTemplateIsDeprecated(): void
    {
        $template = '@LeaptCore/Paginator/paginator_bootstrap3_layout.html.twig';
        $paginator = (new ArrayPaginator(range(1, 50)))->setLimitPerPage(10)->setPage(2);
        $this->paginatorExtension->setTheme($paginator, [$template]);

        $this->expectUserDeprecationMessageMatches(\sprintf(self::DEPRECATION_REGEX, preg_quote($template, '/')));

        $this->paginatorExtension->renderPaginatorWidget($this->env, $paginator);
    }

    public function testFormTemplateCompiles(): void
    {
        $template = '@LeaptCore/Form/bootstrap_3_layout.html.twig';
        $source = $this->env->compileSource($this->env->getLoader()->getSourceContext($template));

        // The deprecation is triggered from both widget blocks
        self::assertSame(2, substr_count($source, 'Bootstrap 3 templates are deprecated'));
    }
}
