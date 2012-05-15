<?php
namespace Snowcap\CoreBundle\Twig\Extension;

use \Symfony\Component\DependencyInjection\ContainerInterface;

class GoogleExtension extends \Twig_Extension
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * @var \Twig_Environment
     */
    private $twigEnvironment;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->twigEnvironment = $environment;
    }

    /**
     * Get all available functions
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'analytics_tracking_code' => new \Twig_Function_Method($this, 'getAnalyticsTrackingCode', array('is_safe' => array('html'))),
        );
    }

    public function getAnalyticsTrackingCode()
    {
        if($this->container->hasParameter('google_tracking_id')) {
            $trackingId = $this->container->getParameter('google_tracking_id');
            $template = $this->twigEnvironment->loadTemplate('SnowcapCoreBundle:Google:tracking_code.html.twig');
            return $template->render(array('tracking_id' => $trackingId));
        }
    }

    /**
     * Return the name of the extension
     *
     * @return string
     */
    public function getName()
    {
        return 'snowcap_google';
    }
}