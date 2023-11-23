<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Twig\Extension;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode;
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

        if (class_exists(ErrorCorrectionLevel::class)) {
            // endroid/qr-code v5 handles enums
            $errorCorrectionLevel = ErrorCorrectionLevel::High;
            $roundBlockSizeMode = RoundBlockSizeMode::Margin;
        } else {
            // while v4 handles classes
            \assert(class_exists(ErrorCorrectionLevelHigh::class));
            $errorCorrectionLevel = new ErrorCorrectionLevelHigh();
            \assert(class_exists(RoundBlockSizeModeMargin::class));
            $roundBlockSizeMode = new RoundBlockSizeModeMargin();
        }

        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($qrCodeContent)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel($errorCorrectionLevel)
            ->size($size)
            ->margin($margin)
            ->roundBlockSizeMode($roundBlockSizeMode)
            ->build();

        return $result->getDataUri();
    }
}
