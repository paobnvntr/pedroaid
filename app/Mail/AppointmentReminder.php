<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment; // Import the Appointment model

class AppointmentReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment; // Define public property to hold appointment data

    /**
     * Create a new message instance.
     *
     * @param Appointment $appointment The appointment instance
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('[#' . $this->appointment->appointment_id . '] Appointment: Reminder for ' . $this->appointment->name)
                    ->with(['appointment' => $this->appointment])
                    ->markdown('email.appointmentReminder');
    }
}