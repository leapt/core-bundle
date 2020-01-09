<?php

namespace Leapt\CoreBundle\Controller;

use Leapt\CoreBundle\Sitemap\SitemapManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

/**
 * Class SitemapController.
 */
class SitemapController
{
    /**
     * @var SitemapManager
     */
    private $sitemapManager;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var HttpKernelInterface
     */
    private $httpKernel;

    public function __construct(SitemapManager $sitemapManager, RouterInterface $router, Environment $twig, HttpKernelInterface $httpKernel)
    {
        $this->sitemapManager = $sitemapManager;
        $this->router = $router;
        $this->twig = $twig;
        $this->httpKernel = $httpKernel;
    }

    public function defaultAction(Request $request)
    {
        $sitemaps = $this->sitemapManager->getSitemaps();

        if (1 < \count($sitemaps)) {
            return new Response($this->twig->render('@LeaptCore/Sitemap/index.xml.twig', ['sitemaps' => $sitemaps]));
        } elseif (1 === \count($sitemaps)) {
            $subRequest = $request->duplicate([], null, [
                '_controller' => 'Leapt\CoreBundle\Controller\SitemapController::sitemapAction',
                'sitemap'     => current($sitemaps)->getAlias(),
            ]);

            return $this->httpKernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
        }
        throw new \UnexpectedValueException('No sitemap has been defined');
    }

    /**
     * @param string $sitemap
     */
    public function sitemapAction($sitemap)
    {
        $sitemap = $this->sitemapManager->getSitemap($sitemap);
        $sitemap->build($this->router);

        return new Response($this->twig->render('@LeaptCore/Sitemap/sitemap.xml.twig', ['sitemap' => $sitemap]));
    }
}
