<?php

namespace Snowcap\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SitemapController extends Controller {


    public function defaultAction()
    {
        $sitemapManager = $this->get('snowcap_core.sitemap_manager');
        $sitemaps = $sitemapManager->getSitemaps();

        if(count($sitemaps) > 1) {
            return $this->render('SnowcapCoreBundle:Sitemap:index.xml.twig', array('sitemaps' => $sitemaps));
        }
        elseif(count($sitemaps === 1)) {
            return $this->forward('SnowcapCoreBundle:Sitemap:sitemap', array('sitemap' => current($sitemaps)->getAlias()));
        }
        else {
            throw new \UnexpectedValueException('No sitemap has been defined');
        }
    }

    /**
     * @param string $sitemap
     */
    public function sitemapAction($sitemap)
    {
        $sitemapManager = $this->get('snowcap_core.sitemap_manager');
        $sitemap = $sitemapManager->getSitemap($sitemap);
        $sitemap->build($this->get('router'));

        return $this->render('SnowcapCoreBundle:Sitemap:sitemap.xml.twig', array('sitemap' => $sitemap));
    }

}