<?php
namespace Snowcap\CoreBundle\Doctrine\Mapping;
use Doctrine\ORM\Mapping\Annotation;

/**
 * @Annotation
 */
class File implements Annotation {
    /** @var string */
    public $path;
    /** @var string */
    public $mappedBy;
}
