<?php

namespace Leapt\CoreBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigTest;

/**
 * Class SiteExtension
 * @package Leapt\CoreBundle\Twig\Extension
 */
class SiteExtension extends AbstractExtension
{
    /**
     * @var array
     */
    private $titleParts = ['prepend' => [], 'append' => []];

    /**
     * @var string
     */
    private $metaDescription;

    /**
     * @var array
     */
    private $metaKeywords = [];

    /**
     * @return array
     */
    public function getTests()
    {
        return [
            new TwigTest('false', function ($var) {
                return false === $var;
            }),
        ];
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('prepend_page_title', [$this, 'prependPageTitle']),
            new TwigFunction('append_page_title', [$this, 'appendPageTitle']),
            new TwigFunction('page_title', [$this, 'getPageTitle']),
            new TwigFunction('meta_description', [$this, 'getMetaDescription']),
            new TwigFunction('set_meta_description', [$this, 'setMetaDescription']),
            new TwigFunction('meta_keywords', [$this, 'getMetaKeywords']),
            new TwigFunction('add_meta_keywords', [$this, 'addMetaKeywords']),
        ];
    }

    /**
     * @param string $baseTitle
     * @param string $seperator
     * @return string
     */
    public function getPageTitle($baseTitle, $seperator = ' - ')
    {
        $parts = array_merge(
            $this->titleParts['prepend'],
            [$baseTitle],
            $this->titleParts['append']
        );

        return implode($seperator, $parts);
    }

    /**
     * @param string $defaultDescription
     * @return string
     */
    public function getMetaDescription($defaultDescription)
    {
        return $this->metaDescription ?: $defaultDescription;
    }

    /**
     * @param string $description
     */
    public function setMetaDescription($description)
    {
        $this->metaDescription = $description;
    }

    /**
     * @param array $defaultKeywords
     * @return string
     */
    public function getMetaKeywords(array $defaultKeywords)
    {
        $merged = array_merge($defaultKeywords, $this->metaKeywords);
        $exploded = [];
        foreach($merged as $item) {
            $exploded = array_merge($exploded, explode(',', $item));
        }
        $trimmed = array_map('trim', $exploded);

        return implode(',', array_unique($trimmed));
    }

    /**
     * @param array $keywords
     */
    public function addMetaKeywords(array $keywords)
    {
        $this->metaKeywords = array_merge($this->metaKeywords, $keywords);
    }

    /**
     * @param string $prepend
     */
    public function prependPageTitle($prepend)
    {
        array_unshift($this->titleParts['prepend'], $prepend);
    }

    /**
     * @param string $append
     */
    public function appendPageTitle($append)
    {
        $this->titleParts['append'][] = $append;
    }
}