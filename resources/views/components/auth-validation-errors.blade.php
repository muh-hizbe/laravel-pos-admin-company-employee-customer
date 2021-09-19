@props(['errors'])

@if ($errors->any())
    <div {{ $attributes }} x-data="{hidden: false}" x-show="!hidden">
        <div class="w-11/12">
            <div class="font-medium text-red-600">
                {{ __('Whoops! Something went wrong.') }}
            </div>
            <ul class="list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <div class="flex flex-row-reverse w-1/12">
            <svg xmlns="http://www.w3.org/2000/svg" @click="hidden = true" class="h-6 w-6 cursor-pointer hover:opacity-25 text-red-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>
@endif
