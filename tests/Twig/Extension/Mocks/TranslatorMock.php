<?php

namespace Leapt\CoreBundle\Tests\Twig\Extension\Mocks;

class TranslatorMock extends AbstractTranslatorMock
{
    public function trans(string $id, array $parameters = [], string $domain = null, string $locale = null)
    {
        return $this->mockTrans($id, $parameters);
    }
}
