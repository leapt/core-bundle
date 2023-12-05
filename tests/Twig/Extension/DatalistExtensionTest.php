<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Twig\Extension;

use Leapt\CoreBundle\Datalist\Action\Type\SimpleActionType;
use Leapt\CoreBundle\Datalist\Datalist;
use Leapt\CoreBundle\Datalist\DatalistFactory;
use Leapt\CoreBundle\Datalist\Datasource\ArrayDatasource;
use Leapt\CoreBundle\Datalist\Field\Type\TextFieldType;
use Leapt\CoreBundle\Datalist\Type\DatalistType;
use Leapt\CoreBundle\Twig\Extension\DatalistExtension;
use Leapt\CoreBundle\Twig\Extension\PaginatorExtension;
use Leapt\CoreBundle\Twig\Extension\TextExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class DatalistExtensionTest extends TestCase
{
    private Environment $env;
    private DatalistExtension $extension;
    private DatalistFactory $datalistFactory;

    protected function setUp(): void
    {
        $loader = new FilesystemLoader();
        $loader->addPath(__DIR__ . '/../../../templates', 'LeaptCore');
        $this->env = new Environment($loader);

        $router = $this->createMock(RouterInterface::class);
        $router->method('generate')->willReturn('');

        $requestStack = $this->createMock(RequestStack::class);
        $this->extension = new DatalistExtension($requestStack);
        $this->env->addExtension($this->extension);
        $this->env->addExtension(new PaginatorExtension('', $requestStack));
        $this->env->addExtension(new RoutingExtension($router));
        $this->env->addExtension(new TranslationExtension());
        $this->env->addExtension(new TextExtension());
        $this->env->addExtension(new AssetExtension(new Packages()));
        $this->env->addExtension(new FormExtension());

        $formBuilder = $this->createMock(FormBuilderInterface::class);
        $formBuilder->method('getForm')->willReturn($this->createMock(FormInterface::class));
        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->method('createNamedBuilder')->willReturn($formBuilder);

        $this->datalistFactory = new DatalistFactory(
            $formFactory,
            $router,
        );
    }

    public function testDefaultActionsContainerClass(): void
    {
        $datalist = $this->getDatalist();
        $result = $this->extension->renderDatalistWidget($this->env, $datalist);
        self::assertStringContainsString('<div class="btn-group">', $result);
    }

    public function testCustomActionsContainerClass(): void
    {
        $datalist = $this->getDatalist(['actions_container_class' => 'btn-group btn-group-sm']);
        $result = $this->extension->renderDatalistWidget($this->env, $datalist);
        self::assertStringContainsString('<div class="btn-group btn-group-sm">', $result);
    }

    private function getDatalist(array $options = []): Datalist
    {
        $datalist = $this->datalistFactory->createBuilder(DatalistType::class, $options)
            ->addField('title', TextFieldType::class)
            ->addAction('update', SimpleActionType::class, ['route' => 'test'])
            ->getDatalist();
        $datasource = new ArrayDatasource([['item1' => ['title' => 'test1'], 'item2' => ['title' => 'test2']]]);
        $datalist->setDatasource($datasource);

        return $datalist;
    }
}
