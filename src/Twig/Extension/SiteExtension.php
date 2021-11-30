<?php

namespace Leapt\CoreBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigTest;

class SiteExtension extends AbstractExtension
{
    private array $titleParts = ['prepend' => [], 'append' => []];

    private string $metaDescription;

    private array $metaKeywords = [];

    public function getTests(): array
    {
        return [
            new TwigTest('false', function ($var) {
                return false === $var;
            }),
        ];
    }

    public function getFunctions(): array
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

    public function getPageTitle(string $baseTitle, string $seperator = ' - '): string
    {
        $parts = array_merge(
            $this->titleParts['prepend'],
            [$baseTitle],
            $this->titleParts['append'],
        );

        return implode($seperator, $parts);
    }

    public function getMetaDescription(string $defaultDescription): string
    {
        return $this->metaDescription ?: $defaultDescription;
    }

    public function setMetaDescription(string $description): self
    {
        $this->metaDescription = $description;

        return $this;
    }

    public function getMetaKeywords(array $defaultKeywords): string
    {
        $merged = array_merge($defaultKeywords, $this->metaKeywords);
        $exploded = [];
        foreach ($merged as $item) {
            $exploded = array_merge($exploded, explode(',', $item));
        }
        $trimmed = array_map('trim', $exploded);

        return implode(',', array_unique($trimmed));
    }

    public function addMetaKeywords(array $keywords): self
    {
        $this->metaKeywords = array_merge($this->metaKeywords, $keywords);

        return $this;
    }

    public function prependPageTitle(string $prepend): self
    {
        array_unshift($this->titleParts['prepend'], $prepend);

        return $this;
    }

    public function appendPageTitle(string $append): self
    {
        $this->titleParts['append'][] = $append;

        return $this;
    }
}
