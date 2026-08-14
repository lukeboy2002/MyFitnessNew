<select {{ $attributes->merge(['class' => 'text-muted block w-full py-1.5 bg-surface border border-border text-heading text-xs rounded-md focus:outline-none focus:ring-0 focus:border-secondary shadow-xs placeholder:text-muted' ]) }} >
    {{ $slot }}
</select>
