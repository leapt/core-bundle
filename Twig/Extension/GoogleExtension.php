<?php

namespace Leapt\CoreBundle\Twig\Extension;

/**
 * Class GoogleExtension
 * @package Leapt\CoreBundle\Twig\Extension
 */
class GoogleExtension extends \Twig_Extension
{
    const INVALID_DOMAIN_NAME_EXCEPTION = 10;

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
     * @var bool
     */
    private $debug;

    /**
     * @var string
     */
    private $tagsManagerId;

    /**
     * @param string $accountId
     * @param bool   $debug
     */
    public function __construct($accountId, $debug = false)
    {
        $this->accountId = $accountId;
        $this->debug = $debug;
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
        return [
            new \Twig_SimpleFunction('analytics_tracking_code', [$this, 'getAnalyticsTrackingCode'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new \Twig_SimpleFunction('analytics_tracking_commerce', [$this, 'getAnalyticsCommerce'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new \Twig_SimpleFunction('tags_manager_code', [$this, 'getTagsManagerCode'], ['is_safe' => ['html'], 'needs_environment' => true]),
        ];
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
        return 'leapt_google';
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
     * @param string $tagsManagerId
     */
    public function setTagsManagerId($tagsManagerId)
    {
        $this->tagsManagerId = $tagsManagerId;
    }

    /**
     * @param \Twig_Environment $env
     * @return string
     */
    public function getAnalyticsTrackingCode(\Twig_Environment $env)
    {
        if (null !== $this->accountId || 'none' === $this->domainName) {
            $template = $env->loadTemplate('LeaptCoreBundle:Google:tracking_code.html.twig');

            return $template->render([
                'tracking_id'  => $this->accountId,
                'domain_name'  => $this->domainName,
                'allow_linker' => $this->allowLinker,
                'debug' => $this->debug,
            ]);
        }

        return '<!-- AnalyticsTrackingCode: account id is null or domain name is not set to "none" -->';
    }

    /**
     * Send eCommerce order to Google Analytics
     *
     * @param \Twig_Environment $env
     * @param array|object $order
     * Example :
     * array(
     *  'id' => '1234',           // order ID - required
     *  'name' => 'Acme Clothing',  // affiliation or store name
     *  'total' => '1199',          // total in cents - required
     *  'tax' => '129',           // tax in cents
     *  'shipping' => '5',              // shipping in cents
     *  'city' => 'San Jose',       // city
     *  'state' => 'California',     // state or province
     *  'country' => 'USA'             // country
     *  'items' => array(
     *      array(
     *          'id' => 'DD44',           // SKU/code - required
     *          'name' => 'T-Shirt',        // product name
     *          'category' => 'Green Medium',   // category or variation
     *          'price' => '1199',          // unit price in cents - required
     *          'quantity' => '1',               // quantity - required
     *      )
     *  )
     * @return string
     */
    public function getAnalyticsCommerce(\Twig_Environment $env, $order)
    {
        if (null !== $this->accountId || $this->domainName === 'none') {
            $template = $env->loadTemplate('LeaptCoreBundle:Google:tracking_commerce.html.twig');

            return $template->render(['order' => $order]);
        }

        return '<!-- AnalyticsTrackingCode: account id is null -->';
    }

    /**
     * @param \Twig_Environment $env
     * @return string
     */
    public function getTagsManagerCode(\Twig_Environment $env)
    {
        if (null !== $this->tagsManagerId) {
            $template = $env->loadTemplate('LeaptCoreBundle:Google:tags_manager_code.html.twig');

            return $template->render(['tags_manager_id' => $this->tagsManagerId]);
        }

        return '<!-- TagsManagerCode: tags manager id is null -->';
    }
}