<?php
namespace Snowcap\CoreBundle\Twig\Extension;

use \Symfony\Component\DependencyInjection\ContainerInterface;

class GoogleExtension extends \Twig_Extension
{

    /**
     * @var \Twig_Environment
     */
    private $twigEnvironment;

    /**
     * @var string
     */
    private $accountId;

    /**
     * @var string
     */
    private $domainName;

    /**
     * @var string
     */
    private $allowLinker;

    /**
     * @param string $accountId
     */
    public function __construct($accountId)
    {
        $this->accountId = $accountId;
    }

    public function setDomainName($domainName)
    {
        $this->domainName = $domainName;
    }

    public function setAllowLinker($allowLinker)
    {
        $this->allowLinker = $allowLinker;
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
        if(null !== $this->accountId || $this->domainName === 'none') {
            $template = $this->twigEnvironment->loadTemplate('SnowcapCoreBundle:Google:tracking_code.html.twig');
            return $template->render(array(
                'tracking_id' => $this->accountId,
                'domain_name' => $this->domainName,
                'allow_linker' => $this->allowLinker
            ));
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