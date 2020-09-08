@php
    $fieldType = $fieldType ?? 'text';
    $fieldName = $fieldType === 'captcha' ? 'g-recaptcha-response' : ($fieldName ?? 'Field');
    $fieldErrorName = $fieldErrorName ?? $fieldName;
    $fieldId = $fieldId ?? $fieldName;
    $fieldIsRequired = $fieldIsRequired ?? $required ?? false;
    $fieldEmptyOptionTitle = $selectEmptyOptionTitle ?? '---';
    $fieldLabel = $fieldLabel
        ?? $label
        ?? ($fieldType === 'captcha' ? 'Are you human?' : \Illuminate\Support\Str::title($fieldName));
    $fieldAttributes = [sprintf('name="%s"', $fieldName), ...$fieldAttributes ?? $attributes ?? []];
    $fieldHasError = isset($errors) && $errors->has($fieldErrorName);
    $fieldAutoFocus = $fieldAutoFocus ?? $autofocus ?? false;
    $fieldPlaceholder = $fieldPlaceholder ?? $placeholder ?? false;
    $fieldContent ??= false;
    $fieldAutocomplete = $fieldAutocomplete ?? $autocomplete ?? false;
    $fieldValue = $fieldValue ?? $value ?? false;
    $fieldIsTextArea = in_array($fieldType, ['textarea', 'longtext', 'long']);
    $fieldOptions = $fieldOptions ?? $options ?? [];
    $fieldClasses ??= [];
    $fieldLabelClasses ??= [];
    $fieldWrapperClasses ??= [];

    // pre-imploded classes
    $errorDefaultFieldClassesImploded = 'text-red-500 border-red-700 bg-red-light hover:border-red-800 shadow-inner rounded-b-none';
    $nonErrorDefaultFieldClassesImploded = 'hover:border-teal-600 focus:border-teal-600 focus:bg-teal-100';
    $defaultFieldClassesImploded = sprintf(
        'block bg-white border-2 rounded p-2 w-full focus:outline-none %s',
        $fieldHasError ? $errorDefaultFieldClassesImploded : $nonErrorDefaultFieldClassesImploded
    );

    if (!($fieldIsTextArea || $fieldType === 'password')) {
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
        $fieldWrapperClasses[] = 'required';
    }

    if ($fieldAutoFocus) {
        $fieldAttributes[] = 'autofocus';
    }

    if ($fieldType === 'select') {
        array_unshift($fieldWrapperClasses, 'w-64');
    }
@endphp
@if($fieldType === 'hidden')
    <input id="{{ $fieldId }}" name="{{ $fieldName }}" type="hidden" {!! implode(' ', $fieldAttributes) !!} />
@else
    <div id="field-group-{{ $fieldId }}" class="group my-4 {{ implode(' ', $fieldWrapperClasses) }}">
        @if($fieldType === 'checkbox')
            <label for="{{ $fieldName }}" class="block mt-2 text-sm font-bold {{ implode(' ', $fieldLabelClasses) }}">
                <input id="{{ $fieldId }}" name="{{ $fieldName }}" class="mr-2 align-middle inline-block {{ implode(' ', $fieldClasses) }}"
                       type="checkbox" {{ (($$fieldName ??= false) || old($fieldName)) ? 'checked' : '' }} />
                <span class="align-middle inline-block">{{ __($fieldLabel) }}</span>
            </label>
        @else
            <label for="{{ $fieldName }}" class="block mt-2 mb-1 text-sm font-bold {{ implode(' ', $fieldLabelClasses) }}">{{ __($fieldLabel) }}</label>
            @if(!empty($fieldContent))
                {!! $fieldContent !!}
            @elseif($fieldType === 'captcha')
                @include('reliqarts-common::partials.nocaptcha')
            @elseif($fieldType === 'select')
                <div class="inline-block relative w-full">
                    <select id="{{ $fieldId }}"
                            class="{{ $defaultFieldClassesImploded }} px-4 py-2 pr-8 appearance-none leading-tight {{ implode(' ', $fieldClasses) }}" {!! implode(' ', $fieldAttributes) !!}>
                        @if(!$fieldIsRequired)
                            <option value="" {{ empty($fieldValue) ? 'selected' : '' }}>{{ $fieldEmptyOptionTitle }}</option>
                        @endif
                        @foreach ($fieldOptions as $optionValue => $optionText)
                            <option value="{{ $optionValue }}" {{ $optionValue === $fieldValue ? 'selected' : '' }}>{{ $optionText }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                        </svg>
                    </div>
                </div>
            @elseif($fieldIsTextArea)
                <textarea id="{{ $fieldId }}"
                          class="{{ $defaultFieldClassesImploded }} {{ implode(' ', $fieldClasses) }}"
                          {!! implode(' ', $fieldAttributes) !!}>{!! $fieldValue ?? '' !!}</textarea>
            @else
                <input id="{{ $fieldId }}" type="{{ $fieldType }}"
                       class="{{ $defaultFieldClassesImploded }} {{ implode(' ', $fieldClasses) }}"
                        {!! implode(' ', $fieldAttributes) !!} />
            @endif
        @endif
        @if($fieldHasError)
            @include('reliqarts-common::partials.alert', ['color' => 'red', 'message' => $errors->first($fieldErrorName)])
        @endif
    </div>
@endif
