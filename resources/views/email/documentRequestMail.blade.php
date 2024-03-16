@component('mail::message')
# {{ $mailData['title'] }}

Hi {{ $mailData['name'] }},

{{ $mailData['message'] }}

@component('mail::panel')
**Tracking ID:** {{ $mailData['tracking_id'] }}
@endcomponent

@component('mail::button', ['url' => $mailData['link']])
View Your Request
@endcomponent

Sincerely,<br>
[PedroAID](https://pedroaid.com/)

@endcomponent