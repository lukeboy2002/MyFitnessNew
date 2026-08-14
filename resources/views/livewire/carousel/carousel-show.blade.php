<div id="default-carousel" class="relative w-full" data-carousel="slide">
    <!-- Carousel wrapper -->
    <div class="relative overflow-hidden rounded-base aspect-video w-full lg:max-h-144">
        <!-- Logo overlay -->
        <a href="{{ route('dashboard') }}">
            <div class="absolute z-100 flex justify-center items-center gap-3">
                <img src="{{ asset('storage/assets/logo.png') }}"
                     class="w-20"
                     alt="logo">
                <div class="font-theme text-3xl text-secondary">
                    {{ config('app.name') }}
                </div>
            </div>
        </a>

        @foreach($items as $item)
            <div class="hidden duration-700 ease-in-out" data-carousel-item="active">

                <img src="{{ asset('storage/'. $item->image_path) }}"
                     class="absolute inset-0 w-full h-full object-cover opacity-50"
                     alt="{{ $item->author }}">
                @if($item->author)
                    <div
                        class="absolute z-100 right-0 bottom-0 flex justify-center items-center gap-1 py-2 px-2 text-muted text-xs ">
                        <span>
                            image by:
                        </span>
                        <a class="hover:underline" href="{{ $item->link }}" target="_blank">{{ $item->author }}</a>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
