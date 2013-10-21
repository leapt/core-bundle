<?php

namespace Snowcap\CoreBundle\Sitemap;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SitemapManager {

    /**
     * @var AbstractSitemap
     */
    private $sitemaps = array();

    /**
     * @param AbstractSitemap $sitemap
     * @throws \BadMethodCallException
     */
    public function registerSitemap($alias, AbstractSitemap $sitemap) {
        $sitemap->setAlias($alias);
        $this->sitemaps[$alias] = $sitemap;
    }

    /**
     * @return AbstractSitemap
     * @throws \UnexpectedValueException
     */
    public function getSitemaps() {
        return $this->sitemaps;
    }

    /**
     * @param string $alias
     * @return AbstractSitemap
     * @throws NotFoundHttpException
     */
    public function getSitemap($alias) {
        if(!isset($this->sitemaps[$alias])) {
            throw new NotFoundHttpException(sprintf('There is no sitemap with alias "%s"', $alias));
        }

        return $this->sitemaps[$alias];
    }
}
