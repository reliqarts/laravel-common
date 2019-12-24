@php
    $fieldType = $fieldType ?? 'text';
    $fieldName = $fieldType === 'captcha' ? 'g-recaptcha-response' : ($fieldName ?? 'Field');
    $fieldIsRequired = $fieldIsRequired ?? $required ?? false;
    $fieldLabel = $fieldLabel
        ?? $label
        ?? ($fieldType === 'captcha' ? 'Are you human?' : \Illuminate\Support\Str::title($fieldName));
    $fieldAttributes = [sprintf('name="%s"', $fieldName)];
    $fieldHasError = isset($errors) && $errors->has($fieldName);
    $fieldAutoFocus = $fieldAutoFocus ?? $autofocus ?? false;
    $fieldPlaceholder = $fieldPlaceholder ?? $placeholder ?? false;
    $fieldContent = $fieldContent ?? false;
    $fieldAutocomplete = $fieldAutocomplete ?? $autocomplete ?? false;
    $fieldValue = $fieldValue ?? $value ?? false;

    if ($fieldType !== 'password') {
        $fieldAttributes[] = sprintf('value="%s"', $fieldValue ?: e(old($fieldName)));
    }

    if ($fieldPlaceholder) {
        $fieldAttributes[] = sprintf('placeholder="%s"', $fieldPlaceholder);
    }

    if ($fieldAutocomplete || $fieldType === 'email') {
        $fieldAttributes[] = sprintf('autocomplete="%s"', $fieldAutocomplete ?: $fieldType);
    }

    if ($fieldIsRequired) {
        $fieldAttributes[] = 'required';
    }

    if ($fieldAutoFocus) {
        $fieldAttributes[] = 'autofocus';
    }
@endphp
@if($fieldType === 'hidden')
    <input id="{{ $fieldName }}" name="{{ $fieldName }}" type="hidden" {!! implode(' ', $fieldAttributes) !!} />
@else
    <div class="group my-4">
        @if($fieldType === 'checkbox')
            <label for="{{ $fieldName }}" class="block mt-2 text-sm font-bold">
                <input id="{{ $fieldName }}" name="{{ $fieldName }}" class="mr-2 align-middle inline-block"
                       type="checkbox" {{ (($$fieldName ??= false) || old($fieldName)) ? 'checked' : '' }} />
                <span class="align-middle inline-block">{{ __($fieldLabel) }}</span>
            </label>
        @else
            <label for="{{ $fieldName }}" class="block mt-2 mb-1 text-sm font-bold">{{ __($fieldLabel) }}</label>
            @if($fieldType === 'captcha')
                @include('reliqarts-common::partials.nocaptcha')
            @else
                <input id="{{ $fieldName }}" type="{{ $fieldType }}"
                       class="block bg-white border-2 rounded p-2 w-full focus:outline-none{{ $fieldHasError
               ? ' text-red-500 border-red-700 bg-red-light hover:border-red-800 shadow-inner rounded-b-none'
               : ' hover:border-teal-600 focus:border-teal-600 focus:bg-teal-100' }}" {!! implode(' ', $fieldAttributes) !!}>
            @endif
        @endif
        @if($fieldHasError)
            @include('reliqarts-common::partials.alert', ['color' => 'red', 'message' => $errors->first($fieldName)])
        @endif
    </div>
@endif
