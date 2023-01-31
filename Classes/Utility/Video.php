<?php

declare(strict_types=1);

namespace UniversityOfCopenhagen\KuVideo23\Utility;

/**
 * This file is part of the "ku_video23" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Video utility class
 */
class Video
{
    /**
     * Convert seconds to h:mm:ss. Can be expanded to include days.
     * @param int $inputSeconds video duration in seconds
     */
    public static function formatTimestamp($inputSeconds)
    {
        if (!$inputSeconds) {
            return;
        }
        
        $secondsInAMinute = 60;
        $secondsInAnHour = 60 * $secondsInAMinute;
        $secondsInADay = 24 * $secondsInAnHour;

        // Extract days
        (int)$days = floor($inputSeconds / $secondsInADay);

        // Extract hours
        $hourSeconds = $inputSeconds % $secondsInADay;
        (int)$hours = floor($hourSeconds / $secondsInAnHour);

        // Extract minutes
        $minuteSeconds = $hourSeconds % $secondsInAnHour;
        (int)$minutes = floor($minuteSeconds / $secondsInAMinute);

        // Extract the remaining seconds
        $remainingSeconds = $minuteSeconds % $secondsInAMinute;
        (int)$seconds = ceil($remainingSeconds);

        // Pad with zeros, but remove '0:' if no hours
        $result = preg_replace('/\b0:\b/', '', $hours . ':' . sprintf("%02d", $minutes) . ':' . sprintf("%02d", $seconds));

        return $result;
    }
}
