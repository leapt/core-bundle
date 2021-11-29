<?php

namespace Leapt\CoreBundle\Twig\Extension;

use Leapt\CoreBundle\Util\StringUtil;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TextExtension extends AbstractExtension
{
    public const MISSING_EXTENSION_EXCEPTION = 10;

    protected bool $useMultiByteString = false;

    /**
     * Core extension constructor
     * Check if MultiByte string is available.
     */
    public function __construct()
    {
        $this->setMultiByteString($this->isMultiByteStringAvailable());
    }

    /**
     * Get all available filters.
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('camelize', [$this, 'camelize'], ['is_safe' => ['html']]),
            new TwigFilter('safe_truncate', [$this, 'safeTruncate'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    /**
     * @param $string
     */
    public function camelize($string): string
    {
        return StringUtil::camelize($string);
    }

    /**
     * Filter used to safely truncate a string with html.
     */
    public function safeTruncate(Environment $env, string $value, int $length = 30, bool $preserve = true, string $separator = '...'): string
    {
        $charset = $env->getCharset();

        if ($this->isMultiByteStringAvailable() && $this->getMultiByteString()) {
            $strlen = function ($string, $encoding = null) {
                return mb_strlen($string, $encoding);
            };
            $substr = function ($string, $start, $length = null, $encoding = null) {
                return mb_substr($string, $start, $length, $encoding);
            };
            $strpos = function ($haystack, $needle, $offset = null, $encoding = null) {
                return mb_strpos($haystack, $needle, $offset, $encoding);
            };
        } else {
            $strlen = function ($string, $encoding = null) {
                return \strlen($string);
            };
            $substr = function ($string, $start, $length = null, $encoding = null) {
                return substr($string, $start, $length);
            };
            $strpos = function ($haystack, $needle, $offset = null, $encoding = null) {
                return strpos($haystack, $needle, $offset);
            };
        }
        // First, strip tags to get a exact chars count
        $strippedValue = strip_tags($value);

        // Initialize the breakpoint to the exact length for now
        $breakpoint = $length;

        // Check if the string is bigger than the available length, otherwise, there is nothing to do
        if (\strlen($strippedValue) > $length) {
            // Initialize the pipedValue used to replace spaces by pipe in html tags
            $pipedValue = $value;

            // Check if there is html tags in the original value
            if ($strippedValue !== $value) {
                // Replace spaces in html tags by pipes to easily split the string by spaces available in the text
                $pipedValue = preg_replace_callback(
                    '#<([^>]*)( )([^<]*)>#',
                    function ($matches) {
                        return str_replace(' ', '|', $matches[0]);
                    },
                    $value
                );
            }

            // Initialize the available words
            $words = explode(' ', $substr($strippedValue, 0, $breakpoint, $charset));
            $availableWords = \count($words);
            $lastWord = '';

            // If we have to preserve words
            if ($preserve) {
                // First check if there is any spaces available in the string
                if (false !== $strpos($substr($strippedValue, 0, $length, $charset), ' ', null, $charset)) {
                    // Get a breakpoint at the next space after the available length
                    if (false !== ($nextSpace = $strpos($substr($strippedValue, $length, $strlen($strippedValue, $charset), $charset), ' ', null, $charset))) {
                        // Update breakpoint to next space
                        $breakpoint += $nextSpace;
                        // Split the string by spaces until the breakpoint
                        $words = explode(' ', $substr($strippedValue, 0, $breakpoint, $charset));
                        // If the space is not the next char, we should remove last word
                        if (0 < $nextSpace) {
                            // Remove the last element which is outside the scope of defined length
                            array_pop($words);
                        }
                        // Get the count of available words
                        $availableWords = \count($words);
                    } else { // Otherwise remove the last word from the array
                        --$availableWords;
                    }
                } else { // Otherwise remove the last word from the array
                    --$availableWords;
                }
            } else { // Otherwise, preserve the last word part and remove it from the array
                $lastWord = $words[\count($words) - 1];
                --$availableWords;
            }

            // Split the piped value by spaces
            $words = explode(' ', $pipedValue);
            // Remove words that are not in the scope defined by the length
            $words = \array_slice($words, 0, $availableWords);
            if ('' !== $lastWord) {
                $words[] = $lastWord;
            }

            $pipedValue = implode(' ', $words);

            // Replace back pipes in html tags to spaces
            $value = preg_replace_callback(
                '#<([^>]*)(|)([^<]*)>#',
                function ($matches) {
                    return str_replace('|', ' ', $matches[0]);
                },
                $pipedValue
            );

            // Finally close all unclosed tags and add trailing separator
            return $this->closeTags($value . $separator);
        }

        return $value;
    }

    /**
     * Enable/disable MultiByte string
     * Useful for Unit Testing.
     */
    public function setMultiByteString(bool $useMultiByteString): self
    {
        if ($useMultiByteString && !$this->isMultiByteStringAvailable()) {
            throw new \BadFunctionCallException('mbstring extension is not enabled', self::MISSING_EXTENSION_EXCEPTION);
        }
        $this->useMultiByteString = $useMultiByteString;

        return $this;
    }

    /**
     * Check if MultiByte string is used.
     */
    public function getMultiByteString(): bool
    {
        return $this->useMultiByteString;
    }

    /**
     * Check if MultiByte string is available.
     */
    public function isMultiByteStringAvailable(): bool
    {
        return \function_exists('mb_get_info');
    }

    /**
     * Helper used to close html tags.
     */
    protected function closeTags(string $html): string
    {
        preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
        $openedTags = $result[1]; //put all closed tags into an array

        preg_match_all('#</([a-z]+)>#iU', $html, $result);
        $closedTags = $result[1];

        $len_opened = \count($openedTags);
        if (\count($closedTags) === $len_opened) {
            return $html;
        }

        $openedTags = array_reverse($openedTags);
        for ($i = 0; $i < $len_opened; ++$i) {
            if (!\in_array($openedTags[$i], $closedTags, true)) {
                $html .= '</' . $openedTags[$i] . '>';
            } else {
                unset($closedTags[array_search($openedTags[$i], $closedTags, true)]);
            }
        }

        return $html;
    }
}
