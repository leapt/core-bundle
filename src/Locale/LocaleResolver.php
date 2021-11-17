<?php

namespace Leapt\CoreBundle\Locale;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Depending on the configuration resolves the correct locale for the reCAPTCHA.
 */
final class LocaleResolver
{
    public function __construct(
        private string $defaultLocale,
        private bool $useLocaleFromRequest,
        private RequestStack $requestStack
    ) {
    }

    /**
     * @return string The resolved locale key, depending on configuration
     */
    public function resolve()
    {
        return $this->useLocaleFromRequest
            ? $this->requestStack->getCurrentRequest()->getLocale()
            : $this->defaultLocale;
    }
}
