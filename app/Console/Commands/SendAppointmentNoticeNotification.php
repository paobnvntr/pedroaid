<?php

namespace App\Console\Commands;

use App\Mail\AppointmentReminder;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAppointmentNoticeNotification extends Command
{
    protected $signature = 'send:appointment-reminders';
    protected $description = 'Send appointment reminders for appointments scheduled for the next day';

    public function handle()
    {
        $reminderDate = Carbon::tomorrow();

        // Query database for appointments scheduled for the next day
        $appointments = Appointment::whereDate('appointment_date', $reminderDate->toDateString())
                        ->whereIn('appointment_status', ['Booked', 'Rescheduled'])
                        ->get();

        foreach ($appointments as $appointment) {
            // Send email reminder
            Mail::to($appointment->email)->send(new AppointmentReminder($appointment));
        }

        $this->info('Appointment reminders sent successfully for ' . $reminderDate->toDateString());
    }
}
