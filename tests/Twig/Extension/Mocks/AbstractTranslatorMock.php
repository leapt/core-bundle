<?php

namespace Leapt\CoreBundle\Tests\Twig\Extension\Mocks;

use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractTranslatorMock implements TranslatorInterface
{
    /**
     * @var string
     */
    protected $locale;

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Method used to mock the trans and transChoice methods.
     *
     * @param string $id
     * @param array  $parameters
     * @param null   $number
     *
     * @return string
     */
    protected function mockTrans($id, $parameters, $number = null)
    {
        $separator = '|';

        $parts = [];
        $parts[] = $id;
        if (null !== $number) {
            $parts[] = $number;
        }

        foreach ($parameters as $name => $value) {
            $parts[] = $name . '=' . $value;
        }

        return implode($separator, $parts);
    }
}
