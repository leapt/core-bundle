<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Twig\Extension\Mocks;

final class TranslatorMock extends AbstractTranslatorMock
{
    public function trans(string $id, array $parameters = [], string $domain = null, string $locale = null): string
    {
        return $this->mockTrans($id, $parameters);
    }
}
