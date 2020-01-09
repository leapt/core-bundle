<?php

namespace Leapt\CoreBundle\Sitemap;

use Symfony\Component\Routing\Router;

/**
 * Class AbstractSitemap
 * @package Leapt\CoreBundle\Sitemap
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
    private $urls = array();

    /**
     * @var string
     */
    private $alias;

    /**
     * @param $loc
     * @param \DateTime $lastMod
     * @param string $changeFreq
     * @param mixed $priority
     * @param array $images
     * @return AbstractSitemap
     */
    protected function addUrl($loc, \DateTime $lastMod = null, $changeFreq = null, $priority = null, $images = array())
    {
        $url = array(
            'loc' => $loc
        );
        if ($lastMod !== null) {
            $url['lastmod'] = $lastMod;
        }
        if ($changeFreq !== null) {
            $this->validateChangeFreq($changeFreq);
            $url['changefreq'] = $changeFreq;
        }
        if ($priority !== null) {
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
     * @throws \InvalidArgumentException
     */
    private function validateChangeFreq($changeFreq)
    {
        if (!in_array($changeFreq, array(
            self::CHANGEFREQ_ALWAYS,
            self::CHANGEFREQ_HOURLY,
            self::CHANGEFREQ_DAILY,
            self::CHANGEFREQ_WEEKLY,
            self::CHANGEFREQ_MONTHLY,
            self::CHANGEFREQ_YEARLY,
            self::CHANGEFREQ_NEVER
        ))
        ) {
            throw new \InvalidArgumentException('The provided changefreq is invalid');
        }
    }

    /**
     * @param string $priority
     * @throws \InvalidArgumentException
     */
    private function validatePriority($priority)
    {
        if (!is_numeric($priority) || $priority > 1 || $priority < 0) {
            throw new \InvalidArgumentException('The priority parameter must be a number between 0 and 1');
        }
    }

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
     * Build the sitemap
     *
     * The easiest way to implement this method is to use the addUrl method
     *
     * @abstract
     * @return mixed
     */
    abstract public function build(Router $router);

}