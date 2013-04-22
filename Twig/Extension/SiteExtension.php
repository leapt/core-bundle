<?php

namespace Snowcap\CoreBundle\Twig\Extension;

use \Symfony\Component\DependencyInjection\ContainerInterface;

class SiteExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    private $titleParts = array('prepend' => array(), 'append' => array());

    /**
     * @var string
     */
    private $metaDescription;

    /**
     * @var array
     */
    private $metaKeywords = array();

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'prepend_page_title' => new \Twig_Function_Method($this, 'prependPageTitle'),
            'append_page_title' => new \Twig_Function_Method($this, 'appendPageTitle'),
            'page_title' => new \Twig_Function_Method($this, 'getPageTitle'),
            'meta_description' => new \Twig_Function_Method($this, 'getMetaDescription'),
            'set_meta_description' => new \Twig_Function_Method($this, 'setMetaDescription'),
            'meta_keywords' => new \Twig_Function_Method($this, 'getMetaKeywords'),
            'add_meta_keywords' => new \Twig_Function_Method($this, 'addMetaKeywords'),
        );
    }

    /**
     * @param string $baseTitle
     * @param string $seperator
     * @return string
     */
    public function getPageTitle($baseTitle, $seperator = ' - ')
    {
        $parts = array_merge(
            $this->titleParts['prepend'],
            array($baseTitle),
            $this->titleParts['append']
        );

        return implode($seperator, $parts);
    }

    /**
     * @return string
     */
    public function getMetaDescription($defaultDescription)
    {
        return $this->metaDescription ?: $defaultDescription;
    }

    /**
     * @param string $description
     */
    public function setMetaDescription($description)
    {
        $this->metaDescription = $description;
    }

    /**
     * @param array $defaultKeywords
     * @return string
     */
    public function getMetaKeywords(array $defaultKeywords)
    {
        $merged = array_merge($defaultKeywords, $this->metaKeywords);
        $exploded = array();
        foreach($merged as $item) {
            $exploded = array_merge($exploded, explode(',', $item));
        }
        $trimmed = array_map('trim', $exploded);

        return implode(',', array_unique($trimmed));
    }

    /**
     * @param array $keywords
     */
    public function addMetaKeywords(array $keywords)
    {
        $this->metaKeywords = array_merge($this->metaKeywords, $keywords);
    }

    /**
     * @param string $prepend
     */
    public function prependPageTitle($prepend)
    {
        array_unshift($this->titleParts['prepend'], $prepend);
    }

    /**
     * @param string $prepend
     */
    public function appendPageTitle($append)
    {
        array_push($this->titleParts['append'], $append);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'snowcap_core_site';
    }
}