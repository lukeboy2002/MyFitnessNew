<?php

namespace App\Livewire\Muscle;

use App\Services\MuscleProgressService;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class MuscleProgress extends Component
{
    public string $period = 'this_month';

    public ?int $expandedBodyPartId = null;

    /**
     * Set the active period filter.
     */
    public function setPeriod(string $period): void
    {
        if (array_key_exists($period, $this->getAvailablePeriods())) {
            $this->period = $period;
        }
    }

    /**
     * Get available period filters.
     *
     * @return array<string, string>
     */
    public function getAvailablePeriods(): array
    {
        return [
            'this_week' => __('This week'),
            'this_month' => __('This month'),
            'last_30_days' => __('Last 30 days'),
            'this_year' => __('This year'),
            'all_time' => __('All time'),
        ];
    }

    /**
     * Toggle expanded muscle groups for a body part.
     */
    public function toggleBodyPart(int $bodyPartId): void
    {
        $this->expandedBodyPartId = ($this->expandedBodyPartId === $bodyPartId)
            ? null
            : $bodyPartId;
    }

    public function render(MuscleProgressService $muscleProgressService): View
    {
        $userId = auth()->id();

        if (! $userId) {
            return view('livewire.muscle.muscle-progress', [
                'bodyParts' => collect(),
                'muscleGroups' => collect(),
                'totalSets' => 0,
                'maxSets' => 1,
                'periods' => $this->getAvailablePeriods(),
            ]);
        }

        [$startDate, $endDate] = $this->getDateRange();

        /** @var Collection $bodyParts */
        $bodyParts = $muscleProgressService->getSetsPerBodyPart($userId, $startDate, $endDate);

        $totalSets = (int) $bodyParts->sum('total_sets');
        $maxSets = (int) ($bodyParts->max('total_sets') ?: 1);

        /** @var Collection $muscleGroups */
        $muscleGroups = collect();

        if ($this->expandedBodyPartId) {
            $muscleGroups = $muscleProgressService->getSetsPerMuscleGroup(
                $userId,
                $this->expandedBodyPartId,
                $startDate,
                $endDate
            );
        }

        return view('livewire.muscle.muscle-progress', [
            'bodyParts' => $bodyParts,
            'muscleGroups' => $muscleGroups,
            'totalSets' => $totalSets,
            'maxSets' => $maxSets,
            'periods' => $this->getAvailablePeriods(),
        ]);
    }

    /**
     * Get the date range for the current period.
     *
     * @return array{0: CarbonInterface, 1: CarbonInterface}
     */
    protected function getDateRange(): array
    {
        return match ($this->period) {
            'this_week' => [now()->startOfWeek(), now()->endOfWeek()],
            'last_30_days' => [now()->subDays(30)->startOfDay(), now()->endOfDay()],
            'this_year' => [now()->startOfYear(), now()->endOfYear()],
            'all_time' => [Carbon::createFromTimestamp(0), now()->endOfDay()],
            default => [now()->startOfMonth(), now()->endOfMonth()],
        };
    }
}
