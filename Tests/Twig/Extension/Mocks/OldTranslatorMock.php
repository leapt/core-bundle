<?php

namespace Leapt\CoreBundle\Tests\Twig\Extension\Mocks;

class OldTranslatorMock extends AbstractTranslatorMock
{
    public function trans($id, array $parameters = [], string $domain = null, string $locale = null)
    {
        return $this->mockTrans($id, $parameters);
    }
}
