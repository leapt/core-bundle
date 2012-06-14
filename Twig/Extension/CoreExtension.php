<?php
namespace Snowcap\CoreBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CoreExtension extends \Twig_Extension implements ContainerAwareInterface
{

    /**
     * @var array
     */
    private $activePaths = array();

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var bool
     */
    private $useMultiByteString = false;

    /**
     * Core extension constructor
     * Check if MultiByte string is available
     */
    public function __construct()
    {
        $this->useMultiByteString = $this->isMultiByteStringAvailable();
    }

    /**
     * @param null|ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Get all available functions
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'set_active_paths' => new \Twig_Function_Method($this, 'setActivePaths'),
            'is_active_path'   => new \Twig_Function_Method($this, 'isActivePath'),
        );
    }

    /**
     * Get all available filters
     *
     * @return array
     */
    public function getFilters()
    {
        return array(
            'time_ago'      => new \Twig_Filter_Method($this, 'timeAgo'),
            'safe_truncate' => new \Twig_Filter_Method($this, 'safeTruncate', array('needs_environment' => true, 'is_safe' => array('html'))),
        );
    }

    /**
     * Set the paths to be considered as active (navigation-wise)
     *
     * @param array $paths an array of URI paths
     */
    public function setActivePaths(array $paths)
    {
        $this->activePaths = $paths;
    }

    /**
     * Checks if the provided path is to be considered as active
     *
     * @param string $path
     *
     * @return bool
     */
    public function isActivePath($path)
    {
        return in_array($path, $this->activePaths) || $path === $this->container->get('request')->getRequestUri();
    }

    /**
     * Filter used to display the time ago for a specific date
     *
     * @param \Datetime|string $datetime
     *
     * @return string
     */
    public function timeAgo($datetime, $locale = null)
    {
        $interval = $this->relativeTime($datetime);

        $translator = $this->container->get('translator');

        $years = $interval->format('%y');
        $months = $interval->format('%m');
        $days = $interval->format('%d');
        $hours = (int)$interval->format('%H');
        $minutes = (int)$interval->format('%i');
        if ($years != 0) {
            $ago = $translator->transChoice('timeago.yearsago', $years, array('%years%' => $years), 'SnowcapCoreBundle', $locale);
        } elseif ($months == 0 && $days == 0 && $hours == 0 && $minutes == 0) {
            $ago = $translator->trans('timeago.justnow', array(), 'SnowcapCoreBundle', $locale);
        } elseif ($months == 0 && $days == 0 && $hours == 0) {
            $ago = $translator->transChoice('timeago.minutesago', $minutes, array('%minutes%' => $minutes), 'SnowcapCoreBundle', $locale);
        } elseif ($months == 0 && $days == 0) {
            $ago = $translator->transChoice('timeago.hoursago', $hours, array('%hours%' => $hours), 'SnowcapCoreBundle', $locale);
        } elseif ($months == 0) {
            $ago = $translator->transChoice('timeago.daysago', $days, array('%days%' => $days), 'SnowcapCoreBundle', $locale);
        } else {
            $ago = $translator->transChoice('timeago.monthsago', $months, array('%months%' => $months), 'SnowcapCoreBundle', $locale);
        }

        return $ago;
    }

    /**
     * Filter used to safely truncate a string with html
     *
     * @param \Twig_Environment $env
     * @param string            $value
     * @param int               $length
     * @param bool              $preserve
     * @param string            $separator
     *
     * @return string
     */
    public function safeTruncate(\Twig_Environment $env, $value, $length = 30, $preserve = true, $separator = '...')
    {
        $charset = $env->getCharset();

        if ($this->useMultiByteString) {
            $strlen = function($string, $encoding = null) {
                return mb_strlen($string, $encoding);
            };
            $substr = function($string, $start, $length = null, $encoding = null) {
                return mb_substr($string, $start, $length, $encoding);
            };
            $strpos = function($haystack, $needle, $offset = null, $encoding = null) {
                return mb_strpos($haystack, $needle, $offset, $encoding);
            };
        } else {
            $strlen = function($string, $encoding = null) {
                return strlen($string);
            };
            $substr = function($string, $start, $length = null, $encoding = null) {
                return substr($string, $start, $length);
            };
            $strpos = function($haystack, $needle, $offset = null, $encoding = null) {
                return strpos($haystack, $needle, $offset);
            };
        }
        // First, strip tags to get a exact chars count
        $strippedValue = strip_tags($value);

        // Initialize the breakpoint to the exact length for now
        $breakpoint = $length;

        // Check if the string is bigger than the available length, otherwise, there is nothing to do
        if (strlen($strippedValue) > $length) {
            // Initialize the pipedValue used to replace spaces by pipe in html tags
            $pipedValue = $value;

            // Check if there is html tags in the original value
            if ($strippedValue !== $value) {
                // Replace spaces in html tags by pipes to easily split the string by spaces available in the text
                $pipedValue = preg_replace_callback(
                    '#<([^>]*)( )([^<]*)>#',
                    function($matches)
                    {
                        return str_replace(' ', '|', $matches[0]);
                    },
                    $value
                );
            }

            // Initialize the available words
            $words = explode(' ', $substr($strippedValue, 0, $breakpoint, $charset));
            $availableWords = count($words);
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
                        if ($nextSpace > 0) {
                            // Remove the last element which is outside the scope of defined length
                            array_pop($words);
                        }
                        // Get the count of available words
                        $availableWords = count($words);
                    } else { // Otherwise remove the last word from the array
                        $availableWords--;
                    }
                } else { // Otherwise remove the last word from the array
                    $availableWords--;
                }
            } else { // Otherwise, preserve the last word part and remove it from the array
                $lastWord = $words[count($words) - 1];
                $availableWords--;
            }

            // Split the piped value by spaces
            $words = explode(' ', $pipedValue);
            // Remove words that are not in the scope defined by the length
            $words = array_slice($words, 0, $availableWords);
            if ($lastWord !== '') {
                $words[] = $lastWord;
            }

            $pipedValue = implode(' ', $words);

            // Replace back pipes in html tags to spaces
            $value = preg_replace_callback(
                '#<([^>]*)(|)([^<]*)>#',
                function($matches)
                {
                    return str_replace('|', ' ', $matches[0]);
                },
                $pipedValue
            );

            // Finally close all unclosed tags and add trailing separator
            return $this->closeTags($value) . $separator;
        }

        return $value;

    }

    /**
     * Public method used to enable/disable MultiByte string
     * Useful for Unit Testing
     *
     * @param bool $useMultiByteString
     */
    public function setMultiByteString($useMultiByteString) {
        $this->useMultiByteString = $useMultiByteString && $this->isMultiByteStringAvailable();
    }

    /**
     * Check if MultiByte string is available
     *
     * @return bool
     */
    private function isMultiByteStringAvailable()
    {

        return function_exists('mb_get_info');
    }

    /**
     * Helper used to close html tags
     *
     * @param string $html
     *
     * @return string
     */
    private function closeTags($html)
    {
        preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
        $openedTags = $result[1]; #put all closed tags into an array

        preg_match_all('#</([a-z]+)>#iU', $html, $result);
        $closedTags = $result[1];

        $len_opened = count($openedTags);
        if (count($closedTags) == $len_opened) {
            return $html;
        }

        $openedTags = array_reverse($openedTags);
        for ($i = 0; $i < $len_opened; $i++) {
            if (!in_array($openedTags[$i], $closedTags)) {
                $html .= '</' . $openedTags[$i] . '>';
            } else {
                unset($closedTags[array_search($openedTags[$i], $closedTags)]);
            }

        }
        return $html;
    }

    /**
     * Helper used to get a date interval between a date and now
     *
     * @param string|DateTime $datetime
     *
     * @return \DateInterval
     */
    private function relativeTime($datetime = null)
    {
        if ($datetime === null) {
            return "";
        }

        if (is_string($datetime)) {
            $datetime = new \DateTime($datetime);
        }

        $current_date = new \DateTime();

        $interval = $current_date->diff($datetime);

        return $interval;

    }

    /**
     * Return the name of the extension
     *
     * @return string
     */
    public function getName()
    {
        return 'snowcap_core';
    }
}