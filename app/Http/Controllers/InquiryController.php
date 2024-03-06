<?php

namespace App\Http\Controllers;

use App\Mail\InquiryMail;
use App\Models\InquiryMessage;
use App\Models\Logs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class InquiryController extends Controller
{
    public function index()
    {
        $unanswered_inquiry = Inquiry::where('status', 'unanswered')->get();
        $answered_inquiry = Inquiry::where('status', 'answered')->get();
        return view('inquiry.index', compact('unanswered_inquiry', 'answered_inquiry')); 
    }

    public function addInquiry()
    {
        return view('inquiry.addInquiry');
    }

    public function validateInquiryForm(Request $request) {
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'inquiry' => 'required',
         ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        }else {
            return response()->json(['message' => 'Validation passed']);
        }
    }

    public function saveInquiry(Request $request) {
        $inquiryID = Inquiry::generateUniqueInquiryID();

        $createInquiry = Inquiry::create([
            'inquiry_id' => $inquiryID,
            'name' => $request->name,
            'email' => $request->email,
            'inquiry' => $request->inquiry,
            'status' => 'Unanswered',
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at'=> Carbon::now('Asia/Manila'),
        ]);

        if($createInquiry->save()) {
            $mailData = [
                'title' => 'Mail from PedroAID',
                'name' => $request->name,
                'message' => 'Inquiry Received!',
                'tracking_id' => $inquiryID,
                'link' => 'http://127.0.0.1:8000/tracker/inquiry-details/check-details?inquiry_id=' . $inquiryID . '&email=' . $request->email,
            ];

            $mailSubject = '[#'. $inquiryID . '] Inquiry Sent: Inquiry from ' . $request->name;

            Mail::to($request->email)->send(new InquiryMail($mailData, $mailSubject));

            $this->logAddInquiry('Add Inquiry Success', Auth::user()->username, $inquiryID);

            return redirect()->route('inquiry')->with('success', 'Inquiry Sent Successfully!');
        }else {

            $this->logAddInquiry('Add Inquiry Failed', Auth::user()->username, $inquiryID);
            return redirect()->route('inquiry')->with('failed', 'Failed to Send Inquiry!');
        }
    }

    private function logAddInquiry(string $status, string $user, string $inquiryID)
    {
        Logs::create([
            'type' => 'Add Inquiry',
            'user' => $user,
            'subject' => $status,
            'message' => "Inquiry ID: $inquiryID. $status by $user.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    public function inquiryDetails(string $id)
    {
        $inquiry = Inquiry::where('inquiry_id', $id)->get()->first();
        $messages = InquiryMessage::where('inquiry_id', $id)->get();
        $staffName = Auth::user()->name;

        return view('inquiry.inquiryDetails', compact('inquiry', 'messages', 'staffName'));
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
    
        $this->logMessageSent($id);
    
        $this->sendInquiryMessageEmail($inquiry, $id, $inquiry_email);
    
        return redirect()->route('inquiry.inquiryDetails', $id)->with('success', 'Message Sent!');
    }

    private function validateMessageRequest($request)
    {
        return Validator::make($request->all(), [
            '_token' => 'required',
            'message' => 'required',
        ]);
    }

    private function getInquiryById($id)
    {
        return Inquiry::where('inquiry_id', $id)->get()->first();
    }

    private function saveInquiryMessage($id, $inquiry_email, $message)
    {
        InquiryMessage::create([
            'inquiry_id' => $id,
            'email' => $inquiry_email,
            'staff_name' => Auth::user()->name,
            'message' => $message,
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    private function updateInquiryTimestamp($id)
    {
        Inquiry::where('inquiry_id', $id)->update([
            'status' => 'Answered',
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    private function logMessageSent($id)
    {
        Logs::create([
            'type' => 'Send Inquiry Message',
            'user' => Auth::user()->username,
            'subject' => 'Send Inquiry Message Success',
            'message' => 'Inquiry ID: ' . $id . ' has been successfully sent a message by ' . Auth::user()->username . '.',
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    private function sendInquiryMessageEmail($inquiry, $id, $inquiry_email)
    {
        $mailData = [
            'title' => 'Mail from PedroAID',
            'name' => $inquiry->name,
            'message' => 'Inquiry Response!',
            'tracking_id' => $id,
            'link' => route('inquiryDetails', ['inquiry_id' => $id, 'email' => $inquiry_email])
        ];

        $mailSubject = "[#$id] New Message: Inquiry from $inquiry->name";

        Mail::to($inquiry_email)->send(new InquiryMail($mailData, $mailSubject));
    }

    public function editInquiry(string $id)
    {
        $inquiry = Inquiry::where('inquiry_id', $id)->get()->first();
        return view('inquiry.editInquiry', compact('inquiry'));
    }

    public function validateEditInquiryForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'inquiry' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        } else {
            return response()->json(['message' => 'Validation passed']);
        }
    }

    public function updateInquiry(Request $request, string $id) {
        $inquiry = Inquiry::where('inquiry_id', $id)->get()->first();

        if ($this->shouldUpdateInquiry($request, $inquiry)) {
            $this->updateInquiryDetails($request, $inquiry);
    
            $this->logInquiryEditSuccess($id);
    
            return redirect()->route('inquiry.editInquiry', $id)->with('success', 'Inquiry Edited Successfully!');
        } else {
            $this->logInquiryEditFailed($id);
    
            return redirect()->route('inquiry.editInquiry', $id)->with('failed', 'Failed to Edit Inquiry!');
        }
    }

    private function shouldUpdateInquiry($request, $inquiry)
    {
        return $request->name !== $inquiry->name || 
        $request->email !== $inquiry->email || 
        $request->inquiry !== $inquiry->inquiry || 
        $request->status !== $inquiry->status;
    }

    private function updateInquiryDetails($request, $inquiry)
    {
        Inquiry::where('inquiry_id', $inquiry->inquiry_id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'inquiry' => $request->inquiry,
            'status' => $request->status,
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    private function logInquiryEditSuccess($id)
    {
        Logs::create([
            'type' => 'Edit Inquiry',
            'user' => Auth::user()->username,
            'subject' => 'Edit Inquiry Success',
            'message' => 'Inquiry ID: ' . $id . ' has been successfully edited by ' . Auth::user()->username . '.',
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    private function logInquiryEditFailed($id)
    {
        Logs::create([
            'type' => 'Edit Inquiry',
            'user' => Auth::user()->username,
            'subject' => 'Edit Inquiry Failed',
            'message' => 'Failed to edit Inquiry ID: ' . $id . ' by ' . Auth::user()->username . '.',
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    public function deleteInquiry(string $id)
    {
        $inquiry = Inquiry::where('inquiry_id', $id)->get()->first();
        return view('inquiry.deleteInquiry', compact('inquiry'));
    }

    public function destroyInquiry(string $id)
    {
        try {
            DB::beginTransaction();
    
            $inquiry = Inquiry::where('inquiry_id', $id)->get()->first();
            $user = Auth::user()->username;
    
            $this->createDeleteInquiryLog($user, $inquiry);
    
            $inquiryStatus = $inquiry->inquiry_status;
    
            Inquiry::where('inquiry_id', $id)->delete();

            DB::table('notifications')
            ->where('data->inquiry_id', $id)
            ->where('type', 'App\Notifications\NewInquiry')
            ->delete();

            DB::table('notifications')
            ->where('data->inquiry_id', $id)
            ->where('type', 'App\Notifications\NewInquiryMessage')
            ->delete();
    
            DB::commit();
    
            return redirect()->route('inquiry')->with('success', 'Inquiry Deleted Successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
    
            return redirect()->route('inquiry')->with('failed', 'Failed to delete Inquiry!');
        }
    }

    private function createDeleteInquiryLog($user, $inquiry)
    {
        $logData = [
            'type' => 'Delete Inquiry',
            'user' => $user,
            'subject' => 'Delete Inquiry Success',
            'message' => 'Inquiry ID: ' . $inquiry->inquiry_id . ' has been successfully deleted by ' . $user . '.',
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];
    
        Logs::create($logData);
    }
}
