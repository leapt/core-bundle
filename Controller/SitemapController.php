<?php

namespace Leapt\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class SitemapController
 * @package Leapt\CoreBundle\Controller
 */
class SitemapController extends Controller
{

    public function defaultAction()
    {
        $sitemapManager = $this->get('leapt_core.sitemap_manager');
        $sitemaps = $sitemapManager->getSitemaps();

        if (count($sitemaps) > 1) {
            return $this->render('LeaptCoreBundle:Sitemap:index.xml.twig', array('sitemaps' => $sitemaps));
        } else if (1 === count($sitemaps)) {
            return $this->forward('LeaptCoreBundle:Sitemap:sitemap', array('sitemap' => current($sitemaps)->getAlias()));
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
        $sitemapManager = $this->get('leapt_core.sitemap_manager');
        $sitemap = $sitemapManager->getSitemap($sitemap);
        $sitemap->build($this->get('router'));

        return $this->render('LeaptCoreBundle:Sitemap:sitemap.xml.twig', array('sitemap' => $sitemap));
    }
}