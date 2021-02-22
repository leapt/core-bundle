<?php

namespace Leapt\CoreBundle\Doctrine\Mapping;

/**
 * @Annotation
 * @NamedArgumentConstructor
 * @Target({"PROPERTY"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class File
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

    public function __construct(string $path = null, string $pathCallback = null, string $mappedBy = null, string $filename = null, string $nameCallback = null)
    {
        $this->path = $path;
        $this->pathCallback = $pathCallback;
        $this->mappedBy = $mappedBy;
        $this->filename = $filename;
        $this->nameCallback = $nameCallback;
    }
}
