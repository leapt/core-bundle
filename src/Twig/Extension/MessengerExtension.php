<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Twig\Extension;

use Leapt\CoreBundle\Messenger\MessengerHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class MessengerExtension extends AbstractExtension
{
    public function __construct(private readonly MessengerHelper $messengerHelper) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_messenger_worker_running', [$this, 'isMessengerWorkerRunning']),
        ];
    }

    public function isMessengerWorkerRunning(string $transport): bool
    {
        return $this->messengerHelper->isWorkerRunning($transport);
    }
}
