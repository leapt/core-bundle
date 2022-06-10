<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Twig\Extension;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class QrCodeExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_qr_code_from_string', [$this, 'getQrCodeFromString']),
        ];
    }

    public function getQrCodeFromString(string $qrCodeContent, int $size = 200, int $margin = 0): string
    {
        if (!class_exists(Builder::class)) {
            throw new \Exception('The "endroid/qr-code" package is required to use the "get_qr_code_from_string" Twig function. Try running "composer require endroid/qr-code".');
        }

        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($qrCodeContent)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size($size)
            ->margin($margin)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->build();

        return $result->getDataUri();
    }
}
