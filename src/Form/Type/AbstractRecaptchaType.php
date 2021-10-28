<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

abstract class AbstractRecaptchaType extends AbstractType
{
    /**
     * The public key.
     */
    protected string $publicKey;

    /**
     * Enable recaptcha?
     */
    protected bool $enabled;

    /**
     * The API server host name.
     */
    protected string $apiHost;

    /**
     * The reCAPTCHA server URL.
     */
    protected string $recaptchaApiServer;

    /**
     * @param string $publicKey Recaptcha public key
     * @param bool   $enabled   Recaptcha status
     * @param string $apiHost   Api host
     */
    public function __construct(string $publicKey, bool $enabled, string $apiHost = 'www.google.com')
    {
        $this->publicKey = $publicKey;
        $this->enabled = $enabled;
        $this->apiHost = $apiHost;
        $this->recaptchaApiServer = sprintf('https://%s/recaptcha/api.js', $apiHost);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
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

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'leapt_core_recaptcha';
    }

    /**
     * Gets the public key.
     *
     * @return string The javascript source URL
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Gets the API host name.
     *
     * @return string The hostname for API
     */
    public function getApiHost()
    {
        return $this->apiHost;
    }

    abstract protected function addCustomVars(FormView $view, FormInterface $form, array $options): void;
}
