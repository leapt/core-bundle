<?php

namespace Leapt\CoreBundle\Twig\Extension;

use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DateExtension extends AbstractExtension
{
    public function __construct(private TranslatorInterface $translator)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('time_ago', [$this, 'timeAgo']),
        ];
    }

    /**
     * Filter used to display the time ago for a specific date.
     */
    public function timeAgo(\Datetime|string $datetime, $locale = null): string
    {
        $interval = $this->relativeTime($datetime);

        $years = (int) $interval->format('%y');
        $months = (int) $interval->format('%m');
        $days = (int) $interval->format('%d');
        $hours = (int) $interval->format('%H');
        $minutes = (int) $interval->format('%i');
        if (0 !== $years) {
            $ago = $this->transChoice('timeago.yearsago', $years, ['%years%' => $years], $locale);
        } elseif (0 === $months && 0 === $days && 0 === $hours && 0 === $minutes) {
            $ago = $this->translator->trans('timeago.justnow', [], 'LeaptCoreBundle', $locale);
        } elseif (0 === $months && 0 === $days && 0 === $hours) {
            $ago = $this->transChoice('timeago.minutesago', $minutes, ['%minutes%' => $minutes], $locale);
        } elseif (0 === $months && 0 === $days) {
            $ago = $this->transChoice('timeago.hoursago', $hours, ['%hours%' => $hours], $locale);
        } elseif (0 === $months) {
            $ago = $this->transChoice('timeago.daysago', $days, ['%days%' => $days], $locale);
        } else {
            $ago = $this->transChoice('timeago.monthsago', $months, ['%months%' => $months], $locale);
        }

        return $ago;
    }

    /**
     * Helper used to get a date interval between a date and now.
     */
    private function relativeTime(\DateTime|string $datetime): \DateInterval
    {
        if (\is_string($datetime)) {
            $datetime = new \DateTime($datetime);
        }

        $currentDate = new \DateTime();

        return $currentDate->diff($datetime);
    }

    private function transChoice($id, $count, array $parameters, $locale = null): string
    {
        $parameters = array_merge($parameters, ['%count%' => $count]);

        return $this->translator->trans($id, $parameters, 'LeaptCoreBundle', $locale);
    }
}
