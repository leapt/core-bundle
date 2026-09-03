<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Datalist\Field\Type;

use Leapt\CoreBundle\Datalist\DatalistFactory;
use Leapt\CoreBundle\Datalist\Datasource\ArrayDatasource;
use Leapt\CoreBundle\Datalist\Field\Type\DateTimeFieldType;
use Leapt\CoreBundle\Datalist\Type\DatalistType;
use Leapt\CoreBundle\Tests\Twig\Extension\Mocks\TranslatorMock;
use Leapt\CoreBundle\Twig\Extension\DateExtension;
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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class DateTimeFieldTypeTest extends TestCase
{
    public function testDefaultOptions(): void
    {
        $fieldType = new DateTimeFieldType();
        $resolver = new OptionsResolver();
        $fieldType->configureOptions($resolver);

        $options = $resolver->resolve();

        self::assertSame('d/m/Y', $options['format']);
        self::assertFalse($options['time_ago']);
    }

    public function testTimeAgoOptionCanBeEnabled(): void
    {
        $fieldType = new DateTimeFieldType();
        $resolver = new OptionsResolver();
        $fieldType->configureOptions($resolver);

        $options = $resolver->resolve(['time_ago' => true]);

        self::assertTrue($options['time_ago']);
    }

    public function testRendersFormattedDateByDefault(): void
    {
        $result = $this->renderDatetimeField(new \DateTime('2024-01-15'));
        self::assertStringContainsString('15/01/2024', $result);
    }

    public function testRendersTimeAgoWhenOptionEnabled(): void
    {
        $result = $this->renderDatetimeField(new \DateTime('now'), ['time_ago' => true]);
        self::assertStringContainsString('timeago.justnow', $result);
    }

    private function renderDatetimeField(\DateTime $value, array $options = []): string
    {
        $loader = new FilesystemLoader();
        $loader->addPath(__DIR__ . '/../../../../templates', 'LeaptCore');
        $env = new Environment($loader);

        $requestStack = $this->createStub(RequestStack::class);
        $router = $this->createStub(RouterInterface::class);
        $router->method('generate')->willReturn('');

        $datalistExtension = new DatalistExtension($requestStack);
        $env->addExtension($datalistExtension);
        $env->addExtension(new PaginatorExtension('', $requestStack));
        $env->addExtension(new RoutingExtension($router));
        $env->addExtension(new TranslationExtension());
        $env->addExtension(new TextExtension());
        $env->addExtension(new AssetExtension(new Packages()));
        $env->addExtension(new FormExtension());
        $env->addExtension(new DateExtension(new TranslatorMock()));

        $formBuilder = $this->createStub(FormBuilderInterface::class);
        $formBuilder->method('getForm')->willReturn($this->createStub(FormInterface::class));
        $formFactory = $this->createStub(FormFactoryInterface::class);
        $formFactory->method('createNamedBuilder')->willReturn($formBuilder);

        $datalistFactory = new DatalistFactory($formFactory, $router);

        $datalist = $datalistFactory->createBuilder(DatalistType::class)
            ->addField('publicationDate', DateTimeFieldType::class, $options)
            ->getDatalist();
        $datalist->setDatasource(new ArrayDatasource([]));

        $datalistExtension->setTheme($datalist, ['@LeaptCore/Datalist/datalist_bootstrap5_grid_layout.html.twig']);

        $field = $datalist->getFields()[0];

        return $datalistExtension->renderDatalistField($env, $field, ['publicationDate' => $value]);
    }
}
