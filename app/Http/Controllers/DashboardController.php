<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use App\Models\DocumentRequest;
use App\Models\Appointment;
use App\Models\Inquiry;
use App\Models\Logs;
use App\Models\Ordinances;
use App\Models\User;
use App\Models\Feedback;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $committees = Committee::count();
        $ordinances = Ordinances::count();

        $pending_appointments = Appointment::where('appointment_status', 'Pending')->count();
        $booked_appointments = Appointment::where('appointment_status', 'Booked')->count();
        $cancelled_appointments = Appointment::where('appointment_status', 'Cancelled')->count();
        $finished_appointments = Appointment::where('appointment_status', 'Finished')->count();

        $todays_appointments = Appointment::where('appointment_date', now('Asia/Manila')->format('Y-m-d'))->where('appointment_status', 'Booked')->orderBy('appointment_time', 'ASC')->get();

        $unanswered_inquiries = Inquiry::where('status', 'Unanswered')->take(4)->orderBy('created_at', 'ASC')->get();
        $answered_inquiries = Inquiry::where('status', 'Answered')->take(4)->orderBy('created_at', 'ASC')->get();

        $pending_requests = DocumentRequest::where('documentRequest_status', 'Pending')->count();
        $onhold_requests = DocumentRequest::where('documentRequest_status', 'On Hold')->count();
        $claimed_requests = DocumentRequest::where('documentRequest_status', 'Claimed')->count();
        $unclaimed_requests = DocumentRequest::where('documentRequest_status', 'Unclaimed')->count();

        $processing_requests = DocumentRequest::where('documentRequest_status', 'Processing')->take(4)->orderBy('created_at', 'ASC')->get();
        $toclaim_requests = DocumentRequest::where('documentRequest_status', 'To Claim')->take(4)->orderBy('created_at', 'ASC')->get();

        $staff = User::where('level', 'Staff')->count();
        $admin = User::where('level', 'Admin')->count();
        $super_admin = User::where('level', 'Super Admin')->count();

        $logs = Logs::take(4)->orderBy('created_at', 'DESC')->get();

        return view('dashboard', compact('committees', 'ordinances', 'pending_appointments', 'booked_appointments', 'cancelled_appointments', 'finished_appointments', 'todays_appointments', 'pending_requests', 'onhold_requests', 'claimed_requests', 'unclaimed_requests', 'processing_requests', 'toclaim_requests', 'staff', 'admin', 'super_admin', 'logs', 'unanswered_inquiries', 'answered_inquiries'));
    }

    public function getAppointmentFeedbackData() {
        $feedbackData = Feedback::where('transaction_type', 'Appointment')->select('rating', DB::raw('count(*) as count'))
                                ->groupBy('rating')
                                ->get();
    
        return response()->json($feedbackData);
    }

    public function getDocumentRequestFeedbackData() {
        $feedbackData = Feedback::where('transaction_type', 'Document Request')->select('rating', DB::raw('count(*) as count'))
                                ->groupBy('rating')
                                ->get();
    
        return response()->json($feedbackData);
    }
}
