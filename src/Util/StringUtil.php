<?php

namespace Leapt\CoreBundle\Util;

/**
 * Class StringUtil.
 */
class StringUtil
{
    /**
     * Camelizes a string.
     *
     * @param string $id A string to camelize
     *
     * @return string The camelized string
     */
    public static function camelize($id)
    {
        return preg_replace_callback('/(^|_|\.)+(.)/', function ($match) { return ('.' === $match[1] ? '_' : '') . strtoupper($match[2]); }, $id);
    }

    /**
     * A string to underscore.
     *
     * @param string $id The string to underscore
     *
     * @return string The underscored string
     */
    public static function underscore($id)
    {
        return strtolower(preg_replace(['/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'], ['\\1_\\2', '\\1_\\2'], strtr($id, '_', '.')));
    }

    /**
     * Removes the accents from an UTF-8 string.
     *
     * @static
     *
     * @param string $string
     * @param bool   $onlyUpperCase
     *
     * @return string
     */
    public static function unaccent($string, $onlyUpperCase = false)
    {
        $replacements = [
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A',
            'Ä' => 'A', 'Å' => 'A', 'Ç' => 'C', 'È' => 'E',
            'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I',
            'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ò' => 'O',
            'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O',
            'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U',
            'Ý' => 'Y',
        ];

        if (!$onlyUpperCase) {
            $replacements = array_merge($replacements, [
                'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
                'ä' => 'a', 'å' => 'a', 'ç' => 'c', 'è' => 'e',
                'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i',
                'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o',
                'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
                'ö' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u',
                'ü' => 'u', 'ý' => 'y', 'ÿ' => 'y', 'œ' => 'oe',
            ]);
        }

        return strtr($string, $replacements);
    }

    /**
     * @static
     *
     * @param $string
     *
     * @return string
     */
    public static function slugify($string)
    {
        $slug = self::unaccent($string);
        $slug = strtolower($slug);

        // Remove all none word characters
        $slug = preg_replace('/\W/', ' ', $slug);

        // More stripping. Replace spaces with dashes
        $slug = strtolower(preg_replace('/[^A-Z^a-z^0-9^\/]+/', '-',
            preg_replace('/([a-z\d])([A-Z])/', '\1_\2',
                preg_replace('/([A-Z]+)([A-Z][a-z])/', '\1_\2',
                    preg_replace('/::/', '/', $slug),
                ),
            ),
        ));

        return trim($slug, '-');
    }

    /**
     * @param string $string
     *
     * @return string
     *
     * @deprecated
     */
    public static function sluggify($string)
    {
        @trigger_error('sluggify() is deprecated. Use slugify instead', \E_USER_DEPRECATED);

        return self::slugify($string);
    }
}
