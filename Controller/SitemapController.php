<?php

namespace Snowcap\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SitemapController extends Controller {

    /**
     * @Template
     */
    public function indexAction()
    {
        $sitemapManager = $this->get('snowcap_core.sitemap_manager');
        $sitemap = $sitemapManager->getSitemap();
        $sitemap->build();

        return array('sitemap' => $sitemap);
    }
}