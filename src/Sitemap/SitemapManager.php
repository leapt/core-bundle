<?php

namespace Leapt\CoreBundle\Sitemap;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SitemapManager
{
    /**
     * @var AbstractSitemap[]
     */
    private array $sitemaps = [];

    /**
     * @throws \BadMethodCallException
     */
    public function registerSitemap($alias, AbstractSitemap $sitemap)
    {
        $sitemap->setAlias($alias);
        $this->sitemaps[$alias] = $sitemap;
    }

    /**
     * @return AbstractSitemap
     *
     * @throws \UnexpectedValueException
     */
    public function getSitemaps()
    {
        return $this->sitemaps;
    }

    /**
     * @return AbstractSitemap
     *
     * @throws NotFoundHttpException
     */
    public function getSitemap(string $alias)
    {
        if (!isset($this->sitemaps[$alias])) {
            throw new NotFoundHttpException(sprintf('There is no sitemap with alias "%s"', $alias));
        }

        return $this->sitemaps[$alias];
    }
}
