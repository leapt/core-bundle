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

    public function setContainer(ContainerInterface $container = null){
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
            'is_active_path' => new \Twig_Function_Method($this, 'isActivePath'),
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
     * @return string
     */
    public function timeAgo($datetime, $locale = null) {
        $interval = $this->relativeTime($datetime);

        $translator = $this->container->get('translator');

        $years = $interval->format('%y');
        $months = $interval->format('%m');
        $days = $interval->format('%d');
        $hours = (int) $interval->format('%H');
        $minutes = (int) $interval->format('%i');
        if ($years != 0) {
            $ago = $translator->transChoice('timeago.yearsago', $years, array('%years%' => $years), 'SnowcapCoreBundle', $locale);
        } elseif ($months == 0 && $days == 0 && $hours == 0 && $minutes == 0) {
            $ago = $translator->trans('timeago.justnow', array(), 'SnowcapCoreBundle', $locale);
        } elseif ($months == 0 && $days == 0 && $hours == 0) {
            $ago = $translator->transChoice('timeago.minutesago', $minutes, array('%minutes%' => $minutes), 'SnowcapCoreBundle', $locale);
        } elseif($months == 0 && $days == 0) {
            $ago = $translator->transChoice('timeago.hoursago', $hours, array('%hours%' => $hours), 'SnowcapCoreBundle', $locale);
        } elseif($months == 0) {
            $ago = $translator->transChoice('timeago.daysago', $days, array('%days%' => $days), 'SnowcapCoreBundle', $locale);
        } else {
            $ago = $translator->transChoice('timeago.monthsago', $months, array('%months%' => $months), 'SnowcapCoreBundle', $locale);
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