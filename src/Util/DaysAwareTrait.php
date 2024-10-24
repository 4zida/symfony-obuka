<?php

namespace App\Util;

use DateInterval;
use DateMalformedIntervalStringException;
use DateMalformedStringException;
use DateTimeImmutable;
use function Symfony\Component\Clock\now;

trait DaysAwareTrait
{
    public function getDays(): int
    {
        return $this->value;
    }

    /**
     * @throws DateMalformedStringException
     * @throws DateMalformedIntervalStringException
     */
    public function toFutureDate(?DateTimeImmutable $initialDate = null): DateTimeImmutable
    {
        if (!$initialDate) {
            return now()->add($this->toDateInterval());
        }

        return $initialDate->add($this->toDateInterval());
    }

    /**
     * @throws DateMalformedIntervalStringException
     */
    protected function toDateInterval(): DateInterval
    {
        return new DateInterval(sprintf('P%dD', $this->getDays()));
    }
}