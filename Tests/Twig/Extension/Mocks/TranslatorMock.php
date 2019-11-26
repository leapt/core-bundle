<?php

namespace Leapt\CoreBundle\Tests\Twig\Extension\Mocks;

use Symfony\Contracts\Translation\TranslatorInterface;

class TranslatorMock implements TranslatorInterface
{
    /**
     * @var string
     */
    private $locale;

    public function trans(string $id, array $parameters = [], string $domain = null, string $locale = null)
    {
        return $this->mockTrans($id, $parameters);
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Method used to mock the trans and transChoice methods
     *
     * @param string      $id
     * @param array       $parameters
     * @param null        $number
     *
     * @return string
     */
    private function mockTrans($id, $parameters, $number = null)
    {
        $separator = '|';

        $parts = array();
        $parts[] = $id;
        if ($number !== null) {
            $parts[] = $number;
        }

        foreach($parameters as $name => $value) {
            $parts[] = $name . '=' . $value;
        }

        return implode($separator, $parts);
    }
}
