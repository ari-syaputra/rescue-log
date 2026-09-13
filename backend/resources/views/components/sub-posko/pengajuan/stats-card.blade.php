@props([
    'title',
    'value' => '',
    'subtitle',
    'iconBg' => 'bg-blue-50/80',  
    'iconColor' => 'text-blue-600',
    'isBadge' => false,
    'badgeText' => '',
    'badgeColor' => 'bg-emerald-500 text-white',
    'type' => 'default'
])

<div class="{{ $iconBg }} {{ $iconColor }} w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-2xs">
    @if($type === 'users')
        <x-heroicon-s-users class="w-6 h-6" />
    @elseif($type === 'user-group')
        <x-heroicon-s-user-group class="w-6 h-6" />
    @elseif($type === 'cpu-chip')
        <x-heroicon-s-cpu-chip class="w-6 h-6" />
    @endif
</div>

<div class="space-y-0.5">
    <p class="text-xs font-semibold text-slate-500">{{ $title }}</p>
    
    @if($isBadge)
        <div class="pt-1">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $badgeColor }}">
                {{ $badgeText }}
            </span>
        </div>
    @else
        <h4 class="text-xl font-extrabold text-slate-900">{{ $value }}</h4>
    @endif

    <p class="text-[11px] text-slate-400">{{ $subtitle }}</p>
</div>