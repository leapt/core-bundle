<?php
namespace Snowcap\CoreBundle\Twig\Extension;

use \Symfony\Component\DependencyInjection\ContainerInterface;

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
     * @param string $accountId
     * @param bool   $debug
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
        return array(
            'faceboook_sdk_code' => new \Twig_Function_Method($this, 'getFacebookSdkCode', array('is_safe' => array('html'))),
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'snowcap_facebook';
    }

    /**
     * @return string
     */
    public function getFacebookSdkCode()
    {
        if (null !== $this->appId) {
            $template = $this->twigEnvironment->loadTemplate('SnowcapCoreBundle:Facebook:sdk_code.html.twig');

            return $template->render(
                array(
                    'app_id' => $this->appId,
                )
            );
        }

        return '<!-- FacebookSdkCode: app_id is null -->';
    }
}