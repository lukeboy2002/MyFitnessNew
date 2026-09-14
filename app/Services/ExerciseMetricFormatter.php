<?php

namespace App\Services;

class ExerciseMetricFormatter
{
    /**
     * Format a metric value for display.
     */
    public function formatMetric(
        string $metric,
        float|int|null $value
    ): string {
        if ($value === null) {
            return '-';
        }

        return match ($metric) {

            // Strength
            'volume' => number_format(
                $value,
                0,
                ',',
                '.'
            ).' kg',

            'weight' => number_format(
                $value,
                1,
                ',',
                '.'
            ).' kg',

            'reps' => number_format(
                $value,
                0
            ).' reps',

            'one_rep_max' => number_format(
                $value,
                1,
                ',',
                '.'
            ).' kg',

            // Cardio
            'distance' => number_format(
                $value,
                2,
                ',',
                '.'
            ).' km',

            'duration' => $this->formatDuration(
                (int) $value
            ),

            'calories' => number_format(
                $value,
                0
            ).' kcal',

            'speed' => number_format(
                $value,
                1,
                ',',
                '.'
            ).' km/h',

            'pace' => $this->formatPace(
                (int) $value
            ),

            'heart_rate' => number_format(
                $value,
                0
            ).' bpm',

            'watts' => number_format(
                $value,
                0
            ).' W',

            default => number_format(
                $value,
                2,
                ',',
                '.'
            ),
        };
    }

    /**
     * Format seconds as HH:MM:SS or MM:SS.
     */
    public function formatDuration(
        int $seconds
    ): string {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        if ($hours > 0) {
            return sprintf(
                '%02d:%02d:%02d',
                $hours,
                $minutes,
                $secs
            );
        }

        return sprintf(
            '%02d:%02d',
            $minutes,
            $secs
        );
    }

    /**
     * Format pace stored as seconds per kilometer.
     */
    public function formatPace(
        int|float $seconds
    ): string {
        $seconds = (int) round($seconds);

        if ($seconds <= 0) {
            return '-';
        }

        $minutes = floor($seconds / 60);
        $secs = $seconds % 60;

        return sprintf(
            '%d:%02d /km',
            $minutes,
            $secs
        );
    }
}
