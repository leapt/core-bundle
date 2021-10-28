<?php

namespace Leapt\CoreBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigTest;

/**
 * Class SiteExtension.
 */
class SiteExtension extends AbstractExtension
{
    private array $titleParts = ['prepend' => [], 'append' => []];

    private string $metaDescription;

    private array $metaKeywords = [];

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
     * @return string
     */
    public function getPageTitle(string $baseTitle, string $seperator = ' - ')
    {
        $parts = array_merge(
            $this->titleParts['prepend'],
            [$baseTitle],
            $this->titleParts['append']
        );

        return implode($seperator, $parts);
    }

    /**
     * @return string
     */
    public function getMetaDescription(string $defaultDescription)
    {
        return $this->metaDescription ?: $defaultDescription;
    }

    public function setMetaDescription(string $description)
    {
        $this->metaDescription = $description;
    }

    /**
     * @return string
     */
    public function getMetaKeywords(array $defaultKeywords)
    {
        $merged = array_merge($defaultKeywords, $this->metaKeywords);
        $exploded = [];
        foreach ($merged as $item) {
            $exploded = array_merge($exploded, explode(',', $item));
        }
        $trimmed = array_map('trim', $exploded);

        return implode(',', array_unique($trimmed));
    }

    public function addMetaKeywords(array $keywords)
    {
        $this->metaKeywords = array_merge($this->metaKeywords, $keywords);
    }

    public function prependPageTitle(string $prepend)
    {
        array_unshift($this->titleParts['prepend'], $prepend);
    }

    public function appendPageTitle(string $append)
    {
        $this->titleParts['append'][] = $append;
    }
}
