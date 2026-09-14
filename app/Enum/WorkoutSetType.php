<?php

namespace App\Enum;

enum WorkoutSetType: string
{
    case Warmup = 'warmup';
    case Working = 'working';
    case Drop = 'drop';
    case Failure = 'failure';
    case Cooldown = 'cooldown';
}
