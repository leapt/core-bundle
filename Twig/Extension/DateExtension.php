<?php

namespace Leapt\CoreBundle\Twig\Extension;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class DateExtension
 * @package Leapt\CoreBundle\Twig\Extension
 */
class DateExtension extends AbstractExtension
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
        return [
            new TwigFilter('time_ago', [$this, 'timeAgo']),
        ];
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

        $years = $interval->format('%y');
        $months = $interval->format('%m');
        $days = $interval->format('%d');
        $hours = (int)$interval->format('%H');
        $minutes = (int)$interval->format('%i');
        if ($years != 0) {
            $ago = $this->transChoice('timeago.yearsago', $years, ['%years%' => $years], $locale);
        } elseif ($months == 0 && $days == 0 && $hours == 0 && $minutes == 0) {
            $ago = $this->translator->trans('timeago.justnow', [], 'LeaptCoreBundle', $locale);
        } elseif ($months == 0 && $days == 0 && $hours == 0) {
            $ago = $this->transChoice('timeago.minutesago', $minutes, ['%minutes%' => $minutes], $locale);
        } elseif ($months == 0 && $days == 0) {
            $ago = $this->transChoice('timeago.hoursago', $hours, ['%hours%' => $hours], $locale);
        } elseif ($months == 0) {
            $ago = $this->transChoice('timeago.daysago', $days, ['%days%' => $days], $locale);
        } else {
            $ago = $this->transChoice('timeago.monthsago', $months, ['%months%' => $months], $locale);
        }

        return $ago;
    }

    /**
     * Helper used to get a date interval between a date and now
     *
     * @param string|\DateTime $datetime
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

    private function transChoice($id, $count, array $parameters, $locale = null)
    {
        if (Kernel::VERSION_ID >= 40200) {
            $parameters = array_merge($parameters, ['%count%' => $count]);

            return $this->translator->trans($id, $parameters, 'LeaptCoreBundle', $locale);
        }

        return $this->translator->transChoice($id, $count, $parameters, 'LeaptCoreBundle', $locale);
    }
}