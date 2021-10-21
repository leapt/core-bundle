<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Twig\Extension\Mocks;

use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractTranslatorMock implements TranslatorInterface
{
    protected string $locale;

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * Method used to mock the trans and transChoice methods.
     *
     * @param string $id
     * @param array  $parameters
     * @param null   $number
     */
    protected function mockTrans($id, $parameters, $number = null): string
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
