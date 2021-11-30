<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Form\Type;

use Leapt\CoreBundle\Locale\LocaleResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecaptchaType extends AbstractRecaptchaType
{
    public function __construct($publicKey, $enabled, protected bool $ajax, protected LocaleResolver $localeResolver, $apiHost = 'www.google.com')
    {
        parent::__construct($publicKey, $enabled, $apiHost);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'compound'      => false,
            'language'      => $this->localeResolver->resolve(),
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
        ]);
    }

    public function getParent(): string
    {
        return TextType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'leapt_core_recaptcha';
    }

    protected function addCustomVars(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars = array_replace($view->vars, [
            'leapt_core_recaptcha_ajax' => $this->ajax,
        ]);

        if (!isset($options['language'])) {
            $options['language'] = $this->localeResolver->resolve();
        }

        if (!$this->ajax) {
            $view->vars = array_replace($view->vars, [
                'url_challenge' => sprintf('%s?hl=%s', $this->recaptchaApiServer, $options['language']),
            ]);
        } else {
            $view->vars = array_replace($view->vars, [
                'url_api' => sprintf('//%s/recaptcha/api/js/recaptcha_ajax.js', $this->apiHost),
            ]);
        }
    }
}
