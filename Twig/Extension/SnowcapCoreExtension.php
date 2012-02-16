<?php
namespace Snowcap\CoreBundle\Twig\Extension;

use Twig_Environment;
use Symfony\Component\HttpFoundation\Request;

class SnowcapCoreExtension extends \Twig_Extension
{

    /**
     * Get all available functions
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'is_menu_active' => new \Twig_Function_Method($this, 'isMenuActive'),
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
            'time_ago' => new \Twig_Filter_Method($this, 'timeAgo'),
            'safe_truncate' => new \Twig_Filter_Method($this, 'safeTruncate', array('is_safe' => array('html'))),
        );
    }

    /**
     * Filter used to display the time ago for a specific date
     *
     * @param \Datetime|string $datetime
     * @return string
     */
    public function timeAgo($datetime) {
        $interval = $this->relativeTime($datetime);

        $years = $interval->format('%y');
        $months = $interval->format('%m');
        $days = $interval->format('%d');
        if ($years != 0) {
            $ago = $years . ' year(s) ago';
        } else {
            if ($months == 0 && $days == 0) {
                $ago = 'Today';
            } elseif ($months == 0 && $days == 1) {
                $ago = 'Yesterday';
            } else {
                $ago = ($months == 0 ? $days . ' day(s) ago' : $months . ' month(s) ago');
            }
        }

        return $ago;
    }

    /**
     * Filter used to safely truncate a string with html
     *
     * @param string $value
     * @param int $length
     * @param bool $preserve
     * @param string $separator
     * @return string
     */
    public function safeTruncate($value, $length = 30, $preserve = true, $separator = ' ...')
    {
        if (strlen($value) > $length) {
            if ($preserve) {
                if (false !== ($breakpoint = strpos($value, ' ', $length))) {
                    $length = $breakpoint;
                }
            }

            return $this->closeTags(substr($value, 0, $length) . $separator);
        }

        return $value;
    }

    /**
     * Helper used to get a date interval between a date and now
     *
     * @param string|DateTime $datetime
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
     * Helper used to close html tags
     *
     * @param string $html
     * @return string
     */
    private function closeTags($html)
    {
        preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
        $openedtags = $result[1]; #put all closed tags into an array

        preg_match_all('#</([a-z]+)>#iU', $html, $result);
        $closedtags = $result[1];

        $len_opened = count($openedtags);
        if (count($closedtags) == $len_opened) {
            return $html;
        }

        $openedtags = array_reverse($openedtags);
        for ($i = 0; $i < $len_opened; $i++) {
            if (!in_array($openedtags[$i], $closedtags)) {
                $html .= '</' . $openedtags[$i] . '>';
            } else {
                unset($closedtags[array_search($openedtags[$i], $closedtags)]);
            }

        }
        return $html;
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