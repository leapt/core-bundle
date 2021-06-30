<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Form\Type;

use Leapt\CoreBundle\Form\Type\RecaptchaV3Type;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class RecaptchaV3TypeTest extends TestCase
{
    private RecaptchaV3Type $type;

    protected function setUp(): void
    {
        $this->type = new RecaptchaV3Type('key', true, true, 'www.google.com');
    }

    public function testBuildView(): void
    {
        $view = new FormView();

        /** @var FormInterface $form */
        $form = $this->createMock(FormInterface::class);

        $this->assertArrayNotHasKey('leapt_core_recaptcha_enabled', $view->vars);
        $this->assertArrayNotHasKey('leapt_core_recaptcha_hide_badge', $view->vars);

        $this->type->buildView($view, $form, []);

        $this->assertTrue($view->vars['leapt_core_recaptcha_enabled']);
        $this->assertTrue($view->vars['leapt_core_recaptcha_hide_badge']);
    }

    public function testGetParent(): void
    {
        $this->assertSame(HiddenType::class, $this->type->getParent());
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
            'label'             => false,
            'validation_groups' => ['Default'],
            'error_bubbling'    => false,
            'script_nonce_csp'  => '',
        ];

        $this->assertSame($expected, $options);
    }

    public function testGetBlockPrefix(): void
    {
        $this->assertEquals('leapt_core_recaptcha_v3', $this->type->getBlockPrefix());
    }
}
