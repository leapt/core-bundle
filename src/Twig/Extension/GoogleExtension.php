<?php

namespace Leapt\CoreBundle\Twig\Extension;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GoogleExtension extends AbstractExtension
{
    public const INVALID_DOMAIN_NAME_EXCEPTION = 10;

    private string $domainName;

    private string $allowLinker;

    private string $tagsManagerId;

    public function __construct(private ?string $accountId = null, private bool $debug = false)
    {
    }

    /**
     * Get all available functions.
     *
     * @return array
     *
     * @codeCoverageIgnore
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('analytics_tracking_code', [$this, 'getAnalyticsTrackingCode'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new TwigFunction('analytics_tracking_commerce', [$this, 'getAnalyticsCommerce'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new TwigFunction('tags_manager_code', [$this, 'getTagsManagerCode'], ['is_safe' => ['html'], 'needs_environment' => true]),
        ];
    }

    /**
     * @param string $domainName Available options are "auto" or "none" or a real domain name
     */
    public function setDomainName(string $domainName)
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

    public function setAllowLinker(string $allowLinker)
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

    public function setTagsManagerId(string $tagsManagerId)
    {
        $this->tagsManagerId = $tagsManagerId;
    }

    /**
     * @return string
     */
    public function getAnalyticsTrackingCode(Environment $env)
    {
        if (null !== $this->accountId || 'none' === $this->domainName) {
            $template = $env->load('@LeaptCore/Google/tracking_code.html.twig');

            return $template->render([
                'tracking_id'  => $this->accountId,
                'domain_name'  => $this->domainName,
                'allow_linker' => $this->allowLinker,
                'debug'        => $this->debug,
            ]);
        }

        return '<!-- AnalyticsTrackingCode: account id is null or domain name is not set to "none" -->';
    }

    /**
     * Send eCommerce order to Google Analytics.
     *
     * @param object|array $order
     *                            Example :
     *                            array(
     *                            'id' => '1234',           // order ID - required
     *                            'name' => 'Acme Clothing',  // affiliation or store name
     *                            'total' => '1199',          // total in cents - required
     *                            'tax' => '129',           // tax in cents
     *                            'shipping' => '5',              // shipping in cents
     *                            'city' => 'San Jose',       // city
     *                            'state' => 'California',     // state or province
     *                            'country' => 'USA'             // country
     *                            'items' => array(
     *                            array(
     *                            'id' => 'DD44',           // SKU/code - required
     *                            'name' => 'T-Shirt',        // product name
     *                            'category' => 'Green Medium',   // category or variation
     *                            'price' => '1199',          // unit price in cents - required
     *                            'quantity' => '1',               // quantity - required
     *                            )
     *                            )
     *
     * @return string
     */
    public function getAnalyticsCommerce(Environment $env, object|array $order)
    {
        if (null !== $this->accountId || 'none' === $this->domainName) {
            $template = $env->load('@LeaptCore/Google/tracking_commerce.html.twig');

            return $template->render(['order' => $order]);
        }

        return '<!-- AnalyticsTrackingCode: account id is null -->';
    }

    /**
     * @return string
     */
    public function getTagsManagerCode(Environment $env)
    {
        if (null !== $this->tagsManagerId) {
            $template = $env->load('@LeaptCore/Google/tags_manager_code.html.twig');

            return $template->render(['tags_manager_id' => $this->tagsManagerId]);
        }

        return '<!-- TagsManagerCode: tags manager id is null -->';
    }
}
