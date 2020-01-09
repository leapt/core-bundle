<?php

namespace Leapt\CoreBundle\Twig\Extension;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class FacebookExtension.
 */
class FacebookExtension extends AbstractExtension
{
    /**
     * @var string
     */
    private $appId;

    public function __construct($appId)
    {
        $this->appId = $appId;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('facebook_sdk_code', [$this, 'getFacebookSdkCode'], ['is_safe' => ['html'], 'needs_environment' => true]),
        ];
    }

    /**
     * @return string
     */
    public function getFacebookSdkCode(Environment $env)
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
