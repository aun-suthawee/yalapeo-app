@props([
    'color' => 'primary',
    'darkMode' => false,
    'detail' => null,
    'icon' => null,
    'keyBindings' => null,
    'tag' => 'button',
    'type' => 'button',
])

@php
    $hasHoverAndFocusState = ($tag !== 'a' || filled($attributes->get('href')));

    $buttonClasses = \Illuminate\Support\Arr::toCssClasses([
        'filament-dropdown-list-item filament-dropdown-item group flex w-full items-center whitespace-nowrap rounded-md p-2 text-sm outline-none',
        'hover:text-white focus:text-white' => $hasHoverAndFocusState,
        'hover:bg-primary-500 focus:bg-primary-500' => ($color === 'primary' || $color === 'secondary') && $hasHoverAndFocusState,
        'hover:bg-danger-500 focus:bg-danger-500' => $color === 'danger' && $hasHoverAndFocusState,
        'hover:bg-success-500 focus:bg-success-500' => $color === 'success' && $hasHoverAndFocusState,
        'hover:bg-warning-500 focus:bg-warning-500' => $color === 'warning' && $hasHoverAndFocusState,
    ]);

    $detailClasses = \Illuminate\Support\Arr::toCssClasses([
        'filament-dropdown-list-item-detail ml-auto text-xs text-gray-500',
        'group-hover:text-primary-100 group-focus:text-primary-100' => ($color === 'primary' || $color === 'secondary') && $hasHoverAndFocusState,
        'group-hover:text-danger-100 group-focus:text-danger-100' => $color === 'danger' && $hasHoverAndFocusState,
        'group-hover:text-success-100 group-focus:text-success-100' => $color === 'success' && $hasHoverAndFocusState,
        'group-hover:text-warning-100 group-focus:text-warning-100' => $color === 'warning' && $hasHoverAndFocusState,
    ]);

    $labelClasses = 'filament-dropdown-list-item-label w-full truncate text-start';

    $iconClasses = \Illuminate\Support\Arr::toCssClasses([
        'filament-dropdown-list-item-icon mr-2 h-5 w-5 rtl:ml-2 rtl:mr-0',
        'group-hover:text-white group-focus:text-white' => $hasHoverAndFocusState,
        'text-primary-500' => $color === 'primary',
        'text-danger-500' => $color === 'danger',
        'text-gray-500' => $color === 'secondary',
        'text-success-500' => $color === 'success',
        'text-warning-500' => $color === 'warning',
    ]);

    $wireTarget = $attributes->whereStartsWith(['wire:target', 'wire:click'])->first();

    $hasLoadingIndicator = filled($wireTarget);

    if ($hasLoadingIndicator) {
        $loadingIndicatorTarget = html_entity_decode($wireTarget, ENT_QUOTES);
    }
@endphp

@if ($tag === 'button')
    <button
        type="{{ $type }}"
        wire:loading.attr="disabled"
        {!! $hasLoadingIndicator ? 'wire:loading.class.delay="opacity-70 cursor-wait"' : '' !!}
        {!! ($hasLoadingIndicator && $loadingIndicatorTarget) ? "wire:target=\"{$loadingIndicatorTarget}\"" : '' !!}
        {{ $attributes->class([$buttonClasses]) }}
    >
        @if ($icon)
            @if ($icon === 'heroicon-s-logout')
                <svg xmlns="http://www.w3.org/2000/svg" 
                    class="{{ $iconClasses }}"
                    @if($hasLoadingIndicator) wire:loading.remove.delay wire:target="{{ $loadingIndicatorTarget }}" @endif
                    viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H3zm10 1a1 1 0 011 1v6a1 1 0 11-2 0V5a1 1 0 011-1zm-6 0a1 1 0 011 1v6a1 1 0 11-2 0V5a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
            @elseif ($icon === 'heroicon-s-trash')
                <svg xmlns="http://www.w3.org/2000/svg" 
                    class="{{ $iconClasses }}"
                    @if($hasLoadingIndicator) wire:loading.remove.delay wire:target="{{ $loadingIndicatorTarget }}" @endif
                    viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            @elseif ($icon === 'heroicon-s-pencil')
                <svg xmlns="http://www.w3.org/2000/svg" 
                    class="{{ $iconClasses }}"
                    @if($hasLoadingIndicator) wire:loading.remove.delay wire:target="{{ $loadingIndicatorTarget }}" @endif
                    viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
            @elseif ($icon === 'heroicon-s-paper-clip')
                <svg xmlns="http://www.w3.org/2000/svg" 
                    class="{{ $iconClasses }}"
                    @if($hasLoadingIndicator) wire:loading.remove.delay wire:target="{{ $loadingIndicatorTarget }}" @endif
                    viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd" />
                </svg>
            @elseif ($icon === 'heroicon-s-document-text')
                <svg xmlns="http://www.w3.org/2000/svg" 
                    class="{{ $iconClasses }}"
                    @if($hasLoadingIndicator) wire:loading.remove.delay wire:target="{{ $loadingIndicatorTarget }}" @endif
                    viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                </svg>
            @else
                <x-dynamic-component
                    :component="$icon"
                    :wire:loading.remove.delay="$hasLoadingIndicator"
                    :wire:target="$hasLoadingIndicator ? $loadingIndicatorTarget : false"
                    :class="$iconClasses"
                />
            @endif
        @endif

        @if ($hasLoadingIndicator)
            <x-filament-support::loading-indicator
                x-cloak
                wire:loading.delay
                :wire:target="$loadingIndicatorTarget"
                :class="$iconClasses"
            />
        @endif

        <span class="{{ $labelClasses }}">
            {{ $slot }}
        </span>

        @if ($detail)
            <span class="{{ $detailClasses }}">
                {{ $detail }}
            </span>
        @endif
    </button>
@elseif ($tag === 'a')
    <a {{ $attributes->class([$buttonClasses]) }}>
        @if ($icon)
            @if ($icon === 'heroicon-s-logout')
                <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconClasses }}" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H3zm10 1a1 1 0 011 1v6a1 1 0 11-2 0V5a1 1 0 011-1zm-6 0a1 1 0 011 1v6a1 1 0 11-2 0V5a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
            @elseif ($icon === 'heroicon-s-trash')
                <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconClasses }}" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            @elseif ($icon === 'heroicon-s-pencil')
                <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconClasses }}" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
            @else
                <x-dynamic-component :component="$icon" :class="$iconClasses" />
            @endif
        @endif

        <span class="{{ $labelClasses }}">
            {{ $slot }}
        </span>

        @if ($detail)
            <span class="{{ $detailClasses }}">
                {{ $detail }}
            </span>
        @endif
    </a>
@elseif ($tag === 'form')
    <form
        {{ $attributes->only(['action', 'class', 'method', 'wire:submit.prevent']) }}
    >
        @csrf

        <button
            type="submit"
            {{ $attributes->except(['action', 'class', 'method', 'wire:submit.prevent'])->class([$buttonClasses]) }}
        >
            @if ($icon)
                @if ($icon === 'heroicon-s-logout')
                    <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconClasses }}" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H3zm10 1a1 1 0 011 1v6a1 1 0 11-2 0V5a1 1 0 011-1zm-6 0a1 1 0 011 1v6a1 1 0 11-2 0V5a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                @elseif ($icon === 'heroicon-s-trash')
                    <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconClasses }}" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                @elseif ($icon === 'heroicon-s-pencil')
                    <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconClasses }}" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                @else
                    <x-dynamic-component :component="$icon" :class="$iconClasses" />
                @endif
            @endif

            <span class="{{ $labelClasses }}">
                {{ $slot }}
            </span>

            @if ($detail)
                <span class="{{ $detailClasses }}">
                    {{ $detail }}
                </span>
            @endif
        </button>
    </form>
@endif
