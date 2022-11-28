<?php

namespace SB\Traits;

/**
 * Trait Timing
 * @package SB\Traits
 */
trait Timing
{
    /** @var \DateTime[] $timerContainer */
    protected static $timerContainer = [];

    /**
     * Задает таймер и возвращает его значение
     * @param string $key
     * @return \DateTime
     */
    public static function startTimer(string $key = 'default'): \DateTime
    {
        return self::$timerContainer[$key] = new \DateTime();
    }

    /**
     * Возвращает интервал для таймера
     * @param string $key
     * @return \DateInterval|null
     */
    public static function getInterval(string $key = 'default'): \DateInterval
    {
        try {
            if (empty(self::$timerContainer[$key])) {
                throw new \RuntimeException('Таймер не создан');
            }

            $currentTime = new \DateTime();

            $interval = $currentTime->diff(self::$timerContainer[$key], true);

            if ($interval === false) {
                throw new \RuntimeException('Не удалось получить интервал');
            }

            return $interval;
        } catch (\Exception $exception) {
            return null;
        }
    }

    /**
     * Возвращает время работы таймера и информацию о памяти
     * @param string $key
     * @param string $dateFormat
     * @return string
     */
    public static function getTiming(string $key = 'default', string $dateFormat = ''): string
    {
        if (empty(self::$timerContainer[$key])) {
            return 'Таймер не создан';
        }

        $interval = self::getInterval($key);

        if (null === $interval) {
            return 'Не удалось получить интервал';
        }

        return self::formatTiming($key, $interval, $dateFormat);
    }

    /**
     * @param string $key
     * @param \DateInterval $interval
     * @param string $dateFormat
     * @return string
     */
    protected static function formatTiming(string $key, \DateInterval $interval, string $dateFormat = ''): string
    {
        if (empty($dateFormat)) {
            $dateFormat = '%s секунд';
            if (PHP_VERSION_ID >= 70100) {
                $dateFormat = '%s.%f секунд';
            }
        }

        $time = $interval->format($dateFormat);
        $memoryUsage = round(memory_get_usage() / 1024 / 1024) . ' MB';

        return sprintf('Таймер "%s" работал за %s. Использование памяти: %s', $key, $time, $memoryUsage);
    }
}