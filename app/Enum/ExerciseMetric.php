<?php

namespace App\Enum;

enum ExerciseMetric: string
{
    case Incline = 'incline';
    case Resistance = 'resistance';
    case Level = 'level';
    case Speed = 'speed';
    case Watts = 'watts';
    case Cadence = 'cadence';
    case HeartRate = 'heart_rate';
    case Pace = 'pace';
}
