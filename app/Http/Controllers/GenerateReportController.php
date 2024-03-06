<?php

namespace App\Http\Controllers;

use App\Models\AffidavitOfGuardianship;
use App\Models\AffidavitOfLoss;
use App\Models\AffidavitOfNoFixIncome;
use App\Models\AffidavitOfNoIncome;
use App\Models\Appointment;
use App\Models\AppointmentMessage;
use App\Models\DeedOfDonation;
use App\Models\DeedOfSale;
use App\Models\DocumentRequest;
use App\Models\DocumentRequestMessage;
use App\Models\ExtraJudicial;
use App\Models\Feedback;
use App\Models\Inquiry;
use App\Models\InquiryMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class GenerateReportController extends Controller
{
    public function generateAppointmentReport(Request $request, $id) {
        $appointment = Appointment::where('appointment_id', $id)->first();
        $messages = AppointmentMessage::where('appointment_id', $id)->get();
        $feedback = Feedback::where('transaction_id', $id)->where('transaction_type', 'Appointment')->first();
        $rating = $feedback ? $feedback->rating : '';
        $comment = $feedback ? $feedback->comment : '';
        $staffName = Auth::user()->name;

        $pdf = PDF::loadView('pdf.appointmentDetails', compact('appointment', 'messages', 'staffName', 'rating', 'comment'));
        
        return $pdf->download('Appointment_Report_' . $id . '.pdf');
    }

    public function generateDocumentRequestReport(Request $request, $id)
    {
        $documentRequest = DocumentRequest::where('documentRequest_id', $id)->first();
        $messages = DocumentRequestMessage::where('documentRequest_id', $id)->get();
        $feedback = Feedback::where('transaction_id', $id)->where('transaction_type', 'Document Request')->get();
        $rating = '';
        $comment = '';
        if($feedback->count() > 0) {
            $feedback = Feedback::where('transaction_id', $id)->where('transaction_type', 'Document Request')->get()->first();
            $rating = $feedback->rating;
            $comment = $feedback->comment;
        }    
        $staffName = Auth::user()->name;

        if($documentRequest->document_type == 'Affidavit of Loss') {
            $additional_info = AffidavitOfLoss::where('documentRequest_id', $id)->get()->first();
        } else if ($documentRequest->document_type == 'Affidavit of Guardianship') {
            $additional_info = AffidavitOfGuardianship::where('documentRequest_id', $id)->get()->first();
        } else if ($documentRequest->document_type == 'Affidavit of No income') {
            $additional_info = AffidavitOfNoIncome::where('documentRequest_id', $id)->get()->first();
        } else if ($documentRequest->document_type == 'Affidavit of No fix income') {
            $additional_info = AffidavitOfNoFixIncome::where('documentRequest_id', $id)->get()->first();
        } else if ($documentRequest->document_type == 'Extra Judicial') {
            $additional_info = ExtraJudicial::where('documentRequest_id', $id)->get()->first();
        } else if ($documentRequest->document_type == 'Deed of Sale') {
            $additional_info = DeedOfSale::where('documentRequest_id', $id)->get()->first();
        } else if ($documentRequest->document_type == 'Deed of Donation') {
            $additional_info = DeedOfDonation::where('documentRequest_id', $id)->get()->first();
        }

        $pdf = PDF::loadView('pdf.documentRequestDetails', compact('documentRequest', 'messages', 'staffName', 'rating', 'comment', 'additional_info'));
        return $pdf->download('Document_Request_Report_' . $id . '.pdf');
    }

    public function generateInquiryReport(Request $request, $id)
    {
        $inquiry = Inquiry::where('inquiry_id', $id)->first();
        $messages = InquiryMessage::where('inquiry_id', $id)->get();
        $staffName = Auth::user()->name;

        $pdf = PDF::loadView('pdf.inquiryDetails', compact('inquiry', 'messages', 'staffName'));
        return $pdf->download('Inquiry_Report_' . $id . '.pdf');
    }
}
