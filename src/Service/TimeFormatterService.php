<?php

namespace App\Service;

use DateTimeImmutable;

class TimeFormatterService
{
    public function getRelativeTime(?DateTimeImmutable $date, DateTimeImmutable $now): string
    {
        if (!$date) {
            return 'Not modified yet';
        }

        $diff = $now->getTimestamp() - $date->getTimestamp();
        $week = 7 * 86400; // 7 days in seconds

        if ($diff < 60) {
            return $diff . ' second' . ($diff > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 3600) {
            $minutes = round($diff / 60);
            return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 86400) {
            $hours = round($diff / 3600);
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } elseif ($diff < $week) {
            $days = round($diff / 86400);
            return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        } else {
            return $date->format('F j, Y'); // e.g., "January 29, 2025"
        }
    }
}
