<?php

namespace Snowcap\CoreBundle\Twig\Extension;

use Symfony\Component\Translation\TranslatorInterface;

class DateExtension extends \Twig_Extension
{
    /**
     * @var Translator
     */
    private $translator;

    /**
     * Date extension constructor
     *
     * @param Translator $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Get all available filters
     *
     * @return array
     *
     * @codeCoverageIgnore
     */
    public function getFilters()
    {
        return array(
            'time_ago'      => new \Twig_Filter_Method($this, 'timeAgo'),
        );
    }

    /**
     * Return the name of the extension
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getName()
    {
        return 'snowcap_date';
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