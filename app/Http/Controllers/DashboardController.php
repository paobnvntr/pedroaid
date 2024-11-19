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
        $booked_appointments = Appointment::whereIn('appointment_status', ['Booked', 'Rescheduled'])->count();
        $cancelled_appointments = Appointment::where('appointment_status', 'Cancelled')->count();
        $finished_appointments = Appointment::where('appointment_status', 'Finished')->count();

        $todays_appointments = Appointment::where('appointment_date', now('Asia/Manila')->format('Y-m-d'))
                                            ->whereIn('appointment_status', ['Booked', 'Rescheduled'])
                                            ->orderBy('appointment_time', 'ASC')
                                            ->get();

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

    public function getServicesCountData() {
        $appointmentsData = array_fill(1, 12, 0);
        $inquiriesData = array_fill(1, 12, 0);
        $documentRequestsData = array_fill(1, 12, 0);

        $appointments = Appointment::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $inquiries = Inquiry::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $documentRequests = DocumentRequest::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        foreach ($appointments as $appointment) {
            $month = $appointment->month;
            $count = $appointment->count;
            $appointmentsData[$month] = $count;
        }
    
        foreach ($inquiries as $inquiry) {
            $month = $inquiry->month;
            $count = $inquiry->count;
            $inquiriesData[$month] = $count;
        }
    
        foreach ($documentRequests as $documentRequest) {
            $month = $documentRequest->month;
            $count = $documentRequest->count;
            $documentRequestsData[$month] = $count;
        }

        return response()->json([
            'appointmentsData' => $appointmentsData,
            'inquiriesData' => $inquiriesData,
            'documentRequestsData' => $documentRequestsData,
        ]);
    }
    
    public function getDocumentTypeCountData() {
        $affidavitOfLossData = array_fill(1, 12, 0);
        $affidavitOfGuardianshipData = array_fill(1, 12, 0);
        $affidavitOfNoIncomeData = array_fill(1, 12, 0);
        $affidavitOfNoFixIncomeData = array_fill(1, 12, 0);
        $extraJudicialData = array_fill(1, 12, 0);
        $deedOfSaleData = array_fill(1, 12, 0);
        $deedOfDonationData = array_fill(1, 12, 0);
        $otherDocumentData = array_fill(1, 12, 0);

        $this->fetchDocumentTypeData($affidavitOfLossData, 'Affidavit of Loss');
        $this->fetchDocumentTypeData($affidavitOfGuardianshipData, 'Affidavit of Guardianship');
        $this->fetchDocumentTypeData($affidavitOfNoIncomeData, 'Affidavit of No Income');
        $this->fetchDocumentTypeData($affidavitOfNoFixIncomeData, 'Affidavit of No Fixed Income');
        $this->fetchDocumentTypeData($extraJudicialData, 'Extra Judicial');
        $this->fetchDocumentTypeData($deedOfSaleData, 'Deed of Sale');
        $this->fetchDocumentTypeData($deedOfDonationData, 'Deed of Donation');
        $this->fetchDocumentTypeData($otherDocumentData, 'Other Document');

        return response()->json([
            'affidavitOfLossData' => $affidavitOfLossData,
            'affidavitOfGuardianshipData' => $affidavitOfGuardianshipData,
            'affidavitOfNoIncomeData' => $affidavitOfNoIncomeData,
            'affidavitOfNoFixIncomeData' => $affidavitOfNoFixIncomeData,
            'extraJudicialData' => $extraJudicialData,
            'deedOfSaleData' => $deedOfSaleData,
            'deedOfDonationData' => $deedOfDonationData,
            'otherDocumentData' => $otherDocumentData,
        ]);
    }
    
    private function fetchDocumentTypeData(&$dataArray, $documentType) {
        $documentTypeData = DocumentRequest::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->where('document_type', $documentType)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        foreach ($documentTypeData as $data) {
            $month = $data->month;
            $count = $data->count;
            $dataArray[$month] = $count;
        }
    }    
}