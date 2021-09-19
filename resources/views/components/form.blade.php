@props([
	'title' => 'Form',
])

<form x-on:submit.prevent class="bg-white shadow rounded p-4 sticky top-0" {{ $attributes }}>
    <div class="w-full text-center p-2 font-semibold text-xl">
        {{ $title }}
    </div>
    {{ $slot }}
    <div class="w-full mt-2">
        {{ $footer }}
    </div>
</form>
