@props([
    'title',
    'value',
    'bgIcon' => 'bg-amber-50',
    'textIcon' => 'text-amber-600',
    'textValue' => 'text-gray-800'
])

<div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center space-x-4">
    <div class="p-3 rounded-xl {{ $bgIcon }} {{ $textIcon }}">
        {{ $slot }}
    </div>
    <div>
        <p class="text-xs font-semibold text-gray-400 uppercase">{{ $title }}</p>
        <h4 class="text-xl font-bold {{ $textValue }}">{{ $value }}</h4>
    </div>
</div>