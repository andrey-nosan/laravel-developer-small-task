@component('mail::message')
# {{ __('Hello, ') }}{{ $name ?? 'user' }}

{{ $body_content ?? '' }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
