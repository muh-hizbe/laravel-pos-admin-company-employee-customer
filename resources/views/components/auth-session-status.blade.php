@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'flex font-medium text-sm text-green-600']) }}>
        {{ $status }}
    </div>
@endif
