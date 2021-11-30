<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

abstract class AbstractRecaptchaType extends AbstractType
{
    /**
     * The reCAPTCHA server URL.
     */
    protected string $recaptchaApiServer;

    /**
     * @param string $publicKey Recaptcha public key
     * @param bool   $enabled   Recaptcha status
     * @param string $apiHost   Api host
     */
    public function __construct(protected string $publicKey, protected bool $enabled, protected string $apiHost = 'www.google.com')
    {
        $this->recaptchaApiServer = sprintf('https://%s/recaptcha/api.js', $apiHost);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars = array_replace($view->vars, [
            'leapt_core_recaptcha_enabled'  => $this->enabled,
            'leapt_core_recaptcha_api_host' => $this->apiHost,
            'leapt_core_recaptcha_api_uri'  => $this->recaptchaApiServer,
            'public_key'                    => $this->publicKey,
        ]);

        if (!$this->enabled) {
            return;
        }

        $this->addCustomVars($view, $form, $options);
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    public function getApiHost(): string
    {
        return $this->apiHost;
    }

    abstract protected function addCustomVars(FormView $view, FormInterface $form, array $options): void;
}
