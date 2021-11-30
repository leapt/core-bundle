<?php

namespace Leapt\CoreBundle\Feed;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class FeedItem
{
    /**
     * Property used to generate ATOM "id" element.
     */
    #[Assert\NotBlank]
    public string $id;

    /**
     * Property used to generate RSS and ATOM "title" elements.
     */
    #[Assert\NotBlank]
    public string $title;

    /**
     * Property used to generate the ATOM "updated" element.
     */
    #[Assert\NotBlank]
    #[Assert\Type(\DateTime::class)]
    public \DateTime $updatedAt;

    /**
     * Property used to generate the RSS "pubDate" element ATOM "published" element.
     */
    #[Assert\NotBlank]
    #[Assert\Type(\DateTime::class)]
    public \DateTime $createdAt;

    /**
     * Property used to generate the RSS "description" element as well as
     * the ATOM "content" element.
     *
     * This value must be set if the link is not set
     */
    public string $description;

    /**
     * Property used to generate the RSS "link" element as well as the ATOM "link" element
     * (Please note that while the ATOM spec allow you to provide multiple link elements, this
     * library does not).
     *
     * This value must be set if the description is not set
     */
    #[Assert\Url]
    public string $link;

    /**
     * Property used to build RSS and ATOM "author" elements
     * This property should be built as an associative array, e.a :
     * array('name' => 'John Doe', 'email' => 'john@doe.com').
     *
     * @Assert\Collection(
     *     fields = {
     *         "name" = @Assert\NotBlank(),
     *         "email" = {
     *             @Assert\NotBlank(),
     *             @Assert\Email()
     *         }
     *     }
     * )
     */
    #[Assert\NotBlank]
    #[Assert\Type('array')]
    public array $author;

    /**
     * Check that the feed item has at least a link or a description.
     */
    #[Assert\Callback]
    public function hasLinkOrDescription(ExecutionContextInterface $context)
    {
        if (!isset($this->link) || !isset($this->description)) {
            $context->buildViolation('Every feed item should have at least a description or a link')
                ->atPath('link')
                ->addViolation();
        }
    }
}
