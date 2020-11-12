<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Helper;

use DateTimeImmutable;
use DateTimeZone;
use Exception;

class DateHelper
{
    public const DATE_TIMEZONE         = 'WRITEAWAY_TIMEZONE';
    public const DEFAULT_DATE_TIMEZONE = 'UTC';

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
            return new DateTimeZone(env(self::DATE_TIMEZONE));
        } catch (\Throwable $e) {
            return new DateTimeZone(self::DEFAULT_DATE_TIMEZONE);
        }
    }
}
