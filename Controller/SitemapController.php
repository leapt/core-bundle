<?php

namespace Leapt\CoreBundle\Controller;

use Leapt\CoreBundle\Sitemap\SitemapManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class SitemapController
 * @package Leapt\CoreBundle\Controller
 */
class SitemapController extends AbstractController
{
    /**
     * @var SitemapManager
     */
    private $sitemapManager;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(SitemapManager $sitemapManager, RouterInterface $router)
    {
        $this->sitemapManager = $sitemapManager;
        $this->router = $router;
    }

    public function defaultAction()
    {
        $sitemaps = $this->sitemapManager->getSitemaps();

        if (1 < \count($sitemaps)) {
            return $this->render('@LeaptCore/Sitemap/index.xml.twig', ['sitemaps' => $sitemaps]);
        } elseif (1 === \count($sitemaps)) {
            return $this->forward('LeaptCoreBundle:Sitemap:sitemap', ['sitemap' => current($sitemaps)->getAlias()]);
        } else {
            throw new \UnexpectedValueException('No sitemap has been defined');
        }
    }

    /**
     * @param string $sitemap
     */
    public function sitemapAction($sitemap)
    {
        $sitemap = $this->sitemapManager->getSitemap($sitemap);
        $sitemap->build($this->router);

        return $this->render('@LeaptCore/Sitemap/sitemap.xml.twig', ['sitemap' => $sitemap]);
    }
}
