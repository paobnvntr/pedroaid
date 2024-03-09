<?php

namespace App\Http\Controllers;

use App\Models\AffidavitOfGuardianship;
use App\Models\AffidavitOfLoss;
use App\Models\AffidavitOfNoFixIncome;
use App\Models\AffidavitOfNoIncome;
use App\Models\DeedOfDonation;
use App\Models\DeedOfSale;
use App\Models\ExtraJudicial;
use App\Models\Feedback;
use App\Models\Heir;
use App\Models\InquiryMessage;
use App\Models\User;
use App\Notifications\NewAppointmentMessage;
use App\Notifications\NewDocumentRequestMessage;
use App\Notifications\NewInquiryMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\AppointmentMail;
use App\Models\Appointment;
use App\Models\AppointmentMessage;
use App\Models\Inquiry;
use App\Models\DocumentRequest;
use App\Models\DocumentRequestMessage;
use App\Mail\DocumentRequestMail;
class TrackerController extends Controller
{
    public function tracker() {
        return view('landing-page.aid-tracker.tracker');
    }

    public function appointmentTracker() {
        return view('landing-page.aid-tracker.appointment.appointment-tracker');
    }

    public function appointmentDetails(request $request) { 
        Validator::make($request->all(), [
            'appointment_id' => ['required', 'regex:/^[A-Z0-9]{3}-[A-Z0-9]{3}-[A-Z0-9]{3}$/'],
            'email' => 'required|email'
        ])->validate();

        $appointment = Appointment::where('appointment_id', $request->appointment_id)->first();
        if(!$appointment) {
            return redirect()->back()->with('failed','Appointment Not Found!')->withInput();
        }else {
            if($appointment->email != $request->email) {
                return redirect()->back()->with('failed', 'Incorrect Email!')->withInput();
            }else {

                session(['appointment_id' => $appointment->appointment_id]);

                return redirect()->route('redirectAppointmentDetails');
            }
        }
    }

    public function redirectAppointmentDetails() {
        $appointment_id = session('appointment_id');

        if (!$appointment_id) {
            // Handle the case where appointment_id is not found in the session
            return redirect()->route('appointmentTracker')->with('failed', 'Appointment Session Destroyed!');
        }

        $appointment = Appointment::where('appointment_id', $appointment_id)->first();
        $messages = AppointmentMessage::where('appointment_id', $appointment_id)->where('email', $appointment->email)->get();

        $feedback = Feedback::where('transaction_id', $appointment_id)->where('transaction_type', 'Appointment')->get();
        $rating = '';
        $comment = '';
        if($feedback->count() > 0) {
            $feedback = Feedback::where('transaction_id', $appointment_id)->where('transaction_type', 'Appointment')->get()->first();
            $rating = $feedback->rating;
            $comment = $feedback->comment;
        }
            
        session()->forget('appointment_id');

        return view('landing-page.aid-tracker.appointment.appointment-details', compact('appointment', 'messages', 'feedback', 'rating', 'comment'));
    }

    public function refreshAppointment(string $id) {
        session(['appointment_id' => $id]);
        return redirect()->route('redirectAppointmentDetails');
    }

    public function appointmentSendMessage(Request $request, string $id) {
        $validator = $this->validateMessageRequest($request);
    
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    
        $appointment = $this->getAppointmentById($id);
        $appointment_email = $appointment->email;
        $message = $request->message;
    
        $this->saveAppointmentMessage($id, $appointment_email, $message);
    
        $this->updateAppointmentTimestamp($id);

        $staff = User::where('transaction_level', 'Appointment')->get();
        $admins = User::where('level', 'Admin')->get();
        $superAdmins = User::where('level', 'Super Admin')->get();
        
        $notificationData = [
            'appointment_id' => $id,
            'name' => $appointment->name,
        ];

        foreach ($staff as $user) {
            $user->notify(new NewAppointmentMessage($notificationData));
        }

        foreach ($admins as $admin) {
            $admin->notify(new NewAppointmentMessage($notificationData));
        }

        foreach ($superAdmins as $superAdmin) {
            $superAdmin->notify(new NewAppointmentMessage($notificationData));
        }

        session(['appointment_id' => $id]);
    
        return redirect()->route('redirectAppointmentDetails')->with('success', 'Message Sent!');
    }
    
    private function validateMessageRequest(Request $request) {
        return Validator::make($request->all(), [
            '_token' => 'required',
            'message' => 'required',
        ]);
    }
    
    private function getAppointmentById(string $id) {
        return Appointment::where('appointment_id', $id)->firstOrFail();
    }
    
    private function saveAppointmentMessage(string $id, string $appointment_email, string $message) {
        AppointmentMessage::create([
            'appointment_id' => $id,
            'email' => $appointment_email,
            'message' => $message,
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }
    
    private function updateAppointmentTimestamp(string $id) {
        Appointment::where('appointment_id', $id)->update([
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    public function rescheduleAppointment(string $id) {
        session(['appointment_id' => $id]);
        return redirect()->route('appointmentRescheduleForm');
    }

    public function refreshReschedule(string $id) {
        session(['appointment_id' => $id]);
        return redirect()->route('appointmentRescheduleForm');
    }

    public function appointmentRescheduleForm() {
        $appointment_id = session('appointment_id');
        $appointment = Appointment::where('appointment_id', $appointment_id)->first();

        if (!$appointment_id) {
            return redirect()->route('appointmentTracker')->with('failed', 'Appointment Session Destroyed!');
        }

        session()->forget('appointment_id');

        return view('landing-page.aid-tracker.appointment.appointment-reschedule', compact('appointment'));
    }

    public function validateReschedule(Request $request, string $id) {
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'appointment_date' => 'required',
            'appointment_time' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        }else {
            $selectedDate = $request->appointment_date;
            $timeslot = $request->appointment_time;

            $isTimeslotAvailable = Appointment::where('appointment_date', $selectedDate)
                                            ->where('appointment_time', $timeslot)
                                            ->whereIn('appointment_status', ['Pending', 'Booked', 'Rescheduled'])
                                            ->doesntExist();

            if($isTimeslotAvailable) {
                return response()->json(['message' => 'Validation passed']);
            } else {
                return response()->json(['message' => 'Validation failed', 'errors' => ['appointment_time' => ['The selected timeslot is not available']]]);
            }
        }
    }

    public function appointmentReschedule(Request $request, string $id) {
        $selectedDate = $request->appointment_date;
        $timeslot = $request->appointment_time;

        Appointment::where('appointment_id', $id)->update([
            'appointment_date' => $selectedDate,
            'appointment_time' => $timeslot,
            'appointment_status' => 'Rescheduled',
            'updated_at' => Carbon::now('Asia/Manila'),
        ]);

        $appointment = Appointment::where('appointment_id', $id)->first();
        $appointment_email = $appointment->email;

        $mailData = [
            'title' => 'Mail from PedroAID',
            'name' => $appointment->name,
            'message' => 'Your appointment has been rescheduled.',
            'tracking_id' => $id,
            'link' =>  route('appointmentDetails', ['appointment_id' => $id, 'email' => $appointment_email]),
        ];

        $mailSubject = '[#'. $id . '] Rescheduled Appointment: Appointment from ' . $appointment->name;

        Mail::to($appointment_email)->send(new AppointmentMail($mailData, $mailSubject));

        session(['appointment_id' => $id]);
    
        return redirect()
            ->route('redirectAppointmentDetails')->with('success', 'Appointment Rescheduled Successfully!');
    }

    public function cancelAppointment(string $id) {
        $appointment_id = $id;

        Appointment::where('appointment_id', $id)->update([
            'appointment_status' => 'Cancelled',
            'updated_at' => Carbon::now('Asia/Manila'),
        ]);

        $appointment = Appointment::where('appointment_id', $id)->first();
        $appointment_email = $appointment->email;

        $mailData = [
            'title' => 'Mail from PedroAID',
            'name' => $appointment->name,
            'message' => 'Your appointment has been cancelled.',
            'tracking_id' => $id,
            'link' => route('appointmentDetails', ['appointment_id' => $id, 'email' => $appointment_email]),
        ];

        $mailSubject = '[#'. $id . '] Cancelled Appointment: Appointment from ' . $appointment->name;

        Mail::to($appointment_email)->send(new AppointmentMail($mailData, $mailSubject));

        session(['appointment_id' => $appointment_id]);
    
        return redirect()
            ->route('redirectAppointmentDetails')->with('success', 'Appointment Cancelled Successfully!');
    }

    public function redirectFeedback(string $id) {
        session(['appointment_id' => $id]);
        return redirect()->route('appointmentFeedbackForm');
    }

    public function appointmentFeedbackForm() {
        $appointment_id = session('appointment_id');
        $appointment = Appointment::where('appointment_id', $appointment_id)->first();

        if (!$appointment_id) {
            // Handle the case where appointment_id is not found in the session
            return redirect()->route('appointmentTracker')->with('failed', 'Appointment Session Destroyed!');
        }
        
        session()->forget('appointment_id');
        return view('landing-page.aid-tracker.appointment.appointment-feedback', compact('appointment'));
    }

    public function refreshFeedback(string $id) {
        session(['appointment_id' => $id]);
        return redirect()->route('appointmentFeedbackForm');
    }

    public function validateFeedbackForm(Request $request, string $id, string $type) {
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'rating' => 'required',
            'comment' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        }else {
            if($type == 'Appointment') {
                $appointment = Feedback::where('transaction_type', $type)->where('transaction_id', $id)->doesntExist();

                if($appointment) {
                    return response()->json(['message' => 'Validation passed']);
                } else {
                    return response()->json(['message' => 'Validation failed', 'errors' => ['rating' => ['You have already sent a feedback for this transaction']]]);
                }

            } else if($type == 'Document Request') {
                $documentRequest = Feedback::where('transaction_type', $type)->where('transaction_id', $id)->doesntExist();

                if($documentRequest) {
                    return response()->json(['message' => 'Validation passed']);
                } else {
                    return response()->json(['message' => 'Validation failed', 'errors' => ['rating' => ['You have already sent a feedback for this transaction']]]);
                }
            }
        }
    }

    public function sendFeedback(Request $request, string $id, string $type) {
        if($type == 'Appointment') {
            $appointment = Appointment::where('appointment_id', $id)->first();
            $appointment_email = $appointment->email;

            $feedback = Feedback::create([
                'transaction_id' => $id,
                'transaction_type' => 'Appointment',
                'rating' => $request->rating,
                'comment' => $request->comment,
                'created_at' => now('Asia/Manila'),
                'updated_at' => now('Asia/Manila'),
            ]);

            if($feedback) {
                $mailData = [
                    'title' => 'Mail from PedroAID',
                    'name' => $appointment->name,
                    'message' => 'Thank you for your feedback!',
                    'tracking_id' => $id,
                    'link' => route('appointmentDetails', ['appointment_id' => $id, 'email' => $appointment_email]),
                ];

                $mailSubject = '[#'. $id . '] Feedback Received: Appointment from ' . $appointment->name;
                Mail::to($appointment_email)->send(new AppointmentMail($mailData, $mailSubject));

                session(['appointment_id' => $id]);
                return redirect()->route('appointmentFeedbackForm')->with('success', 'Feedback Sent Successfully!');
            } else {
                session(['appointment_id' => $id]);
                return redirect()->route('appointmentFeedbackForm')->with('failed', 'Feedback Failed to Send!');
            }

        } else if($type == 'Document Request') {
            $documentRequest = DocumentRequest::where('documentRequest_id', $id)->first();
            $documentRequest_email = $documentRequest->email;

            $feedback = Feedback::create([
                'transaction_id' => $id,
                'transaction_type' => 'Document Request',
                'rating' => $request->rating,
                'comment' => $request->comment,
                'created_at' => now('Asia/Manila'),
                'updated_at' => now('Asia/Manila'),
            ]);

            if($feedback) {
                $mailData = [
                    'title' => 'Mail from PedroAID',
                    'name' => $documentRequest->name,
                    'message' => 'Thank you for your feedback!',
                    'tracking_id' => $id,
                    'link' => route('documentRequestDetails', ['documentRequest_id' => $id, 'email' => $documentRequest_email]),
                ];

                $mailSubject = '[#'. $id . '] Feedback Received: Document Request from ' . $documentRequest->name;
                Mail::to($documentRequest_email)->send(new DocumentRequestMail($mailData, $mailSubject));

                session(['documentRequest_id' => $id]);
                return redirect()->route('documentRequestFeedbackForm')->with('success', 'Feedback Sent Successfully!');
            } else {
                session(['documentRequest_id' => $id]);
                return redirect()->route('documentRequestFeedbackForm')->with('failed', 'Feedback Failed to Send!');
            }
        }
    }

    public function redirectEditFeedback(string $id) {
        session(['appointment_id' => $id]);
        return redirect()->route('appointmentEditFeedbackForm');
    }

    public function appointmentEditFeedbackForm() {
        $appointment_id = session('appointment_id');
        $appointment = Appointment::where('appointment_id', $appointment_id)->first();

        if (!$appointment_id) {
            // Handle the case where appointment_id is not found in the session
            return redirect()->route('appointmentTracker')->with('failed', 'Appointment Session Destroyed!');
        }

        $feedback = Feedback::where('transaction_id', $appointment_id)->where('transaction_type', 'Appointment')->first();
        $rating = $feedback->rating;
        $comment = $feedback->comment;

        session()->forget('appointment_id');
        return view('landing-page.aid-tracker.appointment.appointment-edit-feedback', compact('appointment', 'rating', 'comment'));
    }

    public function refreshEditFeedback(string $id) {
        session(['appointment_id' => $id]);
        return redirect()->route('appointmentEditFeedbackForm');
    }

    public function validateEditFeedbackForm(Request $request, string $id) {
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'rating' => 'required',
            'comment' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        }else {
            return response()->json(['message' => 'Validation passed']);
        }
    }

    public function editFeedback(Request $request, string $id, string $type) {
        if($type == 'Appointment') {
            $appointment = Appointment::where('appointment_id', $id)->first();
            $appointment_email = $appointment->email;

            $feedback = Feedback::where('transaction_id', $id)->where('transaction_type', 'Appointment')->first();

            if($feedback->rating != $request->rating || $feedback->comment != $request->comment) {
                $feedback->update([
                    'rating' => $request->rating,
                    'comment' => $request->comment,
                    'updated_at' => now('Asia/Manila'),
                ]);

                if($feedback) {
                    $mailData = [
                        'title' => 'Mail from PedroAID',
                        'name' => $appointment->name,
                        'message' => 'Your feedback has been updated.',
                        'tracking_id' => $id,
                        'link' => route('appointmentDetails', ['appointment_id' => $id, 'email' => $appointment_email]),
                    ];

                    $mailSubject = '[#'. $id . '] Updated Feedback: Appointment from ' . $appointment->name;
                    Mail::to($appointment_email)->send(new AppointmentMail($mailData, $mailSubject));

                    session(['appointment_id' => $id]);
                    return redirect()->route('appointmentEditFeedbackForm')->with('success', 'Feedback Updated Successfully!');
                } else {
                    session(['appointment_id' => $id]);
                    return redirect()->route('appointmentEditFeedbackForm')->with('failed', 'Feedback Failed to Update!');
                }
            } else {
                session(['appointment_id' => $id]);
                return redirect()->route('appointmentEditFeedbackForm')->with('failed', 'Fill Up the Form!');
            }
        } else if($type == 'Document Request') {
            $documentRequest = DocumentRequest::where('documentRequest_id', $id)->first();
            $documentRequest_email = $documentRequest->email;

            $feedback = Feedback::where('transaction_id', $id)->where('transaction_type', 'Document Request')->first();

            $feedback->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
                'updated_at' => now('Asia/Manila'),
            ]);

            if($feedback) {
                $mailData = [
                    'title' => 'Mail from PedroAID',
                    'name' => $documentRequest->name,
                    'message' => 'Your feedback has been updated.',
                    'tracking_id' => $id,
                    'link' => route('documentRequestDetails', ['documentRequest_id' => $id, 'email' => $documentRequest_email]),
                ];

                $mailSubject = '[#'. $id . '] Updated Feedback: Document Request from ' . $documentRequest->name;
                Mail::to($documentRequest_email)->send(new DocumentRequestMail($mailData, $mailSubject));

                session(['documentRequest_id' => $id]);
                return redirect()->route('documentRequestEditFeedbackForm')->with('success', 'Feedback Updated Successfully!');
            } else {
                session(['documentRequest_id' => $id]);
                return redirect()->route('documentRequestEditFeedbackForm')->with('failed', 'Feedback Failed to Update!');
            }
        }
    }

    public function inquiryTracker() {
        return view('landing-page.aid-tracker.inquiry.inquiry-tracker');
    }

    public function inquiryDetails(request $request) { 
        Validator::make($request->all(), [
            'inquiry_id' => ['required', 'regex:/^[A-Z0-9]{3}-[A-Z0-9]{3}-[A-Z0-9]{3}$/'],
            'email' => 'required|email'
        ])->validate();

        $inquiry = Inquiry::where('inquiry_id', $request->inquiry_id)->first();
        if(!$inquiry) {
            return redirect()->back()->with('failed','Inquiry Not Found!')->withInput();
        }else {
            if($inquiry->email != $request->email) {
                return redirect()->back()->with('failed', 'Incorrect Email!')->withInput();
            }else {

                session(['inquiry_id' => $inquiry->inquiry_id]);

                return redirect()->route('redirectInquiryDetails');
            }
        }
    }

    public function redirectInquiryDetails() {
        $inquiry_id = session('inquiry_id');

        if (!$inquiry_id) {
            // Handle the case where appointment_id is not found in the session
            return redirect()->route('inquiryTracker')->with('failed', 'Inquiry Session Destroyed!');
        }

        $inquiry = Inquiry::where('inquiry_id', $inquiry_id)->first();
        $messages = InquiryMessage::where('inquiry_id', $inquiry_id)->where('email', $inquiry->email)->get();
            
        session()->forget('inquiry_id');

        return view('landing-page.aid-tracker.inquiry.inquiry-details', compact('inquiry', 'messages'));
    }

    public function refreshInquiry(string $id) {
        session(['inquiry_id' => $id]);
        return redirect()->route('redirectInquiryDetails');
    }

    public function inquirySendMessage(Request $request, string $id) {
        $validator = $this->validateMessageRequest($request);
    
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    
        $inquiry = $this->getInquiryById($id);
        $inquiry_email = $inquiry->email;
        $message = $request->message;
    
        $this->saveInquiryMessage($id, $inquiry_email, $message);
    
        $this->updateInquiryTimestamp($id);

        $staff = User::where('transaction_level', 'Inquiry')->get();
        $admins = User::where('level', 'Admin')->get();
        $superAdmins = User::where('level', 'Super Admin')->get();
        
        $notificationData = [
            'inquiry_id' => $id,
            'name' => $inquiry->name,
        ];

        foreach ($staff as $user) {
            $user->notify(new NewInquiryMessage($notificationData));
        }

        foreach ($admins as $admin) {
            $admin->notify(new NewInquiryMessage($notificationData));
        }

        foreach ($superAdmins as $superAdmin) {
            $superAdmin->notify(new NewInquiryMessage($notificationData));
        }

        session(['inquiry_id' => $id]);
    
        return redirect()->route('redirectInquiryDetails')->with('success', 'Message Sent!');
    }

    private function getInquiryById(string $id) {
        return Inquiry::where('inquiry_id', $id)->firstOrFail();
    }

    private function saveInquiryMessage(string $id, string $inquiry_email, string $message) {
        InquiryMessage::create([
            'inquiry_id' => $id,
            'email' => $inquiry_email,
            'message' => $message,
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    private function updateInquiryTimestamp(string $id) {
        Inquiry::where('inquiry_id', $id)->update([
            'status' => 'Unanswered',
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    public function documentRequestTracker() {
        return view('landing-page.aid-tracker.document-request.document-request-tracker');
    }

    public function documentRequestDetails(Request $request) { 
        Validator::make($request->all(), [
            'documentRequest_id' => ['required', 'regex:/^[A-Z0-9]{3}-[A-Z0-9]{3}-[A-Z0-9]{3}$/'],
            'email' => 'required|email'
        ])->validate();

        $documentRequest = DocumentRequest::where('documentRequest_id', $request->documentRequest_id)->first();
        if(!$documentRequest) {
            return redirect()->back()->with('failed','Document Request Not Found!')->withInput();
        }else {
            if($documentRequest->email != $request->email) {
                return redirect()->back()->with('failed', 'Incorrect Email!')->withInput();
            }else {

                session(['documentRequest_id' => $documentRequest->documentRequest_id]);

                return redirect()->route('redirectDocumentRequestDetails');
            }
        }
    }

    public function redirectDocumentRequestDetails() {
        $documentRequest_id = session('documentRequest_id');

        if (!$documentRequest_id) {
            return redirect()->route('documentRequestTracker')->with('failed', 'Document Request Session Destroyed!');
        }

        $documentRequest = DocumentRequest::where('documentRequest_id', $documentRequest_id)->first();
        $messages = DocumentRequestMessage::where('documentRequest_id', $documentRequest_id)->where('email', $documentRequest->email)->get();
        $heirs_info = '';

        if($documentRequest->document_type == 'Affidavit of Loss') {
            $additional_info = AffidavitOfLoss::where('documentRequest_id', $documentRequest_id)->get()->first();
        } else if ($documentRequest->document_type == 'Affidavit of Guardianship') {
            $additional_info = AffidavitOfGuardianship::where('documentRequest_id', $documentRequest_id)->get()->first();
        } else if ($documentRequest->document_type == 'Affidavit of No income') {
            $additional_info = AffidavitOfNoIncome::where('documentRequest_id', $documentRequest_id)->get()->first();
        } else if ($documentRequest->document_type == 'Affidavit of No fix income') {
            $additional_info = AffidavitOfNoFixIncome::where('documentRequest_id', $documentRequest_id)->get()->first();
        } else if ($documentRequest->document_type == 'Extra Judicial') {
            $additional_info = ExtraJudicial::where('documentRequest_id', $documentRequest_id)->get()->first();
            if($additional_info->surviving_spouse == null && $additional_info->spouse_valid_id_front == null && $additional_info->spouse_valid_id_back == null) {
                $heirs_info = Heir::where('documentRequest_id', $documentRequest_id)->get();
            }
        } else if ($documentRequest->document_type == 'Deed of Sale') {
            $additional_info = DeedOfSale::where('documentRequest_id', $documentRequest_id)->get()->first();
        } else if ($documentRequest->document_type == 'Deed of Donation') {
            $additional_info = DeedOfDonation::where('documentRequest_id', $documentRequest_id)->get()->first();
        }

        $feedback = Feedback::where('transaction_id', $documentRequest_id)->where('transaction_type', 'Document Request')->get();
        $rating = '';
        $comment = '';
        if($feedback->count() > 0) {
            $feedback = Feedback::where('transaction_id', $documentRequest_id)->where('transaction_type', 'Document Request')->get()->first();
            $rating = $feedback->rating;
            $comment = $feedback->comment;
        }    

        session()->forget('documentRequest_id');

        return view('landing-page.aid-tracker.document-request.documentRequest-details', compact('documentRequest', 'messages', 'feedback', 'rating', 'comment', 'additional_info', 'heirs_info'));
    }

    public function refreshDocumentRequest(string $id) {
        session(['documentRequest_id' => $id]);
        return redirect()->route('redirectDocumentRequestDetails');
    }

    public function documentRequestSendMessage(Request $request, string $id) {
        $validator = $this->validateMessageRequest($request);
    
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator);
        }
    
        $documentRequest = $this->getDocumentRequestById($id);
        $documentRequest_email = $documentRequest->email;
        $message = trim($request->message);
    
        $this->saveDocumentRequestMessage($id, $documentRequest_email, $message);
        $this->updateDocumentRequestTimestamp($id);

        $staff = User::where('transaction_level', 'Document Request')->get();
        $admins = User::where('level', 'Admin')->get();
        $superAdmins = User::where('level', 'Super Admin')->get();
        
        $notificationData = [
            'documentRequest_id' => $id,
            'name' => $documentRequest->name,
        ];

        foreach ($staff as $user) {
            $user->notify(new NewDocumentRequestMessage($notificationData));
        }

        foreach ($admins as $admin) {
            $admin->notify(new NewDocumentRequestMessage($notificationData));
        }

        foreach ($superAdmins as $superAdmin) {
            $superAdmin->notify(new NewDocumentRequestMessage($notificationData));
        }
    
        session(['documentRequest_id' => $id]);
    
        return redirect()
            ->route('redirectDocumentRequestDetails')->with('success', 'Message Sent!');
    }
    
    private function getDocumentRequestById(string $id) {
        return DocumentRequest::where('documentRequest_id', $id)->firstOrFail();
    }
    
    private function saveDocumentRequestMessage(string $id, string $documentRequest_email, string $message) {
        DocumentRequestMessage::create([
            'documentRequest_id' => $id,
            'email' => $documentRequest_email,
            'message' => $message,
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }
    
    private function updateDocumentRequestTimestamp(string $id) {
        DocumentRequest::where('documentRequest_id', $id)->update([
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    public function cancelDocumentRequest(string $id) {
        $documentRequest_id = $id;

        DocumentRequest::where('documentRequest_id', $id)->update([
            'documentRequest_status' => 'Cancelled',
            'updated_at' => Carbon::now('Asia/Manila'),
        ]);

        $documentRequest = DocumentRequest::where('documentRequest_id', $id)->first();
        $documentRequest_email = $documentRequest->email;

        $mailData = [
            'title' => 'Mail from PedroAID',
            'name' => $documentRequest->name,
            'message' => 'Your document request has been cancelled.',
            'tracking_id' => $id,
            'link' => route('documentRequestDetails', ['documentRequest_id' => $id, 'email' => $documentRequest_email]),
        ];

        $mailSubject = '[#'. $id . '] Cancelled Document Request: Document Request from ' . $documentRequest->name;
        Mail::to($documentRequest->email)->send(new DocumentRequestMail($mailData, $mailSubject));
        
        session(['documentRequest_id' => $documentRequest_id]);

        return redirect()
            ->route('redirectDocumentRequestDetails')->with('success', 'Document Request Cancelled Successfully!');
    }

    public function redirectDocumentRequestFeedback(string $id) {
        session(['documentRequest_id' => $id]);
        return redirect()->route('documentRequestFeedbackForm');
    }

    public function documentRequestFeedbackForm() {
        $documentRequest_id = session('documentRequest_id');

        if (!$documentRequest_id) {
            return redirect()->route('documentRequestTracker')->with('failed', 'Document Request Session Destroyed!');
        }

        $documentRequest = DocumentRequest::where('documentRequest_id', $documentRequest_id)->first();

        session()->forget('documentRequest_id');
        return view('landing-page.aid-tracker.document-request.documentRequest-feedback', compact('documentRequest'));
    }

    public function refreshDocumentRequestFeedback(string $id) {
        session(['documentRequest_id' => $id]);
        return redirect()->route('documentRequestFeedbackForm');
    }

    public function redirectEditDocumentRequestFeedback(string $id) {
        session(['documentRequest_id' => $id]);
        $documentRequest_id = $id;
        return redirect()->route('documentRequestEditFeedbackForm', compact('documentRequest_id'));
    }

    public function documentRequestEditFeedbackForm(string $id) {
        $documentRequest_id = session('documentRequest_id');
        $documentRequest = DocumentRequest::where('documentRequest_id', $documentRequest_id)->first();

        if (!$documentRequest_id) {
            // Handle the case where appointment_id is not found in the session
            return redirect()->route('documentRequestTracker')->with('failed', 'Appointment Session Destroyed!');
        }

        $feedback = Feedback::where('transaction_id', $documentRequest_id)->where('transaction_type', 'Document Request')->first();
        $rating = $feedback->rating;
        $comment = $feedback->comment;

        session()->forget('documentRequest_id');
        return view('landing-page.aid-tracker.document-request.document-request-edit-feedback', compact('documentRequest', 'rating', 'comment'));
    }

    public function refreshEditDocumentRequestFeedback(string $id) {
        session(['documentRequest_id' => $id]);
        return redirect()->route('documentRequestEditFeedbackForm');
    }

    public function validateEditDocumentRequestFeedbackForm(Request $request, string $id) {
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'rating' => 'required',
            'comment' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        }else {
            return response()->json(['message' => 'Validation passed']);
        }
    }

    public function editDocumentRequestFeedback(Request $request, string $id, string $type) {
        if($type == 'Appointment') {
            $appointment = Appointment::where('appointment_id', $id)->first();
            $appointment_email = $appointment->email;

            $feedback = Feedback::where('transaction_id', $id)->where('transaction_type', 'Appointment')->first();

            if($feedback->rating != $request->rating || $feedback->comment != $request->comment) {
                $feedback->update([
                    'rating' => $request->rating,
                    'comment' => $request->comment,
                    'updated_at' => now('Asia/Manila'),
                ]);

                if($feedback) {
                    $mailData = [
                        'title' => 'Mail from PedroAID',
                        'name' => $appointment->name,
                        'message' => 'Your feedback has been updated.',
                        'tracking_id' => $id,
                        'link' => route('appointmentDetails', ['appointment_id' => $id, 'email' => $appointment_email]),
                    ];

                    $mailSubject = '[#'. $id . '] Updated Feedback: Appointment from ' . $appointment->name;
                    Mail::to($appointment_email)->send(new AppointmentMail($mailData, $mailSubject));

                    session(['appointment_id' => $id]);
                    return redirect()->route('appointmentEditFeedbackForm')->with('success', 'Feedback Updated Successfully!');
                } else {
                    session(['appointment_id' => $id]);
                    return redirect()->route('appointmentEditFeedbackForm')->with('failed', 'Feedback Failed to Update!');
                }
            } else {
                session(['appointment_id' => $id]);
                return redirect()->route('appointmentEditFeedbackForm')->with('failed', 'Fill Up the Form!');
            }
        } else if($type == 'Document Request') {
            $documentRequest = DocumentRequest::where('documentRequest_id', $id)->first();
            $documentRequest_email = $documentRequest->email;

            $feedback = Feedback::where('transaction_id', $id)->where('transaction_type', 'Document Request')->first();

            $feedback->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
                'updated_at' => now('Asia/Manila'),
            ]);

            if($feedback) {
                $mailData = [
                    'title' => 'Mail from PedroAID',
                    'name' => $documentRequest->name,
                    'message' => 'Your feedback has been updated.',
                    'tracking_id' => $id,
                    'link' => route('documentRequestDetails', ['documentRequest_id' => $id, 'email' => $documentRequest_email]),
                ];

                $mailSubject = '[#'. $id . '] Updated Feedback: Document Request from ' . $documentRequest->name;
                Mail::to($documentRequest_email)->send(new DocumentRequestMail($mailData, $mailSubject));

                session(['documentRequest_id' => $id]);
                return redirect()->route('documentRequestEditFeedbackForm', $id)->with('success', 'Feedback Updated Successfully!');
            } else {
                session(['documentRequest_id' => $id]);
                return redirect()->route('documentRequestEditFeedbackForm')->with('failed', 'Feedback Failed to Update!');
            }
        }
    }
}
