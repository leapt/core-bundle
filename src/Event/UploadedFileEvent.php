<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Event;

use Leapt\CoreBundle\FileStorage\FileUploadConfig;
use Symfony\Contracts\EventDispatcher\Event;

final class UploadedFileEvent extends Event
{
    public function __construct(
        public \SplFileInfo $splFileInfo,
        public FileUploadConfig $fileUploadConfig,
    ) {
    }
}
