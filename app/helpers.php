<?php

/**
 * Helper Functions untuk Format Tanggal Indonesia
 * Optimized version with static arrays and efficient logic
 */

if (!function_exists("getMonthsIndonesian")) {
    /**
     * Get cached Indonesian month names
     * @param bool $short Whether to return short month names
     * @return array
     */
    function getMonthsIndonesian($short = false)
    {
        static $months = null;
        static $monthsShort = null;

        if ($months === null) {
            $months = [
                1 => "Januari",
                2 => "Februari",
                3 => "Maret",
                4 => "April",
                5 => "Mei",
                6 => "Juni",
                7 => "Juli",
                8 => "Agustus",
                9 => "September",
                10 => "Oktober",
                11 => "November",
                12 => "Desember",
            ];

            $monthsShort = [
                1 => "Jan",
                2 => "Feb",
                3 => "Mar",
                4 => "Apr",
                5 => "Mei",
                6 => "Jun",
                7 => "Jul",
                8 => "Agu",
                9 => "Sep",
                10 => "Okt",
                11 => "Nov",
                12 => "Des",
            ];
        }

        return $short ? $monthsShort : $months;
    }
}

if (!function_exists("getDaysIndonesian")) {
    /**
     * Get cached Indonesian day names
     * @return array
     */
    function getDaysIndonesian()
    {
        static $days = null;

        if ($days === null) {
            $days = [
                "Sunday" => "Minggu",
                "Monday" => "Senin",
                "Tuesday" => "Selasa",
                "Wednesday" => "Rabu",
                "Thursday" => "Kamis",
                "Friday" => "Jumat",
                "Saturday" => "Sabtu",
            ];
        }

        return $days;
    }
}

if (!function_exists("formatTanggalIndonesia")) {
    /**
     * Format date to Indonesian format (e.g., "1 Juli 2024")
     * @param string|null $date
     * @return string
     */
    function formatTanggalIndonesia($date)
    {
        if (!$date || !($timestamp = strtotime($date))) {
            return "-";
        }

        $months = getMonthsIndonesian();

        return date("j", $timestamp) .
            " " .
            $months[date("n", $timestamp)] .
            " " .
            date("Y", $timestamp);
    }
}

if (!function_exists("formatTanggalIndonesiaWithDay")) {
    /**
     * Format date to Indonesian format with day (e.g., "Senin, 1 Juli 2024")
     * @param string|null $date
     * @return string
     */
    function formatTanggalIndonesiaWithDay($date)
    {
        if (!$date || !($timestamp = strtotime($date))) {
            return "-";
        }

        $days = getDaysIndonesian();
        $dayName = $days[date("l", $timestamp)];

        return $dayName . ", " . formatTanggalIndonesia($date);
    }
}

if (!function_exists("formatTanggalIndonesiaShort")) {
    /**
     * Format date to short Indonesian format (e.g., "1 Jul 2024")
     * @param string|null $date
     * @return string
     */
    function formatTanggalIndonesiaShort($date)
    {
        if (!$date || !($timestamp = strtotime($date))) {
            return "-";
        }

        $months = getMonthsIndonesian(true);

        return date("j", $timestamp) .
            " " .
            $months[date("n", $timestamp)] .
            " " .
            date("Y", $timestamp);
    }
}

if (!function_exists("formatTanggalBatch")) {
    /**
     * Format multiple dates efficiently
     * @param array $dates Array of dates
     * @param string $format 'default', 'with_day', or 'short'
     * @return array
     */
    function formatTanggalBatch(array $dates, $format = "default")
    {
        $results = [];

        foreach ($dates as $key => $date) {
            $results[$key] = match ($format) {
                "with_day" => formatTanggalIndonesiaWithDay($date),
                "short" => formatTanggalIndonesiaShort($date),
                default => formatTanggalIndonesia($date),
            };
        }

        return $results;
    }
}
