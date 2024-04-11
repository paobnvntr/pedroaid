@component('mail::message')
# Appointment Reminder

Dear {{ $appointment->name }},

This is a reminder that your appointment is scheduled for tomorrow: <br>
**Date:** {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y (l)') }} <br>
**Time:** {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}

Please make sure to arrive on time tomorrow.

Sincerely,<br>
[PedroAID](https://pedroaid.com/)

<div style="font-size: 12px; text-align:center;">
    3F New City Hall Bldg., Brgy. Poblacion, San Pedro City, Laguna
</div>

@endcomponent