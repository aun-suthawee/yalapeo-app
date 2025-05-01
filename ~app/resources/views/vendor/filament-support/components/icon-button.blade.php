@props([
    'color' => 'primary',
    'darkMode' => false,
    'disabled' => false,
    'form' => null,
    'icon' => null,
    'keyBindings' => null,
    'indicator' => null,
    'label' => null,
    'size' => 'md',
    'tag' => 'button',
    'tooltip' => null,
    'type' => 'button',
])

@php
    $buttonClasses = [
        'filament-icon-button relative flex items-center justify-center rounded-full outline-none hover:bg-gray-500/5 disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-70',
        'text-primary-500 focus:bg-primary-500/10' => $color === 'primary',
        'text-danger-500 focus:bg-danger-500/10' => $color === 'danger',
        'text-gray-500 focus:bg-gray-500/10' => $color === 'secondary',
        'dark:text-gray-400' => $color === 'secondary' && $darkMode,
        'text-success-500 focus:bg-success-500/10' => $color === 'success',
        'text-warning-500 focus:bg-warning-500/10' => $color === 'warning',
        'dark:hover:bg-gray-300/5' => $darkMode,
        'h-10 w-10' => $size === 'md',
        'h-8 w-8' => $size === 'sm',
        'h-8 w-8 md:h-10 md:w-10' => $size === 'sm md:md',
        'h-12 w-12' => $size === 'lg',
    ];

    $iconClasses = \Illuminate\Support\Arr::toCssClasses([
        'filament-icon-button-icon',
        'w-5 h-5' => $size === 'md',
        'w-4 h-4' => $size === 'sm',
        'w-4 h-4 md:w-5 md:h-5' => $size === 'sm md:md',
        'w-6 h-6' => $size === 'lg',
    ]);

    $indicatorClasses = \Illuminate\Support\Arr::toCssClasses([
        'filament-icon-button-indicator absolute rounded-full text-xs inline-block w-4 h-4 -top-0.5 -right-0.5',
        'bg-primary-500/10' => $color === 'primary',
        'bg-danger-500/10' => $color === 'danger',
        'bg-gray-500/10' => $color === 'secondary',
        'bg-success-500/10' => $color === 'success',
        'bg-warning-500/10' => $color === 'warning',
    ]);

    $wireTarget = $attributes->whereStartsWith(['wire:target', 'wire:click'])->first();

    $hasLoadingIndicator = filled($wireTarget) || ($type === 'submit' && filled($form));

    if ($hasLoadingIndicator) {
        $loadingIndicatorTarget = html_entity_decode($wireTarget ?: $form, ENT_QUOTES);
    }
@endphp

@if ($tag === 'button')
    <button
        @if ($keyBindings) x-mousetrap.global.{{ collect($keyBindings)->map(fn(string $keyBinding): string => str_replace('+', '-', $keyBinding))->implode('.') }} @endif
        @if ($label) title="{{ $label }}" @endif
        @if ($tooltip) x-tooltip.raw="{{ $tooltip }}" @endif type="{{ $type }}"
        {!! $disabled ? 'disabled' : '' !!} @if ($keyBindings || $tooltip) x-data="{}" @endif
        {{ $attributes->class($buttonClasses) }}>
        @if ($label)
            <span class="sr-only">
                {{ $label }}
            </span>
        @endif

        @if ($icon)
            @if (Str::startsWith($icon, 'heroicon-o-'))
                @switch(Str::after($icon, 'heroicon-o-'))
                    @case('plus')
                        <svg xmlns="http://www.w3.org/2000/svg"
                            @if ($hasLoadingIndicator) wire:loading.remove.delay wire:target="{{ $loadingIndicatorTarget }}" @endif
                            class="{{ $iconClasses }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    @break

                    @case('pencil')
                        <svg xmlns="http://www.w3.org/2000/svg"
                            @if ($hasLoadingIndicator) wire:loading.remove.delay wire:target="{{ $loadingIndicatorTarget }}" @endif
                            class="{{ $iconClasses }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    @break

                    @case('trash')
                        <svg xmlns="http://www.w3.org/2000/svg"
                            @if ($hasLoadingIndicator) wire:loading.remove.delay wire:target="{{ $loadingIndicatorTarget }}" @endif
                            class="{{ $iconClasses }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    @break

                    @case('view')
                    @case('eye')
                        <svg xmlns="http://www.w3.org/2000/svg"
                            @if ($hasLoadingIndicator) wire:loading.remove.delay wire:target="{{ $loadingIndicatorTarget }}" @endif
                            class="{{ $iconClasses }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    @break

                    @case('document-download')
                        <svg xmlns="http://www.w3.org/2000/svg"
                            @if ($hasLoadingIndicator) wire:loading.remove.delay wire:target="{{ $loadingIndicatorTarget }}" @endif
                            class="{{ $iconClasses }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    @break

                    @case('dots-vertical')
                        <svg xmlns="http://www.w3.org/2000/svg"
                            @if ($hasLoadingIndicator) wire:loading.remove.delay wire:target="{{ $loadingIndicatorTarget }}" @endif
                            class="{{ $iconClasses }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                        </svg>
                    @break

                    @case('x')
                        <svg xmlns="http://www.w3.org/2000/svg"
                            @if ($hasLoadingIndicator) wire:loading.remove.delay wire:target="{{ $loadingIndicatorTarget }}" @endif
                            class="{{ $iconClasses }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    @break

                    @case('filter')
                        <svg xmlns="http://www.w3.org/2000/svg"
                            @if ($hasLoadingIndicator) wire:loading.remove.delay wire:target="{{ $loadingIndicatorTarget }}" @endif
                            class="{{ $iconClasses }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                    @break

                    @default
                        <svg xmlns="http://www.w3.org/2000/svg"
                            @if ($hasLoadingIndicator) wire:loading.remove.delay wire:target="{{ $loadingIndicatorTarget }}" @endif
                            class="{{ $iconClasses }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                @endswitch
            @elseif(Str::startsWith($icon, 'heroicon-s-'))
                @switch(Str::after($icon, 'heroicon-s-'))
                    @case('plus')
                        <svg xmlns="http://www.w3.org/2000/svg"
                            @if ($hasLoadingIndicator) wire:loading.remove.delay wire:target="{{ $loadingIndicatorTarget }}" @endif
                            class="{{ $iconClasses }}" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                    @break

                    @case('pencil')
                        <svg xmlns="http://www.w3.org/2000/svg"
                            @if ($hasLoadingIndicator) wire:loading.remove.delay wire:target="{{ $loadingIndicatorTarget }}" @endif
                            class="{{ $iconClasses }}" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                    @break

                    @case('filter')
                        <svg xmlns="http://www.w3.org/2000/svg"
                            @if ($hasLoadingIndicator) wire:loading.remove.delay wire:target="{{ $loadingIndicatorTarget }}" @endif
                            class="{{ $iconClasses }}" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                clip-rule="evenodd" />
                        </svg>
                    @break

                    @default
                        <svg xmlns="http://www.w3.org/2000/svg"
                            @if ($hasLoadingIndicator) wire:loading.remove.delay wire:target="{{ $loadingIndicatorTarget }}" @endif
                            class="{{ $iconClasses }}" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                @endswitch
            @else
                <svg xmlns="http://www.w3.org/2000/svg"
                    @if ($hasLoadingIndicator) wire:loading.remove.delay wire:target="{{ $loadingIndicatorTarget }}" @endif
                    class="{{ $iconClasses }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            @endif
        @endif

        @if ($hasLoadingIndicator)
            <x-filament-support::loading-indicator x-cloak wire:loading.delay :wire:target="$loadingIndicatorTarget"
                :class="$iconClasses" />
        @endif

        @if ($indicator)
            <span class="{{ $indicatorClasses }}">
                {{ $indicator }}
            </span>
        @endif
    </button>
@elseif ($tag === 'a')
    <a @if ($keyBindings) x-mousetrap.global.{{ collect($keyBindings)->map(fn(string $keyBinding): string => str_replace('+', '-', $keyBinding))->implode('.') }} @endif
        @if ($label) title="{{ $label }}" @endif
        @if ($tooltip) x-tooltip.raw="{{ $tooltip }}" @endif
        @if ($keyBindings || $tooltip) x-data="{}" @endif {{ $attributes->class($buttonClasses) }}>
        @if ($label)
            <span class="sr-only">
                {{ $label }}
            </span>
        @endif

        @if ($icon)
            @if (Str::startsWith($icon, 'heroicon-o-'))
                @switch(Str::after($icon, 'heroicon-o-'))
                    @case('plus')
                        <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconClasses }}" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    @break

                    @case('pencil')
                        <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconClasses }}" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    @break

                    @case('eye')
                        <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconClasses }}" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    @break

                    @case('filter')
                        <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconClasses }}" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                    @break

                    @default
                        <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconClasses }}" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                @endswitch
            @elseif(Str::startsWith($icon, 'heroicon-s-'))
                @switch(Str::after($icon, 'heroicon-s-'))
                    @case('plus')
                        <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconClasses }}" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                    @break

                    @default
                        <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconClasses }}" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                @endswitch
            @else
                <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconClasses }}" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            @endif
        @endif

        @if ($indicator)
            <span class="{{ $indicatorClasses }}">
                {{ $indicator }}
            </span>
        @endif
    </a>
@endif
