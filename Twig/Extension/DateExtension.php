<?php

namespace Leapt\CoreBundle\Twig\Extension;

use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class DateExtension
 * @package Leapt\CoreBundle\Twig\Extension
 */
class DateExtension extends \Twig_Extension
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Date extension constructor
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
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
        );
    }

    /**
     * Return the name of the extension
     *
     * @return string
     */
    public function getName()
    {
        return 'leapt_date';
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

        $translator = $this->translator;

        $years = $interval->format('%y');
        $months = $interval->format('%m');
        $days = $interval->format('%d');
        $hours = (int)$interval->format('%H');
        $minutes = (int)$interval->format('%i');
        if ($years != 0) {
            $ago = $translator->transChoice('timeago.yearsago', $years, array('%years%' => $years), 'LeaptCoreBundle', $locale);
        } elseif ($months == 0 && $days == 0 && $hours == 0 && $minutes == 0) {
            $ago = $translator->trans('timeago.justnow', array(), 'LeaptCoreBundle', $locale);
        } elseif ($months == 0 && $days == 0 && $hours == 0) {
            $ago = $translator->transChoice('timeago.minutesago', $minutes, array('%minutes%' => $minutes), 'LeaptCoreBundle', $locale);
        } elseif ($months == 0 && $days == 0) {
            $ago = $translator->transChoice('timeago.hoursago', $hours, array('%hours%' => $hours), 'LeaptCoreBundle', $locale);
        } elseif ($months == 0) {
            $ago = $translator->transChoice('timeago.daysago', $days, array('%days%' => $days), 'LeaptCoreBundle', $locale);
        } else {
            $ago = $translator->transChoice('timeago.monthsago', $months, array('%months%' => $months), 'LeaptCoreBundle', $locale);
        }

        return $ago;
    }

    /**
     * Helper used to get a date interval between a date and now
     *
     * @param string|DateTime $datetime
     *
     * @return \DateInterval
     */
    private function relativeTime($datetime)
    {
        if (is_string($datetime)) {
            $datetime = new \DateTime($datetime);
        }

        $current_date = new \DateTime();

        $interval = $current_date->diff($datetime);

        return $interval;
    }
}