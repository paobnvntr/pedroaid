<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentMessage;
use App\Models\Feedback;
use App\Models\Logs;
use App\Notifications\NewAppointmentMessage;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentMail;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public function index()
    {
        $booked_rescheduled = Appointment::whereIn('appointment_status', ['Booked', 'Rescheduled'])
                                        ->orderBy('appointment_date', 'ASC')
                                        ->orderBy('appointment_time', 'ASC')
                                        ->get();

        $cancelled = Appointment::where('appointment_status', 'Cancelled')
                                ->orderBy('appointment_date', 'ASC')
                                ->orderBy('appointment_time', 'ASC')
                                ->get();
                                         
        return view('appointment.index', compact('booked_rescheduled', 'cancelled'));   
    }

    public function pendingAppointment()
    {
        $pending = Appointment::where('appointment_status', 'Pending')
                                         ->orderBy('appointment_date', 'ASC')
                                         ->orderBy('appointment_time', 'ASC')
                                         ->get();
        $declined = Appointment::where('appointment_status', 'Declined')
                                         ->orderBy('appointment_date', 'ASC')
                                         ->orderBy('appointment_time', 'ASC')
                                         ->get();

        return view('appointment.pendingAppointment', compact('pending', 'declined'));   
    }

    public function finishedAppointment()
    {
        $finished = Appointment::where('appointment_status', 'Finished')
                                         ->orderBy('appointment_date', 'ASC')
                                         ->orderBy('appointment_time', 'ASC')
                                         ->get();
        
        $no_show = Appointment::where('appointment_status', 'No-Show')
                                         ->orderBy('appointment_date', 'ASC')
                                         ->orderBy('appointment_time', 'ASC')
                                         ->get();

        return view('appointment.finishedAppointment', compact('finished', 'no_show'));   
    }

    public function approveAppointment(string $id) {
        $this->updateAppointmentStatus($id, 'Booked');
        return redirect()->route('appointment.appointmentDetails', $id)->with('success', 'Appointment Approved Successfully!');
    }
    
    public function declineAppointment(string $id) {
        $this->updateAppointmentStatus($id, 'Declined');
        return redirect()->route('appointment.appointmentDetails', $id)->with('success', 'Appointment Declined Successfully!');
    }

    public function cancelAppointment(string $id) {
        $this->updateAppointmentStatus($id, 'Declined');
        return redirect()->route('appointment.appointmentDetails', $id)->with('success', 'Appointment Declined Successfully!');
    }

    public function finishAppointment(string $id) {
        $this->updateAppointmentStatus($id, 'Finished');
        return redirect()->route('appointment.appointmentDetails', $id)->with('success', 'Appointment Finished Successfully!');
    }

    public function noShowAppointment(string $id) {
        $this->updateAppointmentStatus($id, 'No-Show');
        return redirect()->route('appointment.appointmentDetails', $id)->with('success', 'Appointment No-Show Successfully!');
    } 
    
    private function updateAppointmentStatus(string $id, string $status) {
        $appointment = Appointment::where('appointment_id', $id)->first();
    
        if (!$appointment) {
            // Handle the case where the appointment is not found
            return redirect()->route('appointment.appointmentDetails', $id)->with('failed', 'Appointment not found!');
        }
    
        Appointment::where('appointment_id', $id)->update([
            'appointment_status' => $status,
            'updated_at' => now('Asia/Manila'),
            'date_finished' => $status == 'Finished' ? now('Asia/Manila') : null,
        ]);
    
        $appointment_email = $appointment->email;

        if($status == 'Finished') {
            $additional_message = 'Kindly fill up the feedback form.';
        } elseif($status == 'No-Show') {
            $additional_message = 'Please reschedule if you want to continue your appointment.';
        } else {
            $additional_message = '';
        }
    
        $mailData = [
            'title' => 'Mail from PedroAID',
            'name' => $appointment->name,
            'message' => "Your appointment has been $status. $additional_message" ,
            'tracking_id' => $id,
            'link' => route('appointmentDetails', ['appointment_id' => $id, 'email' => $appointment_email]),
        ];
    
        $mailSubject = "[#$id] $status Appointment: Appointment from $appointment->name";
    
        $this->sendAppointmentEmail($appointment_email, $mailData, $mailSubject);
        
        $user = Auth::user()->username;
        $this->logAppointmentStatus($status, $user, $id);
    }
    
    private function logAppointmentStatus(string $status, string $user, string $appointmentID) {
        Logs::create([
            'type' => 'Update Appointment Status',
            'user' => $user,
            'subject' => "Update Appointment Status to $status",
            'message' => "Appointment ID: $appointmentID status updated to $status by $user.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }
    
    private function sendAppointmentEmail(string $email, array $data, string $subject) {
        Mail::to($email)->send(new AppointmentMail($data, $subject));
    }    

    public function addAppointment()
    {
        return view('appointment.addAppointment');
    }

    public function checkDateAvailability(Request $request) {
        $timeslots = ['14:00', '14:10', '14:20', '14:30', '14:40', '14:50', '15:00', '15:10', '15:20', '15:30', '15:40', '15:50'];
        $fullyBookedDates = [];
    
        // Get all unique dates from the appointments table
        $dates = Appointment::select('appointment_date')
                            ->whereIn('appointment_status', ['Pending', 'Booked', 'Rescheduled'])
                            ->where('appointment_date', '>=', now('Asia/Manila'))
                            ->groupBy('appointment_date')
                            ->get();
    
        foreach ($dates as $date) {
            $isFullyBooked = true;
    
            foreach ($timeslots as $timeslot) {
                $isAvailable = Appointment::where('appointment_date', $date->appointment_date)
                                        ->where('appointment_time', $timeslot)
                                        ->whereIn('appointment_status', ['Pending', 'Booked', 'Rescheduled'])
                                        ->doesntExist();
    
                if ($isAvailable) {
                    $isFullyBooked = false;
                    break;
                }
            }
    
            if ($isFullyBooked) {
                $fullyBookedDates[] = $date->appointment_date;
            }
        }
    
        return response()->json([
            'fullyBookedDates' => $fullyBookedDates,
        ]);
    }

    public function checkTimeAvailability(Request $request) {
        $selectedDate = $request->input('selectedDate');
        $timeslot = $request->input('timeslot');
    
        $isAvailable = Appointment::where('appointment_date', $selectedDate)
                                    ->where('appointment_time', $timeslot)
                                    ->whereIn('appointment_status', ['Pending', 'Booked', 'Rescheduled'])
                                    ->doesntExist();

        if ($isAvailable) {
            return response()->json(['message' => 'Timeslot is available']);
        } else {
            return response()->json(['message'=> 'Timeslot is not available']);
        }
    }

    public function validateForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'city' => 'required',
            'barangay'=> 'required_if:city,San Pedro City',
            'street'=> 'required_if:city,San Pedro City',
            'other_city'=> 'required_if:city,Other City',
            'other_barangay'=> 'required_if:city,Other City',
            'other_street'=> 'required_if:city,Other City',
            'cellphone_number' => ['required', 'regex:/^(09|\+639)\d{9}$/'],
            'email' => 'required|email',
            'appointment_date' => 'required',
            'appointment_time' => 'required',
        ],
        [
            'barangay.required_if' => 'The barangay field is required.',
            'street.required_if' => 'The street field is required.',
            'other_city.required_if' => 'The city field is required.',
            'other_barangay.required_if' => 'The barangay field is required.',
            'other_street.required_if' => 'The street field is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        }else {
            return response()->json(['message' => 'Validation passed']);
        }
    }

    public function saveAppointment(Request $request)
    {
        $appointmentID = Appointment::generateUniqueAppointmentID();
    
        $address = $request->city == 'San Pedro City'
            ? $request->street . ', Brgy. ' . $request->barangay . ', ' . $request->city
            : $request->other_street . ', Brgy. ' . $request->other_barangay . ', ' . $request->other_city;
    
        $appointmentData = [
            'appointment_id' => $appointmentID,
            'name' => $request->name,
            'address' => $address,
            'cellphone_number' => $request->cellphone_number,
            'email' => $request->email,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'appointment_status' => 'Pending',
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];
    
        $createAppointment = Appointment::create($appointmentData);
    
        if ($createAppointment->save()) {
            $mailData = [
                'title' => 'Mail from PedroAID',
                'name' => $request->name,
                'message' => 'Appointment Request Received!',
                'tracking_id' => $appointmentID,
                'link' => route('appointmentDetails', ['appointment_id' => $appointmentID, 'email' => $request->email]),
            ];
    
            $mailSubject = "[#$appointmentID] Requested Appointment: Appointment from $request->name";
    
            $this->sendAppointmentEmail($request->email, $mailData, $mailSubject);
    
            $user = Auth::user()->username;
            $this->logAddAppointment('Add Appointment Success', $user, $appointmentID);
    
            return redirect()->route('appointment.pendingAppointment')->with('success', 'Appointment Booked Successfully!');
        } else {
            $user = Auth::user()->username;
            $this->logAddAppointment('Add Appointment Failed', $user, $appointmentID);
    
            return redirect()->route('appointment.pendingAppointment')->with('failed', 'Failed to Book Appointment!');
        }
    }

    private function logAddAppointment(string $status, string $user, string $appointmentID)
    {
        Logs::create([
            'type' => 'Add Appointment',
            'user' => $user,
            'subject' => $status,
            'message' => "Appointment ID: $appointmentID. $status by $user.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }
    
    public function appointmentDetails(string $appointment_id) {
        $appointment = Appointment::where('appointment_id', $appointment_id)->first();
        $messages = AppointmentMessage::where('appointment_id', $appointment_id)->where('email', $appointment->email)->get();
        $staffName = Auth::user()->name;

        $feedback = Feedback::where('transaction_id', $appointment_id)->where('transaction_type', 'Appointment')->get();
        $rating = '';
        $comment = '';
        if($feedback->count() > 0) {
            $feedback = Feedback::where('transaction_id', $appointment_id)->where('transaction_type', 'Appointment')->get()->first();
            $rating = $feedback->rating;
            $comment = $feedback->comment;
        }    

        return view('appointment.appointmentDetails', compact('appointment', 'messages', 'staffName', 'feedback', 'rating', 'comment'));
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
    
        $this->logMessageSent($id);
    
        $this->sendAppointmentMessageEmail($appointment, $id, $appointment_email);
    
        return redirect()->route('appointment.appointmentDetails', $id)->with('success', 'Message Sent!');
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
            'staff_name' => Auth::user()->name,
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }
    
    private function updateAppointmentTimestamp(string $id) {
        Appointment::where('appointment_id', $id)->update([
            'updated_at' => now('Asia/Manila'),
        ]);
    }
    
    private function logMessageSent(string $id) {
        $user = Auth::user()->username;
        Logs::create([
            'type' => 'Message Appointment',
            'user' => $user,
            'subject' => 'Message Appointment Success',
            'message' => "$user has successfully sent a message to Appointment ID: $id.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }
    
    private function sendAppointmentMessageEmail($appointment, string $id, string $appointment_email) {
        $mailData = [
            'title' => 'Mail from PedroAID',
            'name' => $appointment->name,
            'message' => 'You received a message!',
            'tracking_id' => $id,
            'link' => route('appointmentDetails', ['appointment_id' => $id, 'email' => $appointment_email]),
        ];
    
        $mailSubject = "[#$id] New Message: Appointment from $appointment->name";
    
        Mail::to($appointment_email)->send(new AppointmentMail($mailData, $mailSubject));
    }    

    public function rescheduleAppointmentForm(string $id) {
        $appointment = Appointment::where('appointment_id', $id)->first();
        return view('appointment.rescheduleAppointment', compact('appointment'));
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
    
        $this->updateAppointmentRescheduleDetails($id, $selectedDate, $timeslot, 'Rescheduled');
    
        $appointment = Appointment::where('appointment_id', $id)->first();
        $appointment_email = $appointment->email;
    
        $this->logRescheduleSuccess($id);
        
        $this->sendRescheduleEmail($appointment, $id, $appointment_email);
    
        return redirect()->route('appointment.appointmentDetails', $id)->with('success', 'Appointment Rescheduled Successfully!');
    }
    
    private function updateAppointmentRescheduleDetails(string $id, string $selectedDate, string $timeslot, string $status) {
        Appointment::where('appointment_id', $id)->update([
            'appointment_date' => $selectedDate,
            'appointment_time' => $timeslot,
            'appointment_status' => $status,
            'updated_at' => now('Asia/Manila'),
        ]);
    }
    
    private function logRescheduleSuccess(string $id) {
        $user = Auth::user()->username;
        Logs::create([
            'type' => 'Reschedule Appointment',
            'user' => $user,
            'subject' => 'Reschedule Appointment Success',
            'message' => "$user has successfully rescheduled Appointment ID: $id.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }
    
    private function sendRescheduleEmail($appointment, string $id, string $appointment_email) {
        $mailData = [
            'title' => 'Mail from PedroAID',
            'name' => $appointment->name,
            'message' => 'Your appointment has been rescheduled.',
            'tracking_id' => $id,
            'link' => route('appointmentDetails', ['appointment_id' => $id, 'email' => $appointment_email]),
        ];
    
        $mailSubject = "[#$id] Rescheduled Appointment: Appointment from $appointment->name";
    
        Mail::to($appointment_email)->send(new AppointmentMail($mailData, $mailSubject));
    }    

    // redirect to appointment edit page
    public function editAppointment(string $id)
    {
        $appointment = Appointment::where('appointment_id', $id)->get()->first();
        $address = explode(', ', $appointment->address);

        $city = $address[2];
        $city = trim($city);

        $final_barangay = '';
        $street = '';
        $other_city = '';
        $other_barangay = '';
        $other_street = '';

        if($city == 'San Pedro City') {
            $barangay = $address[1];
            $trim_barangay = explode('. ', $barangay);
            $final_barangay = $trim_barangay[1];
            $final_barangay = trim($final_barangay);
    
            $street = $address[0];
            $street = trim($street);
        } else {
            $other_city = $city;
            
            $other_barangay = $address[1];
            $other_barangay = trim($other_barangay);

            $other_street = $address[0];
            $other_street = trim($other_street);
        }

        return view('appointment.editAppointment', compact('appointment', 'city', 'final_barangay', 'street', 'other_city', 'other_barangay', 'other_street'));
    }

    public function validateEditForm(Request $request, string $id)
    {
        $appointment = Appointment::where('appointment_id', $id)->get()->first();

        $validator = Validator::make($request->all(), [
            'cellphone_number' => ['regex:/^(09|\+639)\d{9}$/'],
            'email' => 'email',
            'barangay'=> 'required_if:city,San Pedro City',
            'street'=> 'required_if:city,San Pedro City',
            'other_city'=> 'required_if:city,Other City',
            'other_barangay'=> 'required_if:city,Other City',
            'other_street'=> 'required_if:city,Other City',
        ],
        [
            'barangay.required_if' => 'The barangay field is required.',
            'street.required_if' => 'The street field is required.',
            'other_city.required_if' => 'The city field is required.',
            'other_barangay.required_if' => 'The barangay field is required.',
            'other_street.required_if' => 'The street field is required.',
        ]);

        if ($request->appointment_date != $appointment->appointment_date) {
            $selectedDate = $request->appointment_date;
            $selectedTimeslot = $request->appointment_time;
            
            $dateTimeslots = Appointment::where('appointment_date', $selectedDate)->whereIn('appointment_status', ['Pending', 'Booked', 'Rescheduled'])->get();
            $timeslots = [];
            foreach ($dateTimeslots as $dateTimeslot) {
                $timeslots[] = $dateTimeslot->appointment_time;
            }

            if (in_array($selectedTimeslot, $timeslots)) {
                return response()->json(['message' => 'Validation failed', 'errors' => ['appointment_time' => ['The selected timeslot is not available.']]]);
            }
        }

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        }else {         
            return response()->json(['message' => 'Validation passed']);
        }
    }

    public function updateAppointment(Request $request, string $id) {
        $appointment = Appointment::where('appointment_id', $id)->get()->first();
    
        if ($this->shouldUpdateAppointment($request, $appointment)) {
            $this->updateAppointmentDetails($request, $appointment);
    
            $this->logAppointmentEditSuccess($id);
    
            return redirect()->route('appointment.editAppointment', $id)->with('success', 'Appointment Edited Successfully!');
        } else {
            return redirect()->route('appointment.editAppointment', $id)->with('failed', 'Fill Up a Field!');
        }
    }
        
    private function shouldUpdateAppointment(Request $request, $appointment) {
        return $request->appointment_status != $appointment->appointment_status ||
            $request->appointment_date != $appointment->appointment_date ||
            $request->appointment_time != $appointment->appointment_time ||
            $request->name != $appointment->name ||
            $this->shouldUpdateAddress($request, $appointment) ||
            $request->cellphone_number != $appointment->cellphone_number ||
            $request->email != $appointment->email;
    }
    
    private function shouldUpdateAddress(Request $request, $appointment) {
        return ($request->city == 'San Pedro City' && $request->filled('barangay') && $request->filled('street')) ||
            ($request->city == 'other-city' && $request->filled('other_barangay') && $request->filled('other_street'));
    }
    
    private function updateAppointmentDetails(Request $request, $appointment) {
        $appointment->name = $request->name;
    
        if ($this->shouldUpdateAddress($request, $appointment)) {
            $appointment->address = $this->generateAddress($request);
        }
    
        $appointment->cellphone_number = $request->cellphone_number;
        $appointment->email = $request->email;
        $appointment->appointment_date = $request->appointment_date;
        $appointment->appointment_time = $request->appointment_time;
    
        if ($request->appointment_status != $appointment->appointment_status) {
            $appointment->appointment_status = $request->appointment_status;
        }

        Appointment::where('appointment_id', $appointment->appointment_id)->update([
            'name' => $appointment->name,
            'address' => $appointment->address,
            'cellphone_number' => $appointment->cellphone_number,
            'email' => $appointment->email,
            'appointment_date' => $appointment->appointment_date,
            'appointment_time' => $appointment->appointment_time,
            'appointment_status' => $appointment->appointment_status,
            'updated_at' => now('Asia/Manila'),
        ]);
    }
    
    private function generateAddress(Request $request) {
        if ($request->city == 'San Pedro City') {
            return $request->street . ', Brgy. ' . $request->barangay . ', ' . $request->city;
        } elseif ($request->city == 'other-city') {
            return $request->other_street . ', Brgy. ' . $request->other_barangay . ', ' . $request->other_city;
        }
    
        return null;
    }
    
    private function logAppointmentEditSuccess(string $id) {
        $user = Auth::user()->username;
        Logs::create([
            'type' => 'Edit Appointment',
            'user' => $user,
            'subject' => 'Edit Appointment Success',
            'message' => "Appointment ID: $id has successfully edited by $user.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }    

    // redirect to delete appointment page
    public function deleteAppointment(string $id)
    {
        $appointment = Appointment::where('appointment_id', $id)->get()->first();
        return view('appointment.deleteAppointment', compact('appointment'));
    }

    // delete appointment account
    public function destroyAppointment(string $id)
    {
        try {
            DB::beginTransaction();
    
            $appointment = Appointment::where('appointment_id', $id)->get()->first();
            $user = Auth::user()->username;
    
            $this->createDeleteAppointmentLog($user, $appointment);
    
            $appointmentStatus = $appointment->appointment_status;
    
            Appointment::where('appointment_id', $id)->delete();

            if(Feedback::where('transaction_id', $id)->where('transaction_type', 'Appointment')->get()->count() > 0) {
                Feedback::where('transaction_id', $id)->where('transaction_type', 'Appointment')->delete();
            }

            DB::table('notifications')
            ->where('data->appointment_id', $id)
            ->where('type', 'App\Notifications\NewAppointment')
            ->delete();

            DB::table('notifications')
            ->where('data->appointment_id', $id)
            ->where('type', 'App\Notifications\NewAppointmentMessage')
            ->delete();
    
            $route = $this->getRouteByAppointmentStatus($appointmentStatus);
    
            DB::commit();
    
            return redirect()->route($route)->with('success', 'Appointment Deleted Successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
    
            return redirect()->route('appointment')->with('failed', 'Failed to delete Appointment!');
        }
    }
    
    // Function to create delete appointment log
    private function createDeleteAppointmentLog($user, $appointment)
    {
        $logData = [
            'type' => 'Delete Appointment',
            'user' => $user,
            'subject' => 'Delete Appointment Success',
            'message' => 'Appointment ID: ' . $appointment->appointment_id . ' has been successfully deleted by ' . $user . '.',
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];
    
        Logs::create($logData);
    }
    
    // Function to get route based on appointment status
    private function getRouteByAppointmentStatus($status)
    {
        $routeMap = [
            'Finished' => 'appointment.finishedAppointment',
            'No-Show' => 'appointment.finishedAppointment', // Assuming no-show appointments go to the same route as finished
            'Pending' => 'appointment.pendingAppointment',
            'Declined' => 'appointment.pendingAppointment', // Assuming declined appointments go to the same route as pending
            'Booked' => 'appointment',
            'Rescheduled' => 'appointment',
            'Cancelled' => 'appointment',
        ];
    
        return $routeMap[$status] ?? 'appointment';
    }    

    public function appointmentFeedback()
    {
        $feedback = Feedback::where('transaction_type', 'Appointment')->get();
        return view('appointment.appointmentFeedback', compact('feedback'));
    }

    public function feedbackForm(string $id) {
        $appointment = Appointment::where('appointment_id', $id)->first();
        return view('appointment.appointmentFeedbackForm', compact('appointment'));
    }

    public function validateFeedbackForm(Request $request, string $id) {
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'rating' => 'required',
            'comment' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        }else {
            return response()->json(['message' => 'Validation passed']);
        }
    }

    public function saveFeedback(Request $request, string $id) {
        $feedback = Feedback::where('transaction_id', $id)->where('transaction_type', 'Appointment')->get();
        $rating = $request->rating;
        $comment = $request->comment;

        if($feedback->count() > 0) {
            Feedback::where('transaction_id', $id)->where('transaction_type', 'Appointment')->update([
                'rating' => $rating,
                'comment' => $comment,
                'updated_at' => now('Asia/Manila'),
            ]);
        } else {
            Feedback::create([
                'transaction_id' => $id,
                'transaction_type' => 'Appointment',
                'rating' => $rating,
                'comment' => $comment,
                'created_at' => now('Asia/Manila'),
                'updated_at' => now('Asia/Manila'),
            ]);
        }

        $user = Auth::user()->username;
        Logs::create([
            'type' => 'Add Appointment Feedback',
            'user' => $user,
            'subject' => 'Add Appointment Feedback Success',
            'message' => 'Appointment ID: ' . $id . ' has been successfully add feedback by ' . $user . '.',
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);

        return redirect()->route('appointment.appointmentDetails', $id)->with('success', 'Feedback Submitted Successfully!');
    }

    public function feedbackEditForm(string $id) {
        $appointment = Appointment::where('appointment_id', $id)->first();
        $feedback = Feedback::where('transaction_id', $id)->where('transaction_type', 'Appointment')->get()->first();
        $rating = $feedback->rating;
        $comment = $feedback->comment;
        return view('appointment.appointmentEditFeedbackForm', compact('appointment', 'rating', 'comment'));
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

    public function saveEditFeedback(Request $request, string $id, string $type) {
        $feedback = Feedback::where('transaction_id', $id)->where('transaction_type', $type)->get()->first();
        $rating = $feedback->rating;
        $comment = $feedback->comment;

        if($rating != $request->rating || $comment != $request->comment) {
            $feedback->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
                'updated_at' => now('Asia/Manila'),
            ]);


            $user = Auth::user()->username;
            Logs::create([
                'type' => 'Edit Appointment Feedback',
                'user' => $user,
                'subject' => 'Edit Appointment Feedback Success',
                'message' => 'Appointment ID: ' . $id . ' has been successfully edit feedback by ' . $user . '.',
                'created_at' => now('Asia/Manila'),
                'updated_at' => now('Asia/Manila'),
            ]);

            return redirect()->route('feedbackEditForm', $id)->with('success', 'Feedback Edited Successfully!');
        } else {
            return redirect()->route('feedbackEditForm', $id)->with('failed', 'No changes made!');
        }
    }

    public function deleteFeedback(string $id) {
        $feedback = Feedback::where('transaction_id', $id)->where('transaction_type', 'Appointment')->get()->first();
        return view('appointment.deleteFeedback', compact('feedback'));
    }

    public function destroyFeedback(string $id) {
        $feedback = Feedback::where('transaction_id', $id)->where('transaction_type', 'Appointment')->get()->first();
        $feedback->delete();

        $user = Auth::user()->username;
        Logs::create([
            'type' => 'Delete Appointment Feedback',
            'user' => $user,
            'subject' => 'Delete Appointment Feedback Success',
            'message' => 'Appointment ID: ' . $id . ' has been successfully delete feedback by ' . $user . '.',
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);

        return redirect()->route('appointment.appointmentFeedback')->with('success', 'Feedback Deleted Successfully!');
    }
}