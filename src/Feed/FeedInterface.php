<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Feed;

interface FeedInterface
{
    /**
     * This method is used to build the ATOM channel "id" element.
     *
     * @abstract
     */
    public function getId(): string;

    /**
     * This method is used to generate the feed "title" element for ATOM and RSS.
     *
     * @abstract
     */
    public function getTitle(): string;

    /**
     * This method is used to generate the RSS "description" element as
     * well as the ATOM "subtitle" element.
     *
     * @abstract
     */
    public function getDescription(): string;

    /**
     * This method is used to build RSS and ATOM "link" elements
     * The generated url should point to the website containing the feed.
     *
     * @abstract
     */
    public function getLink(): string;

    /**
     * This method is used to generated the "updated" atom element.
     *
     * @abstract
     */
    public function getUpdatedAt(): \DateTime;

    /**
     * This method should return an array, a collection (for example a Doctrine collection) or
     * any other traversable structure, in order to build the RSS "items" element or the
     * ATOM "entries" element.
     *
     * @abstract
     */
    public function getItems(): \Traversable|array;

    /**
     * This method should return a valid FeedItem instance.
     *
     * @abstract
     */
    public function buildItem($item): FeedItem;
}
