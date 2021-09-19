@props([
	'title' => 'Judul Default'
])

<div {{ $attributes->merge(['class' => 'flex items-center bg-white shadow-sm rounded-lg p-4 overflow-auto']) }}>
    @isset($icon)
        <div class="flex">
            {{ $icon }}
        </div>
    @endisset
	<div class="flex flex-col w-full">
        <div class="text-gray-800 text-xl font-bold">
            {{ $title }}
        </div>
        <div class="text-gray-500">
            {{ $slot }}
        </div>
    </div>
</div>

