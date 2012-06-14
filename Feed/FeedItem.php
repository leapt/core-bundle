<?php

namespace Snowcap\CoreBundle\Feed;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;

/**
 * @Assert\Callback(methods={"hasLinkOrDescription"})
 */
class FeedItem
{

    /**
     * Property used to generate ATOM "id" element
     *
     * @Assert\NotBlank()
     *
     * @var string
     */
    public $id;

    /**
     * Property used to generate RSS and ATOM "title" elements
     *
     * @Assert\NotBlank()
     *
     * @var string
     */
    public $title;

    /**
     * Property used to generate the ATOM "updated" element
     *
     * @Assert\NotBlank()
     * @Assert\DateTime
     *
     * @var \DateTime
     */
    public $updatedAt;

    /**
     * Property used to generate the RSS "pubDate" element ATOM "published" element
     *
     * @Assert\NotBlank()
     * @Assert\DateTime
     *
     * @var \DateTime
     */
    public $createdAt;

    /**
     * Property used to generate the RSS "description" element as well as
     * the ATOM "content" element
     *
     * This value must be set if the link is not set
     *
     * @var string
     */
    public $description;

    /**
     * Property used to generate the RSS "link" element as well as the ATOM "link" element
     * (Please note that while the ATOM spec allow you to provide multiple link elements, this
     * library does not)
     *
     * This value must be set if the description is not set
     *
     * @Assert\Url()
     *
     * @var string
     */
    public $link;

    /**
     * Check that the feed item has at least a link or a description
     *
     * @param ExecutionContext $context
     */
    public function hasLinkOrDescription(ExecutionContext $context)
    {
        if (!isset($this->link) || !isset($this->description)) {
            $propertyPath = $context->getPropertyPath() . '.link';
            $context->setPropertyPath($propertyPath);
            $context->addViolation('Every feed item should have at least a description or a link', array(), null);
        }
    }
}