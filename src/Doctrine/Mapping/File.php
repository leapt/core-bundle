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
    public ?string $path = null;

    /** @var string */
    public ?string $pathCallback = null;

    /** @var string */
    public ?string $mappedBy = null;

    /** @var string */
    public ?string $filename = null;

    /** @var string */
    public ?string $nameCallback = null;

    public function __construct(string $path = null, string $pathCallback = null, string $mappedBy = null, string $filename = null, string $nameCallback = null)
    {
        $this->path = $path;
        $this->pathCallback = $pathCallback;
        $this->mappedBy = $mappedBy;
        $this->filename = $filename;
        $this->nameCallback = $nameCallback;
    }
}
