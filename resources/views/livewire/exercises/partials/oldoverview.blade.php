@php
    $roleLabels = [
        'primary' => 'Muscle primair',
        'secondary' => 'Muscle secondair',
        'stabilizer' => 'Muscle secondair', // secondary én stabilizer onder dezelfde groep
    ];

    $roleSortOrder = [
        'primary' => 1,
        'secondary' => 2,
        'stabilizer' => 3,
    ];

    $getRoleKey = fn ($muscle) => $muscle->pivot->role instanceof BackedEnum
        ? $muscle->pivot->role->value
        : $muscle->pivot->role;

    $grouped = $exercise->muscles
        ->sortBy(fn ($muscle) => $roleSortOrder[$getRoleKey($muscle)] ?? 99)
        ->groupBy(fn ($muscle) => $roleLabels[$getRoleKey($muscle)] ?? 'Overig');
@endphp
<div class="flex flex-col gap-4">

    @if ($exercise->bodyParts->isNotEmpty())
        <div>
            <h2 class="mb-3 text-sm font-semibold text-primary">
                Body Parts
            </h2>

            <div class="flex flex-wrap gap-2">
                @foreach ($exercise->bodyParts as $bodyPart)
                    <x-badge.default
                        variant="ghost"
                        icon="person-standing"
                    >
                        {{ $bodyPart->name }}
                    </x-badge.default>
                @endforeach
            </div>
        </div>
    @endif

    @if ($exercise->muscles->isNotEmpty())
        <div>
            <div class="flex flex-col flex-wrap gap-2 mb-3">
                @foreach ($grouped as $label => $muscles)
                    <div class="flex flex-col flexgap-2">
                        <div class="mb-3 text-sm font-semibold text-primary">{{ $label }}:</div>
                        @foreach ($muscles as $muscle)
                            <x-badge.default variant="ghost" icon="biceps-flexed">
                                {{ $muscle->name }}
                            </x-badge.default>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    @endif


</div>
