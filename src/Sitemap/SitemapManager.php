<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Sitemap;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SitemapManager
{
    /**
     * @var AbstractSitemap[]
     */
    private array $sitemaps = [];

    public function registerSitemap(string $alias, AbstractSitemap $sitemap): void
    {
        $sitemap->setAlias($alias);
        $this->sitemaps[$alias] = $sitemap;
    }

    /**
     * @return AbstractSitemap[]
     */
    public function getSitemaps(): array
    {
        return $this->sitemaps;
    }

    public function getSitemap(string $alias): AbstractSitemap
    {
        if (!isset($this->sitemaps[$alias])) {
            throw new NotFoundHttpException(sprintf('There is no sitemap with alias "%s"', $alias));
        }

        return $this->sitemaps[$alias];
    }
}
