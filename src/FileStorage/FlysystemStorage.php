<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\FileStorage;

use League\Flysystem\FilesystemOperator;

final class FlysystemStorage
{
    /**
     * @param array<FilesystemOperator> $storages
     */
    public function __construct(private array $storages)
    {
    }

    public function getStorage(string $name): FilesystemOperator
    {
        return $this->storages[$name];
    }
}
