<?php

namespace Snowcap\CoreBundle\Sitemap;

class SitemapManager {

    /**
     * @var AbstractSitemap
     */
    private $sitemap;

    /**
     * @param AbstractSitemap $sitemap
     * @throws \BadMethodCallException
     */
    public function registerSitemap(AbstractSitemap $sitemap) {
        if(isset($this->sitemap)) {
            throw new \BadMethodCallException('You can only register one sitemap at a time');
        }
        $this->sitemap = $sitemap;
    }

    /**
     * @return AbstractSitemap
     * @throws \UnexpectedValueException
     */
    public function getSitemap() {
        if(!isset($this->sitemap)) {
            throw new \UnexpectedValueException('No sitemap has been registered');
        }
        return $this->sitemap;
    }
}