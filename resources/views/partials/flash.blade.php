@php
    $alertColorError ??= 'red';
    $alertColorInfo ??= 'blue';
    $alertColorSuccess ??= 'green';
    $alertColorWarning ??= 'yellow';
    $alertStyles ??= 'font-bold mb-2';
@endphp
@inject('ui', 'ReliqArts\Helper\UI')

<div class="flash-messages flash-notifications">
    @if (isset($errors) && count($errors->all()) > 0)
        @include('reliqarts-common::partials.alert', [
            'alertColor' => $alertColorError,
            'alertMessage' => __('There are errors present in the form(s) below. Please address them and try again.'),
        ])
    @endif
    @includeWhen(
        ($message = Session::get('error')),
        'reliqarts-common::partials.alert',
        array_merge(
            ['alertColor' => $alertColorError],
            is_array($message)
                ? ['alertContent' => $ui->getMessageList(...$message)]
                : ['alertMessage' => $message]
        )
    )
    @includeWhen(
        ($message = Session::get('warning'))
            || ($message = Session::get('warnings')),
        'reliqarts-common::partials.alert',
        array_merge(
            ['alertColor' => $alertColorWarning],
            is_array($message)
                ? ['alertContent' => $ui->getMessageList(...$message)]
                : ['alertMessage' => $message]
        )
    )
    @includeWhen(
        ($message = Session::get('success'))
            || ($message = Session::get('successes')),
        'reliqarts-common::partials.alert',
        array_merge(
            ['alertColor' => $alertColorSuccess],
            is_array($message)
                ? ['alertContent' => $ui->getMessageList(...$message)]
                : ['alertMessage' => $message]
        )
    )
    @includeWhen(
        ($message = Session::get('status'))
            || ($message = Session::get('info'))
            || ($message = Session::get('message'))
            || ($message =  Session::get('notice'))
            || ($message =  Session::get('statuses'))
            || ($message =  Session::get('infos'))
            || ($message =  Session::get('messages'))
            || ($message =  Session::get('notices')),
        'reliqarts-common::partials.alert',
        array_merge(
            ['alertColor' => $alertColorInfo],
            is_array($message)
                ? ['alertContent' => $ui->getMessageList(...$message)]
                : ['alertMessage' => $message]
        )
    )
</div>