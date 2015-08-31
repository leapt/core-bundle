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
     * @var \Twig_Environment
     */
    private $twigEnvironment;

    /**
     * @param string $appId
     */
    public function __construct($appId)
    {
        $this->appId = $appId;
    }

    /**
     * @param \Twig_Environment $environment
     *
     * @codeCoverageIgnore
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->twigEnvironment = $environment;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('facebook_sdk_code', [$this, 'getFacebookSdkCode'], ['is_safe' => ['html']])
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
     * @return string
     */
    public function getFacebookSdkCode()
    {
        if (null !== $this->appId) {
            $template = $this->twigEnvironment->loadTemplate('LeaptCoreBundle:Facebook:sdk_code.html.twig');

            return $template->render(
                array(
                    'app_id' => $this->appId,
                )
            );
        }

        return '<!-- FacebookSdkCode: app_id is null -->';
    }
}