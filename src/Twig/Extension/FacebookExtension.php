<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Twig\Extension;

use Twig\DeprecatedCallableInfo;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @deprecated since leapt/core-bundle 6.5, will be removed in 7.0, as it relies on an outdated Facebook SDK version
 */
class FacebookExtension extends AbstractExtension
{
    public function __construct(private ?string $appId) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('facebook_sdk_code', [$this, 'getFacebookSdkCode'], ['is_safe' => ['html'], 'needs_environment' => true, 'deprecation_info' => new DeprecatedCallableInfo('leapt/core-bundle', '6.5')]),
        ];
    }

    public function getFacebookSdkCode(Environment $env): string
    {
        if (null !== $this->appId) {
            $template = $env->load('@LeaptCore/Facebook/sdk_code.html.twig');

            return $template->render([
                'app_id' => $this->appId,
            ]);
        }

        return '<!-- FacebookSdkCode: app_id is null -->';
    }
}
