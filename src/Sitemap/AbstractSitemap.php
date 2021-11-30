<?php

namespace Leapt\CoreBundle\Sitemap;

use Symfony\Component\Routing\Router;

abstract class AbstractSitemap
{
    public const CHANGEFREQ_ALWAYS = 'always';
    public const CHANGEFREQ_HOURLY = 'hourly';
    public const CHANGEFREQ_DAILY = 'daily';
    public const CHANGEFREQ_WEEKLY = 'weekly';
    public const CHANGEFREQ_MONTHLY = 'monthly';
    public const CHANGEFREQ_YEARLY = 'yearly';
    public const CHANGEFREQ_NEVER = 'never';

    private array $urls = [];

    private string $alias;

    public function getUrls(): array
    {
        return $this->urls;
    }

    public function setAlias(string $alias)
    {
        $this->alias = $alias;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * Build the sitemap.
     *
     * The easiest way to implement this method is to use the addUrl method
     */
    abstract public function build(Router $router): mixed;

    /**
     * @param $loc
     * @param \DateTime $lastMod
     *
     * @return AbstractSitemap
     */
    protected function addUrl($loc, \DateTime $lastMod = null, string $changeFreq = null, mixed $priority = null, array $images = []): self
    {
        $url = [
            'loc' => $loc,
        ];
        if (null !== $lastMod) {
            $url['lastmod'] = $lastMod;
        }
        if (null !== $changeFreq) {
            $this->validateChangeFreq($changeFreq);
            $url['changefreq'] = $changeFreq;
        }
        if (null !== $priority) {
            $this->validatePriority($priority);
            $url['priority'] = $priority;
        }
        foreach ($images as $image) {
            $url['images'][] = $image;
        }

        $this->urls[] = $url;

        return $this;
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function validateChangeFreq(string $changeFreq): void
    {
        if (!\in_array($changeFreq, [
            self::CHANGEFREQ_ALWAYS,
            self::CHANGEFREQ_HOURLY,
            self::CHANGEFREQ_DAILY,
            self::CHANGEFREQ_WEEKLY,
            self::CHANGEFREQ_MONTHLY,
            self::CHANGEFREQ_YEARLY,
            self::CHANGEFREQ_NEVER,
        ], true)
        ) {
            throw new \InvalidArgumentException('The provided changefreq is invalid');
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function validatePriority(string $priority): void
    {
        if (!is_numeric($priority) || 1 < $priority || 0 > $priority) {
            throw new \InvalidArgumentException('The priority parameter must be a number between 0 and 1');
        }
    }
}
