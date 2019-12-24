@php
    $alertColor = $alertColor ?? $color ?? 'teal';
    $alertWithIcon = $alertWithIcon ?? $withIcon ?? false;
    $alertIcon ??= '<path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 '
        . '4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>';
    $alertContent ??= false;
    $alertTitle ??= false;
    $alertMessage = $alertMessage ?? $message ?? 'No message passed into alert component!';
    $alertStyles ??= 'border-t-4 rounded-b';
@endphp
<div role="alert"
     class="bg-{{ $alertColor }}-100 border border-{{ $alertColor }}-700 text-{{ $alertColor }}-700 px-4 py-2 {{ $alertStyles }}">
    <div class="flex items-center">
        @if($alertWithIcon)
            <div class="py-1">
                <svg class="fill-current h-6 w-6 text-{{ $alertColor }} mr-4" xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 20 20">
                    {!! $alertIcon !!}
                </svg>
            </div>
        @endif
        <div>
            @if($alertContent)
                {!! $alertContent !!}
            @else
                @if($alertTitle)<p class="font-bold">{{ $alertTitle }}</p>@endif
                <p class="text-sm">{{ $alertMessage }}</p>
            @endIf
        </div>
    </div>
</div>
