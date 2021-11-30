<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecaptchaV3Type extends AbstractRecaptchaType
{
    public function __construct(string $publicKey, bool $enabled, private bool $hideBadge, string $apiHost = 'www.google.com')
    {
        parent::__construct($publicKey, $enabled, $apiHost);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label'             => false,
            'validation_groups' => ['Default'],
            'script_nonce_csp'  => '',
            'error_bubbling'    => false,
        ]);

        $resolver->setAllowedTypes('script_nonce_csp', 'string');
    }

    public function getParent(): string
    {
        return HiddenType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'leapt_core_recaptcha_v3';
    }

    protected function addCustomVars(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars = array_replace($view->vars, [
            'leapt_core_recaptcha_hide_badge' => $this->hideBadge,
            'script_nonce_csp'                => $options['script_nonce_csp'] ?? '',
        ]);
    }
}
