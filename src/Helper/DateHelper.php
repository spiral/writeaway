<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Helper;

use DateTimeImmutable;
use DateTimeZone;
use Exception;
use Spiral\WriteAway\Model\Environment;

class DateHelper
{
    /**
     * @param string $time
     * @return DateTimeImmutable
     * @throws Exception
     */
    public static function immutable(string $time = 'now'): DateTimeImmutable
    {
        $time = trim($time);
        return new DateTimeImmutable(is_numeric($time) ? "@$time" : $time, self::timezone());
    }

    public static function timezone(): DateTimeZone
    {
        try {
            return new DateTimeZone(env(Environment::DATE_TIMEZONE));
        } catch (\Throwable $e) {
            return new DateTimeZone(Environment::DEFAULT_DATE_TIMEZONE);
        }
    }
}
