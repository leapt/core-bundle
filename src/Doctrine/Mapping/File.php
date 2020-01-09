<?php

namespace Leapt\CoreBundle\Doctrine\Mapping;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target({"METHOD","PROPERTY"})
 */
class File extends Annotation
{
    /** @var string */
    public $path;
    /** @var string */
    public $pathCallback;
    /** @var string */
    public $mappedBy;
    /** @var string */
    public $filename;
    /** @var string */
    public $nameCallback;
}
