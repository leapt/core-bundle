<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Form\Type;

use Leapt\CoreBundle\Form\Type\RecaptchaType;
use Leapt\CoreBundle\Locale\LocaleResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class RecaptchaTypeTest extends TestCase
{
    private RecaptchaType $type;

    protected function setUp(): void
    {
        $requestStack = $this->createMock(RequestStack::class);
        $localeResolver = new LocaleResolver('de', false, $requestStack);
        $this->type = new RecaptchaType('key', true, true, $localeResolver, 'www.google.com');
    }

    public function testBuildView(): void
    {
        $view = new FormView();

        $form = $this->createMock(FormInterface::class);

        $this->assertArrayNotHasKey('leapt_core_recaptcha_enabled', $view->vars);
        $this->assertArrayNotHasKey('leapt_core_recaptcha_ajax', $view->vars);

        $this->type->buildView($view, $form, []);

        $this->assertTrue($view->vars['leapt_core_recaptcha_enabled']);
        $this->assertTrue($view->vars['leapt_core_recaptcha_ajax']);
    }

    public function testGetParent(): void
    {
        $this->assertSame(TextType::class, $this->type->getParent());
    }

    public function testGetPublicKey(): void
    {
        $this->assertSame('key', $this->type->getPublicKey());
    }

    public function testConfigureOptions(): void
    {
        $optionsResolver = new OptionsResolver();

        $this->type->configureOptions($optionsResolver);

        $options = $optionsResolver->resolve();

        $expected = [
            'compound'      => false,
            'language'      => 'de',
            'public_key'    => null,
            'url_challenge' => null,
            'url_noscript'  => null,
            'attr'          => [
                'options' => [
                    'theme'           => 'light',
                    'type'            => 'image',
                    'size'            => 'normal',
                    'callback'        => null,
                    'expiredCallback' => null,
                    'bind'            => null,
                    'defer'           => false,
                    'async'           => false,
                    'badge'           => null,
                ],
            ],
        ];

        $this->assertSame($expected, $options);
    }

    public function testGetBlockPrefix(): void
    {
        $this->assertSame('leapt_core_recaptcha', $this->type->getBlockPrefix());
    }
}
