<?php
namespace Snowcap\CoreBundle\Twig\Extension;

use \Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

use Snowcap\CoreBundle\Feed\FeedInterface;

class FeedExtension extends \Twig_Extension implements ContainerAwareInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * Get all available functions
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'item_link' => new \Twig_Function_Method($this, 'getItemLink', array('is_safe' => array('html'))),
        );
    }

    public function getItemLink(FeedInterface $feed, $item)
    {
        return $feed->getItemLink($item, $this->container->get('router'));
    }

    /**
     * Return the name of the extension
     *
     * @return string
     */
    public function getName()
    {
        return 'snowcap_feed';
    }
}