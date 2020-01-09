<?php

namespace Leapt\CoreBundle\Sitemap;

use Symfony\Component\Routing\Router;

/**
 * Class AbstractSitemap.
 */
abstract class AbstractSitemap
{
    const CHANGEFREQ_ALWAYS = 'always';
    const CHANGEFREQ_HOURLY = 'hourly';
    const CHANGEFREQ_DAILY = 'daily';
    const CHANGEFREQ_WEEKLY = 'weekly';
    const CHANGEFREQ_MONTHLY = 'monthly';
    const CHANGEFREQ_YEARLY = 'yearly';
    const CHANGEFREQ_NEVER = 'never';

    /**
     * @var array
     */
    private $urls = [];

    /**
     * @var string
     */
    private $alias;

    /**
     * @return array
     */
    public function getUrls()
    {
        return $this->urls;
    }

    /**
     * @param string $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Build the sitemap.
     *
     * The easiest way to implement this method is to use the addUrl method
     *
     * @abstract
     *
     * @return mixed
     */
    abstract public function build(Router $router);

    /**
     * @param $loc
     * @param \DateTime $lastMod
     * @param string    $changeFreq
     * @param mixed     $priority
     * @param array     $images
     *
     * @return AbstractSitemap
     */
    protected function addUrl($loc, \DateTime $lastMod = null, $changeFreq = null, $priority = null, $images = [])
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
     * @param string $changeFreq
     *
     * @throws \InvalidArgumentException
     */
    private function validateChangeFreq($changeFreq)
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
     * @param string $priority
     *
     * @throws \InvalidArgumentException
     */
    private function validatePriority($priority)
    {
        if (!is_numeric($priority) || 1 < $priority || 0 > $priority) {
            throw new \InvalidArgumentException('The priority parameter must be a number between 0 and 1');
        }
    }
}
