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
            'analytics_tracking_code'     => new \Twig_Function_Method($this, 'getAnalyticsTrackingCode', array('is_safe' => array('html'))),
            'analytics_tracking_commerce' => new \Twig_Function_Method($this, 'getAnalyticsCommerce', array('is_safe' => array('html'))),
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
     * @param string $domainName Available options are "auto" or "none" or a real domain name
     */
    public function setDomainName($domainName)
    {
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

            return $template->render(
                array(
                    'tracking_id'  => $this->accountId,
                    'domain_name'  => $this->domainName,
                    'allow_linker' => $this->allowLinker
                )
            );
        }

        return '<!-- AnalyticsTrackingCode: account id is null or domain name is not set to "none" -->';
    }

    /**
     * Send eCommerce order to Google Analytics
     *
     * @param array|object $order
     * Example :
     * array(
     *  'id' => '1234',           // order ID - required
     *  'name' => 'Acme Clothing',  // affiliation or store name
     *  'total' => '11.99',          // total - required
     *  'tax' => '1.29',           // tax
     *  'shipping' => '5',              // shipping
     *  'city' => 'San Jose',       // city
     *  'state' => 'California',     // state or province
     *  'country' => 'USA'             // country
     *  'items' => array(
     *      array(
     *          'id' => 'DD44',           // SKU/code - required
     *          'name' => 'T-Shirt',        // product name
     *          'category' => 'Green Medium',   // category or variation
     *          'price' => '11.99',          // unit price - required
     *          'quantity' => '1',               // quantity - required
     *      )
     *  )
     *
     * @return string
     */
    public function getAnalyticsCommerce($order)
    {
        if (null !== $this->accountId || $this->domainName === 'none') {
            $template = $this->twigEnvironment->loadTemplate('SnowcapCoreBundle:Google:tracking_commerce.html.twig');

            return $template->render(array('order' => $order));
        }

        return '<!-- AnalyticsTrackingCode: account id is null or domain name is not set to "none" -->';
    }

}