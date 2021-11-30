<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Twig\Extension;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FacebookExtension extends AbstractExtension
{
    public function __construct(private ?string $appId)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('facebook_sdk_code', [$this, 'getFacebookSdkCode'], ['is_safe' => ['html'], 'needs_environment' => true]),
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
