<?php

namespace Snowcap\CoreBundle\Feed;

interface FeedInterface {
    /**
     * This method is used to build the ATOM channel "id" element
     *
     * @abstract
     * @return string
     */
    public function getId();

    /**
     * This method is used to generate the feed "title" element for ATOM and RSS
     *
     * @abstract
     * @return string
     */
    public function getTitle();

    /**
     * This method is used to generate the RSS "description" element as
     * well as the ATOM "subtitle" element
     *
     * @abstract
     * @return string
     */
    public function getDescription();

    /**
     * This method is used to build RSS and ATOM "link" elements
     * The generated url should point to the website containing the feed
     *
     * @abstract
     * @return string
     */
    public function getLink();

    /**
     * This method is used to generated the "updated" atom element
     *
     * @abstract
     * @return \DateTime
     */
    public function getUpdatedAt();

    /**
     * This method should return an array, a collection (for example a Doctrine collection) or
     * any other traversable structure, in order to build the RSS "items" element or the
     * ATOM "entries" element
     *
     * @abstract
     * @return array|\Traversable
     */
    public function getItems();

    /**
     * This method should return a valid FeedItem instance
     *
     * @abstract
     * @return FeedItem
     */
    public function buildItem($item);
}