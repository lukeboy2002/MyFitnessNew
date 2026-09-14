<div class="rounded-xl border border-border bg-surface p-5">
    @if ($exercise->description)
        <p class="prose prose-orange dark:prose-invert text-primary w-full">
            {!! $exercise->howto !!}
        </p>
    @else
        <p class="text-sm text-muted">
            No instructions available for this exercise yet.
        </p>
    @endif
</div>
