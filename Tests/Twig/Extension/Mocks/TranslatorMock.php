<?php

namespace Snowcap\CoreBundle\Tests\Twig\Extension\Mocks;

class TranslatorMock implements \Symfony\Component\Translation\TranslatorInterface
{
    /**
     * @var string
     */
    private $locale;

    /**
     * Translates the given message.
     *
     * @param string $id         The message id
     * @param array  $parameters An array of parameters for the message
     * @param string $domain     The domain for the message
     * @param string $locale     The locale
     *
     * @return string The translated string
     *
     * @api
     */
    function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        return $this->mockTrans($id, $parameters);
    }

    /**
     * Translates the given choice message by choosing a translation according to a number.
     *
     * @param string  $id         The message id
     * @param integer $number     The number to use to find the indice of the message
     * @param array   $parameters An array of parameters for the message
     * @param string  $domain     The domain for the message
     * @param string  $locale     The locale
     *
     * @return string The translated string
     *
     * @api
     */
    function transChoice($id, $number, array $parameters = array(), $domain = null, $locale = null)
    {

        return $this->mockTrans($id, $parameters, $number);
    }

    /**
     * Sets the current locale.
     *
     * @param string $locale The locale
     *
     * @api
     */
    function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Returns the current locale.
     *
     * @return string The locale
     *
     * @api
     */
    function getLocale()
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