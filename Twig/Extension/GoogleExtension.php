<?php
namespace Snowcap\CoreBundle\Twig\Extension;

use \Symfony\Component\DependencyInjection\ContainerInterface;

class GoogleExtension extends \Twig_Extension
{

    const INVALID_DOMAIN_NAME_EXCEPTION = 10;

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
     * Get all available functions
     *
     * @return array
     *
     * @codeCoverageIgnore
     */
    public function getFunctions()
    {
        return array(
            'analytics_tracking_code' => new \Twig_Function_Method($this, 'getAnalyticsTrackingCode', array('is_safe' => array('html'))),
        );
    }

    /**
     * Return the name of the extension
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getName()
    {
        return 'snowcap_google';
    }

    /**
     * @param string $domainName Available options are "auto" or "none"
     */
    public function setDomainName($domainName)
    {
        if (!in_array($domainName, array('auto', 'none'))) {
            throw new \InvalidArgumentException('Expect "auto" or "none"', self::INVALID_DOMAIN_NAME_EXCEPTION);
        }
        $this->domainName = $domainName;
    }

    /**
     * @return string
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * @return string
     */
    public function getDomainName()
    {
        return $this->domainName;
    }

    /**
     * @param string $allowLinker
     */
    public function setAllowLinker($allowLinker)
    {
        $this->allowLinker = $allowLinker;
    }

    /**
     * @return string
     */
    public function getAllowLinker()
    {
        return $this->allowLinker;
    }

    /**
     * @return string
     */
    public function getAnalyticsTrackingCode()
    {
        if (null !== $this->accountId || $this->domainName === 'none') {
            $template = $this->twigEnvironment->loadTemplate('SnowcapCoreBundle:Google:tracking_code.html.twig');
            return $template->render(array(
                'tracking_id' => $this->accountId,
                'domain_name' => $this->domainName,
                'allow_linker' => $this->allowLinker
            ));
        }
        return '<!-- AnalyticsTrackingCode: account id is null or domain name is not set to "none" -->';
    }

}