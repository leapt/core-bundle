<?php

namespace Leapt\CoreBundle\Twig\Extension;

/**
 * Class FacebookExtension
 * @package Leapt\CoreBundle\Twig\Extension
 */
class FacebookExtension extends \Twig_Extension
{
    /**
     * @var string
     */
    private $appId;

    /**
     * @param string $appId
     */
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
            new \Twig_SimpleFunction('facebook_sdk_code', [$this, 'getFacebookSdkCode'], ['is_safe' => ['html'], 'needs_environment' => true])
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'leapt_facebook';
    }

    /**
     * @param \Twig_Environment $env
     * @return string
     */
    public function getFacebookSdkCode(\Twig_Environment $env)
    {
        if (null !== $this->appId) {
            $template = $env->loadTemplate('LeaptCoreBundle:Facebook:sdk_code.html.twig');

            return $template->render([
                'app_id' => $this->appId,
            ]);
        }

        return '<!-- FacebookSdkCode: app_id is null -->';
    }
}