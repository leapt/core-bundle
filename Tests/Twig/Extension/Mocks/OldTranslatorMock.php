<?php

namespace Leapt\CoreBundle\Tests\Twig\Extension\Mocks;

class OldTranslatorMock extends AbstractTranslatorMock
{
    public function trans($id, array $parameters = [], $domain = null, $locale = null)
    {
        return $this->mockTrans($id, $parameters);
    }
}
