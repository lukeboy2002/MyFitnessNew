@props(['enableImageUpload' => false])
<div class="w-full border border-border rounded-lg bg-surface">
    <div class="px-3 py-2 border-b border-border">
        <div class="flex flex-wrap items-center">
            <div class="flex items-center space-x-1 rtl:space-x-reverse flex-wrap">
                <x-tiptapeditor.toolbar :enable-image-upload="$enableImageUpload"/>
            </div>
        </div>
    </div>
    <div class="px-4 py-2 bg-surface rounded-b-lg">
        {{ $slot }}
    </div>
</div>

