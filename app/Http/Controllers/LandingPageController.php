<?php

namespace App\Http\Controllers;

use App\Models\AffidavitOfGuardianship;
use App\Models\AffidavitOfLoss;
use App\Models\AffidavitOfNoFixIncome;
use App\Models\AffidavitOfNoIncome;
use App\Models\Appointment;
use App\Models\DeedOfDonation;
use App\Models\DeedOfSale;
use App\Models\ExtraJudicial;
use App\Models\Heir;
use App\Models\Ordinances;
use App\Models\OtherDocument;
use App\Models\User;
use App\Notifications\NewAppointment;
use App\Notifications\NewDocumentRequest;
use App\Notifications\NewInquiry;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Committee;
use App\Models\Inquiry;
use Mail;
use App\Mail\AppointmentMail;
use App\Mail\InquiryMail;
use App\Mail\DocumentRequestMail;
use App\Models\DocumentRequest;
use Illuminate\Support\Str;
use App\Models\Feedback;
use Illuminate\Validation\Rule;

class LandingPageController extends Controller
{
    public function home()
    {
        $feedback = Feedback::all();
        return view('landing-page.home', compact('feedback'));
    }

    public function termsOfService()
    {
        return view('landing-page.termsOfService');
    }

    public function privacyPolicy()
    {
        return view('landing-page.privacyPolicy');
    }

    public function displayCommittee()
    {
        $committee = Committee::all();
        return view('landing-page.city-ordinance.committee', compact('committee'));
    }

    public function displayYear(string $committee_name)
    {
        $committee = Committee::where('name', $committee_name)->first();
        $ordinanceYear = Ordinances::select(\DB::raw('YEAR(date_approved) as year'))
        ->where('committee', $committee_name)
        ->distinct()
        ->orderBy('year', 'desc')
        ->get();    

        return view('landing-page.city-ordinance.ordinance-year', compact('committee', 'ordinanceYear'));
    }

    public function displayOrdinance(string $committee_name, int $year)
    {
        $committee = Committee::where('name', $committee_name)->first();
        $ordinance = Ordinances::where('committee', $committee_name)
        ->whereYear('date_approved', $year)
        ->orderBy('date_approved', 'desc')
        ->get();

        return view('landing-page.city-ordinance.ordinance-list', compact('committee', 'ordinance'));
    }

    public function searchOrdinance(Request $request) {
        // Get the search query from the request
        $query = $request->input('query');

        // Perform the search in the ordinances table
        $ordinances = Ordinances::where('ordinance_number', 'like', "%{$query}%")
                                ->orWhere('committee', 'like', "%{$query}%")
                                ->orWhere('date_approved', 'like', "%{$query}%")
                                ->orWhere('description', 'like', "%{$query}%")
                                ->get();

        // Return the matching ordinances as JSON response
        return response()->json($ordinances);
    }

    public function appointmentForm()
    {
        return view('landing-page.appointment.appointment-form');
    }

    public function checkDateAvailability(Request $request) {
        $timeslots = ['14:00', '14:10', '14:20', '14:30', '14:40', '14:50', '15:00', '15:10', '15:20', '15:30', '15:40', '15:50'];
        $fullyBookedDates = [];
    
        // Get all unique dates from the appointments table
        $dates = Appointment::select('appointment_date')
                            ->whereIn('appointment_status', ['Pending', 'Booked', 'Rescheduled'])
                            ->where('appointment_date', '>=', Carbon::now('Asia/Manila'))
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

    public function appointmentValidateForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
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
                'message' => 'Appointment Request Received! Our team will review your request shortly.',
                'tracking_id' => $appointmentID,
                'link' => route('appointmentDetails', ['appointment_id' => $appointmentID, 'email' => $request->email]),
            ];
    
            $mailSubject = "[#$appointmentID] Requested Appointment: Appointment from $request->name";
    
            $this->sendAppointmentEmail($request->email, $mailData, $mailSubject);

            $staff = User::where('transaction_level', 'Appointment')->get();
            $admins = User::where('level', 'Admin')->get();
            $superAdmins = User::where('level', 'Super Admin')->get();
            
            $notificationData = [
                'appointment_id' => $appointmentID,
                'name' => $request->name,
            ];

            foreach ($staff as $user) {
                $user->notify(new NewAppointment($notificationData));
            }

            foreach ($admins as $admin) {
                $admin->notify(new NewAppointment($notificationData));
            }

            foreach ($superAdmins as $superAdmin) {
                $superAdmin->notify(new NewAppointment($notificationData));
            }
    
            return redirect()->route('appointmentForm')->with('success', 'Appointment Booked Successfully! Kindly check your email for confirmation.');
        } else {

            return redirect()->route('appointmentForm')->with('failed', 'Failed to Book Appointment!');
        }
    }

    private function sendAppointmentEmail(string $email, array $data, string $subject) {
        Mail::to($email)->send(new AppointmentMail($data, $subject));
    }   

    public function inquiryForm()
    {
        return view('landing-page.inquiry.inquiry-form');
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
                'message' => 'Inquiry Received! We will get back to you as soon as possible.',
                'tracking_id' => $inquiryID,
                'link' => route('inquiryDetails', ['inquiry_id' => $inquiryID, 'email' => $request->email]),
            ];

            $mailSubject = '[#'. $inquiryID . '] Inquiry Sent: Inquiry from ' . $request->name;

            Mail::to($request->email)->send(new InquiryMail($mailData, $mailSubject));

            $staff = User::where('transaction_level', 'Inquiry')->get();
            $admins = User::where('level', 'Admin')->get();
            $superAdmins = User::where('level', 'Super Admin')->get();
            
            $notificationData = [
                'inquiry_id' => $inquiryID,
                'name' => $request->name,
            ];

            foreach ($staff as $user) {
                $user->notify(new NewInquiry($notificationData));
            }

            foreach ($admins as $admin) {
                $admin->notify(new NewInquiry($notificationData));
            }

            foreach ($superAdmins as $superAdmin) {
                $superAdmin->notify(new NewInquiry($notificationData));
            }

            return redirect()->route('inquiryForm')->with('success', 'Inquiry Sent Successfully! Kindly check your email for confirmation.');
        }else {
            return redirect()->route('inquiryForm')->with('failed', 'Failed to Send Inquiry!');
        }
    }

    public function documentRequestForm()
    {
        return view('landing-page.document-request.documentRequest-form');
    }

    public function validateDocumentRequestForm(Request $request) {
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'name' => 'required',
            'city' => 'required',
            'barangay'=> 'required_if:city,San Pedro City',
            'street'=> 'required_if:city,San Pedro City',
            'other_city'=> 'required_if:city,Other City',
            'other_barangay'=> 'required_if:city,Other City',
            'other_street'=> 'required_if:city,Other City',
            'cellphone_number' => ['required', 'regex:/^(09|\+639)\d{9}$/'],
            'email' => 'required|email',
            'document_type' => 'required',

            'document_name' => 'required_if:document_type,Affidavit of Loss,Affidavit of No income,Affidavit of No fix income',
            'document_civil_status' => 'required_if:document_type,Affidavit of Loss,Affidavit of Guardianship,Affidavit of No income,Affidavit of No fix income,Deed of Sale',

            'document_city' => 'required_if:document_type,Affidavit of Loss,Affidavit of Guardianship,Affidavit of No income,Affidavit of No fix income,Deed of Sale,Deed of Donation',
            'document_barangay' => 'required_if:document_city,San Pedro City',
            'document_street' => 'required_if:document_city,San Pedro City',
            'document_other_city' => 'required_if:document_city,Other City',
            'document_other_barangay' => 'required_if:document_city,Other City',
            'document_other_street' => 'required_if:document_city,Other City',

            'document_city_2' => 'required_if:document_type,Deed of Donation',
            'document_barangay_2' => 'required_if:document_city_2,San Pedro City',
            'document_street_2' => 'required_if:document_city_2,San Pedro City',
            'document_other_city_2' => 'required_if:document_city_2,Other City',
            'document_other_barangay_2' => 'required_if:document_city_2,Other City',
            'document_other_street_2' => 'required_if:document_city_2,Other City',

            'valid_id_front' => 'required_if:document_type,Affidavit of Loss,Affidavit of Guardianship,Affidavit of No income,Affidavit of No fix income,Other Document|image|mimes:jpg,jpeg,png',
            'valid_id_back' => 'required_if:document_type,Affidavit of Loss,Affidavit of Guardianship,Affidavit of No income,Affidavit of No fix income,Other Document|image|mimes:jpg,jpeg,png',

            'item_lost' => 'required_if:document_type,Affidavit of Loss',
            'reason_of_loss' => 'required_if:document_type,Affidavit of Loss',

            'guardian_name' => 'required_if:document_type,Affidavit of Guardianship',
            'minor_name' => 'required_if:document_type,Affidavit of Guardianship',
            'years_in_care' => 'required_if:document_type,Affidavit of Guardianship',
            
            'year_of_no_income' => 'required_if:document_type,Affidavit of No income,Affidavit of No fix income|regex:/^\d{4}$/',
            'certificate_of_indigency' => 'required_if:document_type,Affidavit of No income|mimes:pdf',

            'certificate_of_residency' => 'required_if:document_type,Affidavit of No fix income|mimes:pdf',

            'title_of_property' => 'required_if:document_type,Extra Judicial',
            'title_holder' => 'required_if:document_type,Extra Judicial',
            'surviving_spouse' => [
                'string',
                Rule::requiredIf(function () use ($request) {
                    return $request->input('document_type') === 'Extra Judicial' &&
                            !$request->input('deceased_spouse') && empty($request->input('surviving_spouse'));
                }),
            ],            
            'spouse_valid_id_front' => [
                'image',
                'mimes:jpg,jpeg,png',
                Rule::requiredIf(function () use ($request) {
                    // Check if the checkbox is checked
                    return $request->input('document_type') === 'Extra Judicial' &&
                            !$request->input('deceased_spouse');
                }),
            ],
            'spouse_valid_id_back' => [
                'image',
                'mimes:jpg,jpeg,png',
                Rule::requiredIf(function () use ($request) {
                    // Check if the checkbox is checked
                    return $request->input('document_type') === 'Extra Judicial' &&
                            !$request->input('deceased_spouse');
                }),
            ],
            'surviving_heir.*' => 'required_if:deceased_spouse,on', 

            'name_of_vendor' => 'required_if:document_type,Deed of Sale',
            'property_document' => 'required_if:document_type,Deed of Sale|mimes:pdf',
            'property_price' => 'required_if:document_type,Deed of Sale',
            'vendor_valid_id_front' => 'required_if:document_type,Deed of Sale|image|mimes:jpg,jpeg,png',
            'vendor_valid_id_back' => 'required_if:document_type,Deed of Sale|image|mimes:jpg,jpeg,png',
            'name_of_vendee' => 'required_if:document_type,Deed of Sale',
            'vendee_valid_id_front' => 'required_if:document_type,Deed of Sale|image|mimes:jpg,jpeg,png',
            'vendee_valid_id_back' => 'required_if:document_type,Deed of Sale|image|mimes:jpg,jpeg,png',
            'name_of_witness' => 'required_if:document_type,Deed of Sale',
            'witness_valid_id_front' => 'required_if:document_type,Deed of Sale|image|mimes:jpg,jpeg,png',
            'witness_valid_id_back' => 'required_if:document_type,Deed of Sale|image|mimes:jpg,jpeg,png',

            'donor_name' => 'required_if:document_type,Deed of Donation',
            'donor_civil_status' => 'required_if:document_type,Deed of Donation',
            'donor_valid_id_front' => 'required_if:document_type,Deed of Donation|image|mimes:jpg,jpeg,png',
            'donor_valid_id_back' => 'required_if:document_type,Deed of Donation|image|mimes:jpg,jpeg,png',
            'donee_name' => 'required_if:document_type,Deed of Donation',
            'donee_civil_status' => 'required_if:document_type,Deed of Donation',
            'donee_valid_id_front' => 'required_if:document_type,Deed of Donation|image|mimes:jpg,jpeg,png',
            'donee_valid_id_back' => 'required_if:document_type,Deed of Donation|image|mimes:jpg,jpeg,png',
            'property_description' => 'required_if:document_type,Deed of Donation',
        ],
        [
            'barangay.required_if' => 'The barangay field is required.',
            'street.required_if' => 'The street field is required.',
            'other_city.required_if' => 'The city field is required.',
            'other_barangay.required_if' => 'The barangay field is required.',
            'other_street.required_if' => 'The street field is required.',

            'document_name.required_if' => 'The name field is required.',
            'document_civil_status.required_if' => 'The civil status field is required.',
            
            'document_city.required_if' => 'The city field is required.',
            'document_barangay.required_if' => 'The barangay field is required.',
            'document_street.required_if' => 'The street field is required.',
            'document_other_city.required_if' => 'The city field is required.',
            'document_other_barangay.required_if' => 'The barangay field is required.',
            'document_other_street.required_if' => 'The street field is required.',

            'document_city_2.required_if' => 'The city field is required.',
            'document_barangay_2.required_if' => 'The barangay field is required.',
            'document_street_2.required_if' => 'The street field is required.',
            'document_other_city_2.required_if' => 'The city field is required.',
            'document_other_barangay_2.required_if' => 'The barangay field is required.',
            'document_other_street_2.required_if' => 'The street field is required.',

            'valid_id_front.required_if' => 'The valid ID front field is required.',
            'valid_id_back.required_if' => 'The valid ID back field is required.',
            'item_lost.required_if' => 'The item lost field is required.',
            'reason_of_loss.required_if' => 'The reason of loss field is required.',

            'guardian_name.required_if' => 'The guardian name field is required.',
            'minor_name.required_if' => 'The minor name field is required.',
            'years_in_care.required_if' => 'The years in care field is required.',

            'certificate_of_indigency.required_if' => 'The certificate of indigency field is required.',
            'year_of_no_income.required_if' => 'The year of no income field is required.',
            'certificate_of_residency.required_if' => 'The certificate of residency field is required.',

            'title_of_property.required_if' => 'The title of property field is required.',
            'title_holder.required_if' => 'The title holder field is required.',
            'surviving_spouse.required_if' => 'The surviving spouse field is required.',
            'spouse_valid_id_front.required_if' => 'The spouse valid ID front field is required.',
            'spouse_valid_id_back.required_if' => 'The spouse valid ID back field is required.',
            'surviving_heir.*.required_if' => 'The name of surviving heir field is required.',

            'name_of_vendor.required_if' => 'The name of vendor field is required.',
            'property_document.required_if' => 'The property document field is required.',
            'property_price.required_if' => 'The property price field is required.',
            'vendor_valid_id_front.required_if' => 'The vendor valid ID front field is required.',
            'vendor_valid_id_back.required_if' => 'The vendor valid ID back field is required.',
            'name_of_vendee.required_if' => 'The name of vendee field is required.',
            'vendee_valid_id_front.required_if' => 'The vendee valid ID front field is required.',
            'vendee_valid_id_back.required_if' => 'The vendee valid ID back field is required.',
            'name_of_witness.required_if' => 'The name of witness field is required.',
            'witness_valid_id_front.required_if' => 'The witness valid ID front field is required.',
            'witness_valid_id_back.required_if' => 'The witness valid ID back field is required.',

            'donor_name.required_if' => 'The name field is required.',
            'donor_civil_status.required_if' => 'The civil status field is required.',
            'donor_address.required_if' => 'The address field is required.',
            'donor_valid_id_front.required_if' => 'The valid ID front field is required.',
            'donor_valid_id_back.required_if' => 'The valid ID back field is required.',
            'donee_name.required_if' => 'The name field is required.',
            'donee_civil_status.required_if' => 'The civil status field is required.',
            'donee_address.required_if' => 'The address field is required.',
            'donee_valid_id_front.required_if' => 'The valid ID front field is required.',
            'donee_valid_id_back.required_if' => 'The valid ID back field is required.',
            'property_description.required_if' => 'The property description field is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        }else {
            return response()->json(['message' => 'Validation passed']);
        }
    }

    public function saveDocumentRequest(Request $request) {
        $address = $this->generateAddress($request);
        $documentAddress = $this->generateDocumentAddress($request);
        
        $documentRequestID = DocumentRequest::generateUniqueDocumentRequestID();
    
        $createDocumentRequest = $this->createDocumentRequest($request, $address, $documentRequestID);

        if ($request->document_type == 'Affidavit of Loss') {
            $validIdFrontFilePath = $this->uploadValidIdFrontAffidavitOfLoss($request);
            $validIdBackFilePath = $this->uploadValidIdBackAffidavitOfLoss($request);
    
            $createAffidavitOfLoss = $this->createAffidavitOfLoss($request, $documentAddress, $validIdFrontFilePath, $validIdBackFilePath, $documentRequestID);
            
            if ($createDocumentRequest && $createAffidavitOfLoss) {
                $mailData = $this->prepareMailData($request, $documentRequestID);
                $mailSubject = $this->prepareMailSubject($documentRequestID, $request);
    
                $this->sendMail($request->email, $mailData, $mailSubject);

                $staff = User::where('transaction_level', 'Document Request')->get();
                $admins = User::where('level', 'Admin')->get();
                $superAdmins = User::where('level', 'Super Admin')->get();
                
                $notificationData = [
                    'documentRequest_id' => $documentRequestID,
                    'name' => $request->name,
                ];

                foreach ($staff as $user) {
                    $user->notify(new NewDocumentRequest($notificationData));
                }

                foreach ($admins as $admin) {
                    $admin->notify(new NewDocumentRequest($notificationData));
                }

                foreach ($superAdmins as $superAdmin) {
                    $superAdmin->notify(new NewDocumentRequest($notificationData));
                }
    
                return $this->successResponse();
            } else {
                return $this->failedResponse();
            }
        } else if ($request->document_type == 'Affidavit of Guardianship') {
            $validIdFrontFilePath = $this->uploadValidIdFrontAffidavitOfGuardianship($request);
            $validIdBackFilePath = $this->uploadValidIdBackAffidavitOfGuardianship($request);

            $createAffidavitOfGuardianship = $this->createAffidavitOfGuardianship($request, $documentAddress, $validIdFrontFilePath, $validIdBackFilePath, $documentRequestID);
    
            if ($createDocumentRequest && $createAffidavitOfGuardianship) {
                $mailData = $this->prepareMailData($request, $documentRequestID);
                $mailSubject = $this->prepareMailSubject($documentRequestID, $request);
    
                $this->sendMail($request->email, $mailData, $mailSubject);

                $staff = User::where('transaction_level', 'Document Request')->get();
                $admins = User::where('level', 'Admin')->get();
                $superAdmins = User::where('level', 'Super Admin')->get();
                
                $notificationData = [
                    'documentRequest_id' => $documentRequestID,
                    'name' => $request->name,
                ];

                foreach ($staff as $user) {
                    $user->notify(new NewDocumentRequest($notificationData));
                }

                foreach ($admins as $admin) {
                    $admin->notify(new NewDocumentRequest($notificationData));
                }

                foreach ($superAdmins as $superAdmin) {
                    $superAdmin->notify(new NewDocumentRequest($notificationData));
                }
    
                return $this->successResponse();
            } else {
                return $this->failedResponse();
            }
        } else if ($request->document_type == 'Affidavit of No income') {
            $certOfIndigencyFilePath = $this->uploadCertOfIndigency($request);
            $validIdFrontFilePath = $this->uploadValidIdFrontAONI($request);
            $validIdBackFilePath = $this->uploadValidIdBackAONI($request);
    
            $createAffidavitOfNoIncome = $this->createAffidavitOfNoIncome($request, $documentAddress, $certOfIndigencyFilePath, $validIdFrontFilePath, $validIdBackFilePath, $documentRequestID);
    
            if ($createDocumentRequest && $createAffidavitOfNoIncome) {
                $mailData = $this->prepareMailData($request, $documentRequestID);
                $mailSubject = $this->prepareMailSubject($documentRequestID, $request);
    
                $this->sendMail($request->email, $mailData, $mailSubject);

                $staff = User::where('transaction_level', 'Document Request')->get();
                $admins = User::where('level', 'Admin')->get();
                $superAdmins = User::where('level', 'Super Admin')->get();
                
                $notificationData = [
                    'documentRequest_id' => $documentRequestID,
                    'name' => $request->name,
                ];

                foreach ($staff as $user) {
                    $user->notify(new NewDocumentRequest($notificationData));
                }

                foreach ($admins as $admin) {
                    $admin->notify(new NewDocumentRequest($notificationData));
                }

                foreach ($superAdmins as $superAdmin) {
                    $superAdmin->notify(new NewDocumentRequest($notificationData));
                }
    
                return $this->successResponse();
            } else {
                return $this->failedResponse();
            }
        } else if ($request->document_type == 'Affidavit of No fix income') {
            $certOfResidencyFilePath = $this->uploadCertOfResidency($request);
            $validIdFrontFilePath = $this->uploadValidIdFrontAONFI($request);
            $validIdBackFilePath = $this->uploadValidIdBackAONFI($request);
    
            $createAffidavitOfNoFixIncome = $this->createAffidavitOfNoFixIncome($request, $documentAddress, $certOfResidencyFilePath, $validIdFrontFilePath, $validIdBackFilePath, $documentRequestID);
    
            if ($createDocumentRequest && $createAffidavitOfNoFixIncome) {
                $mailData = $this->prepareMailData($request, $documentRequestID);
                $mailSubject = $this->prepareMailSubject($documentRequestID, $request);
    
                $this->sendMail($request->email, $mailData, $mailSubject);

                $staff = User::where('transaction_level', 'Document Request')->get();
                $admins = User::where('level', 'Admin')->get();
                $superAdmins = User::where('level', 'Super Admin')->get();
                
                $notificationData = [
                    'documentRequest_id' => $documentRequestID,
                    'name' => $request->name,
                ];

                foreach ($staff as $user) {
                    $user->notify(new NewDocumentRequest($notificationData));
                }

                foreach ($admins as $admin) {
                    $admin->notify(new NewDocumentRequest($notificationData));
                }

                foreach ($superAdmins as $superAdmin) {
                    $superAdmin->notify(new NewDocumentRequest($notificationData));
                }
    
                return $this->successResponse();
            } else {
                return $this->failedResponse();
            }
        } else if ($request->document_type == 'Extra Judicial') {
            $titleOfPropertyFilePath = $this->uploadTitleOfProperty($request);

            if($request->deceased_spouse == "on") {
                $survivingSpouseName = null;
                $spouseValidIdFrontFilePath = null;
                $spouseValidIdBackFilePath = null;

                $createExtraJudicial = $this->createExtraJudicial($request, $titleOfPropertyFilePath, $survivingSpouseName, $spouseValidIdFrontFilePath, $spouseValidIdBackFilePath, $documentRequestID);
                $createHeirs = $this->createHeirs($request, $documentRequestID);

                if ($createDocumentRequest && $createExtraJudicial && $createHeirs) {
                    $mailData = $this->prepareMailData($request, $documentRequestID);
                    $mailSubject = $this->prepareMailSubject($documentRequestID, $request);
        
                    $this->sendMail($request->email, $mailData, $mailSubject);
    
                    $staff = User::where('transaction_level', 'Document Request')->get();
                    $admins = User::where('level', 'Admin')->get();
                    $superAdmins = User::where('level', 'Super Admin')->get();
                    
                    $notificationData = [
                        'documentRequest_id' => $documentRequestID,
                        'name' => $request->name,
                    ];
    
                    foreach ($staff as $user) {
                        $user->notify(new NewDocumentRequest($notificationData));
                    }
    
                    foreach ($admins as $admin) {
                        $admin->notify(new NewDocumentRequest($notificationData));
                    }
    
                    foreach ($superAdmins as $superAdmin) {
                        $superAdmin->notify(new NewDocumentRequest($notificationData));
                    }
        
                    return $this->successResponse();
                } else {
                    return $this->failedResponse();
                }
            } else {
                $survivingSpouseName = $request->surviving_spouse;
                $spouseValidIdFrontFilePath = $this->uploadSpouseValidIdFront($request);
                $spouseValidIdBackFilePath = $this->uploadSpouseValidIdBack($request);

                $createExtraJudicial = $this->createExtraJudicial($request, $titleOfPropertyFilePath, $survivingSpouseName, $spouseValidIdFrontFilePath, $spouseValidIdBackFilePath, $documentRequestID);
            
                if ($createDocumentRequest && $createExtraJudicial) {
                    $mailData = $this->prepareMailData($request, $documentRequestID);
                    $mailSubject = $this->prepareMailSubject($documentRequestID, $request);
        
                    $this->sendMail($request->email, $mailData, $mailSubject);
    
                    $staff = User::where('transaction_level', 'Document Request')->get();
                    $admins = User::where('level', 'Admin')->get();
                    $superAdmins = User::where('level', 'Super Admin')->get();
                    
                    $notificationData = [
                        'documentRequest_id' => $documentRequestID,
                        'name' => $request->name,
                    ];
    
                    foreach ($staff as $user) {
                        $user->notify(new NewDocumentRequest($notificationData));
                    }
    
                    foreach ($admins as $admin) {
                        $admin->notify(new NewDocumentRequest($notificationData));
                    }
    
                    foreach ($superAdmins as $superAdmin) {
                        $superAdmin->notify(new NewDocumentRequest($notificationData));
                    }
        
                    return $this->successResponse();
                } else {
                    return $this->failedResponse();
                }
            }

        } else if ($request->document_type == 'Deed of Sale') {
            $propertyDocumentFilePath = $this->uploadPropertyDocument($request);
            $vendorValidIdFrontFilePath = $this->uploadVendorValidIdFront($request);
            $vendorValidIdBackFilePath = $this->uploadVendorValidIdBack($request);
            $vendeeValidIdFrontFilePath = $this->uploadVendeeValidIdFront($request);
            $vendeeValidIdBackFilePath = $this->uploadVendeeValidIdBack($request);
            $witnessValidIdFrontFilePath = $this->uploadWitnessValidIdFront($request);
            $witnessValidIdBackFilePath = $this->uploadWitnessValidIdBack($request);

            $createDeedOfSale = $this->createDeedOfSale($request, $documentAddress, $propertyDocumentFilePath, $vendorValidIdFrontFilePath, $vendorValidIdBackFilePath, $vendeeValidIdFrontFilePath, $vendeeValidIdBackFilePath, $witnessValidIdFrontFilePath, $witnessValidIdBackFilePath, $documentRequestID);
    
            if ($createDocumentRequest && $createDeedOfSale) {
                $mailData = $this->prepareMailData($request, $documentRequestID);
                $mailSubject = $this->prepareMailSubject($documentRequestID, $request);
    
                $this->sendMail($request->email, $mailData, $mailSubject);

                $staff = User::where('transaction_level', 'Document Request')->get();
                $admins = User::where('level', 'Admin')->get();
                $superAdmins = User::where('level', 'Super Admin')->get();
                
                $notificationData = [
                    'documentRequest_id' => $documentRequestID,
                    'name' => $request->name,
                ];

                foreach ($staff as $user) {
                    $user->notify(new NewDocumentRequest($notificationData));
                }

                foreach ($admins as $admin) {
                    $admin->notify(new NewDocumentRequest($notificationData));
                }

                foreach ($superAdmins as $superAdmin) {
                    $superAdmin->notify(new NewDocumentRequest($notificationData));
                }
    
                return $this->successResponse();
            } else {
                return $this->failedResponse();
            }
        } else if ($request->document_type == 'Deed of Donation') {
            $donorValidIdFrontFilePath = $this->uploadDonorValidIdFront($request);
            $donorValidIdBackFilePath = $this->uploadDonorValidIdBack($request);
            $document2Address = $this->generateDocument2Address($request);
            $doneeValidIdFrontFilePath = $this->uploadDoneeValidIdFront($request);
            $doneeValidIdBackFilePath = $this->uploadDoneeValidIdBack($request);

            $createDeedOfDonation = $this->createDeedOfDonation($request, $documentAddress, $donorValidIdFrontFilePath, $donorValidIdBackFilePath, $document2Address, $doneeValidIdFrontFilePath, $doneeValidIdBackFilePath, $documentRequestID);
    
            if ($createDocumentRequest && $createDeedOfDonation) {
                $mailData = $this->prepareMailData($request, $documentRequestID);
                $mailSubject = $this->prepareMailSubject($documentRequestID, $request);
    
                $this->sendMail($request->email, $mailData, $mailSubject);

                $staff = User::where('transaction_level', 'Document Request')->get();
                $admins = User::where('level', 'Admin')->get();
                $superAdmins = User::where('level', 'Super Admin')->get();
                
                $notificationData = [
                    'documentRequest_id' => $documentRequestID,
                    'name' => $request->name,
                ];

                foreach ($staff as $user) {
                    $user->notify(new NewDocumentRequest($notificationData));
                }

                foreach ($admins as $admin) {
                    $admin->notify(new NewDocumentRequest($notificationData));
                }

                foreach ($superAdmins as $superAdmin) {
                    $superAdmin->notify(new NewDocumentRequest($notificationData));
                }
    
                return $this->successResponse();
            } else {
                return $this->failedResponse();
            }
        } else if ($request->document_type == 'Other Document') {
            $validIdFrontFilePath = $this->uploadValidIdFrontOther($request);
            $validIdBackFilePath = $this->uploadValidIdBackOther($request);

            $createOtherDocument = $this->createOtherDocument($validIdFrontFilePath, $validIdBackFilePath, $documentRequestID);

            if ($createDocumentRequest && $createOtherDocument) {
                $mailData = $this->prepareMailData($request, $documentRequestID);
                $mailSubject = $this->prepareMailSubject($documentRequestID, $request);
    
                $this->sendMail($request->email, $mailData, $mailSubject);

                $staff = User::where('transaction_level', 'Document Request')->get();
                $admins = User::where('level', 'Admin')->get();
                $superAdmins = User::where('level', 'Super Admin')->get();
                
                $notificationData = [
                    'documentRequest_id' => $documentRequestID,
                    'name' => $request->name,
                ];

                foreach ($staff as $user) {
                    $user->notify(new NewDocumentRequest($notificationData));
                }

                foreach ($admins as $admin) {
                    $admin->notify(new NewDocumentRequest($notificationData));
                }

                foreach ($superAdmins as $superAdmin) {
                    $superAdmin->notify(new NewDocumentRequest($notificationData));
                }
    
                return $this->successResponse();
            } else {
                return $this->failedResponse();
            }
        } else {
            return $this->failedResponse();
        }
    }
    
    private function generateAddress(Request $request) {
        $city = $request->city == 'San Pedro City' ? $request->city : $request->other_city;
        $street = $city == 'San Pedro City' ? $request->street : $request->other_street;
        $barangay = $city == 'San Pedro City' ? $request->barangay : $request->other_barangay;
    
        return trim($street) . ', Brgy. ' . trim($barangay) . ', ' . trim($city);
    }

    private function generateDocumentAddress(Request $request) {
        $city = $request->document_city == 'San Pedro City' ? $request->document_city : $request->document_other_city;
        $street = $city == 'San Pedro City' ? $request->document_street : $request->document_other_street;
        $barangay = $city == 'San Pedro City' ? $request->document_barangay : $request->document_other_barangay;
    
        return trim($street) . ', Brgy. ' . trim($barangay) . ', ' . trim($city);
    }
    private function generateDocument2Address(Request $request) {
        $city = $request->document_city_2 == 'San Pedro City' ? $request->document_city_2 : $request->document_other_city_2;
        $street = $city == 'San Pedro City' ? $request->document_street_2 : $request->document_other_street_2;
        $barangay = $city == 'San Pedro City' ? $request->document_barangay_2 : $request->document_other_barangay_2;
    
        return trim($street) . ', Brgy. ' . trim($barangay) . ', ' . trim($city);
    }

    private function uploadValidIdFrontAffidavitOfLoss(Request $request) {
        $file = $request->file('valid_id_front');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/affidavitOfLoss/' . $fileName;
        $file->move('uploads/document-request/affidavitOfLoss/', $fileName);
    
        return $filePath;
    }

    private function uploadValidIdBackAffidavitOfLoss(Request $request) {
        $file = $request->file('valid_id_back');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/affidavitOfLoss/' . $fileName;
        $file->move('uploads/document-request/affidavitOfLoss/', $fileName);
    
        return $filePath;
    }

    private function uploadValidIdFrontAffidavitOfGuardianship(Request $request) {
        $file = $request->file('valid_id_front');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/affidavitOfGuardianship/' . $fileName;
        $file->move('uploads/document-request/affidavitOfGuardianship/', $fileName);
    
        return $filePath;
    }

    private function uploadValidIdBackAffidavitOfGuardianship(Request $request) {
        $file = $request->file('valid_id_back');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/affidavitOfGuardianship/' . $fileName;
        $file->move('uploads/document-request/affidavitOfGuardianship/', $fileName);
    
        return $filePath;
    }

    private function uploadCertOfIndigency(Request $request) {
        $file = $request->file('certificate_of_indigency');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/affidavitOfNoIncome/' . $fileName;
        $file->move('uploads/document-request/affidavitOfNoIncome/', $fileName);
    
        return $filePath;
    }

    private function uploadValidIdFrontAONI(Request $request) {
        $file = $request->file('valid_id_front');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/affidavitOfNoIncome/' . $fileName;
        $file->move('uploads/document-request/affidavitOfNoIncome/', $fileName);
    
        return $filePath;
    }

    private function uploadValidIdBackAONI(Request $request) {
        $file = $request->file('valid_id_back');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/affidavitOfNoIncome/' . $fileName;
        $file->move('uploads/document-request/affidavitOfNoIncome/', $fileName);
    
        return $filePath;
    }

    private function uploadCertOfResidency(Request $request) {
        $file = $request->file('certificate_of_residency');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/affidavitOfNoFixIncome/' . $fileName;
        $file->move('uploads/document-request/affidavitOfNoFixIncome/', $fileName);
    
        return $filePath;
    }

    private function uploadValidIdFrontAONFI(Request $request) {
        $file = $request->file('valid_id_front');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/affidavitOfNoFixIncome/' . $fileName;
        $file->move('uploads/document-request/affidavitOfNoFixIncome/', $fileName);
    
        return $filePath;
    }

    private function uploadValidIdBackAONFI(Request $request) {
        $file = $request->file('valid_id_back');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/affidavitOfNoFixIncome/' . $fileName;
        $file->move('uploads/document-request/affidavitOfNoFixIncome/', $fileName);
    
        return $filePath;
    }

    private function uploadPropertyDocument(Request $request) {
        $file = $request->file('property_document');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/deedOfSale/' . $fileName;
        $file->move('uploads/document-request/deedOfSale/', $fileName);
    
        return $filePath;
    }

    private function uploadVendorValidIdFront(Request $request) {
        $file = $request->file('vendor_valid_id_front');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/deedOfSale/' . $fileName;
        $file->move('uploads/document-request/deedOfSale/', $fileName);
    
        return $filePath;
    }

    private function uploadVendorValidIdBack(Request $request) {
        $file = $request->file('vendor_valid_id_back');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/deedOfSale/' . $fileName;
        $file->move('uploads/document-request/deedOfSale/', $fileName);
    
        return $filePath;
    }

    private function uploadVendeeValidIdFront(Request $request) {
        $file = $request->file('vendee_valid_id_front');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/deedOfSale/' . $fileName;
        $file->move('uploads/document-request/deedOfSale/', $fileName);
    
        return $filePath;
    }

    private function uploadVendeeValidIdBack(Request $request) {
        $file = $request->file('vendee_valid_id_back');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/deedOfSale/' . $fileName;
        $file->move('uploads/document-request/deedOfSale/', $fileName);
    
        return $filePath;
    }

    private function uploadWitnessValidIdFront(Request $request) {
        $file = $request->file('witness_valid_id_front');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/deedOfSale/' . $fileName;
        $file->move('uploads/document-request/deedOfSale/', $fileName);
    
        return $filePath;
    }

    private function uploadWitnessValidIdBack(Request $request) {
        $file = $request->file('witness_valid_id_back');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/deedOfSale/' . $fileName;
        $file->move('uploads/document-request/deedOfSale/', $fileName);
    
        return $filePath;
    }

    private function uploadDonorValidIdFront(Request $request) {
        $file = $request->file('donor_valid_id_front');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/deedOfDonation/' . $fileName;
        $file->move('uploads/document-request/deedOfDonation/', $fileName);
    
        return $filePath;
    }

    private function uploadDonorValidIdBack(Request $request) {
        $file = $request->file('donor_valid_id_back');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/deedOfDonation/' . $fileName;
        $file->move('uploads/document-request/deedOfDonation/', $fileName);
    
        return $filePath;
    }

    private function uploadDoneeValidIdFront(Request $request) {
        $file = $request->file('donee_valid_id_front');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/deedOfDonation/' . $fileName;
        $file->move('uploads/document-request/deedOfDonation/', $fileName);
    
        return $filePath;
    }

    private function uploadDoneeValidIdBack(Request $request) {
        $file = $request->file('donee_valid_id_back');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/deedOfDonation/' . $fileName;
        $file->move('uploads/document-request/deedOfDonation/', $fileName);
    
        return $filePath;
    }

    private function uploadValidIdFrontOther(Request $request) {
        $file = $request->file('valid_id_front');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/otherDocument/' . $fileName;
        $file->move('uploads/document-request/otherDocument/', $fileName);
    
        return $filePath;
    }

    private function uploadValidIdBackOther(Request $request) {
        $file = $request->file('valid_id_back');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/otherDocument/' . $fileName;
        $file->move('uploads/document-request/otherDocument/', $fileName);
    
        return $filePath;
    }

    private function uploadTitleOfProperty(Request $request) {
        $file = $request->file('title_of_property');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/ExtraJudicial/' . $fileName;
        $file->move('uploads/document-request/ExtraJudicial/', $fileName);
    
        return $filePath;
    }

    private function uploadSpouseValidIdFront(Request $request) {
        $file = $request->file('spouse_valid_id_front');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/ExtraJudicial/' . $fileName;
        $file->move('uploads/document-request/ExtraJudicial/', $fileName);
    
        return $filePath;
    }

    private function uploadSpouseValidIdBack(Request $request) {
        $file = $request->file('spouse_valid_id_back');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/ExtraJudicial/' . $fileName;
        $file->move('uploads/document-request/ExtraJudicial/', $fileName);
    
        return $filePath;
    }
    
    private function createDocumentRequest(Request $request, $address, $documentRequestID) {
        $data = [
            'documentRequest_id' => $documentRequestID,
            'name' => trim($request->name),
            'address' => $address,
            'cellphone_number' => trim($request->cellphone_number),
            'email' => trim($request->email),
            'document_type' => $request->document_type,
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now('Asia/Manila'),
        ];
    
        return DocumentRequest::create($data);
    }

    private function createAffidavitOfLoss(Request $request, $address, $validIdFrontFilePath, $validIdBackFilePath, $documentRequestID) {
        $data = [
            'documentRequest_id' => $documentRequestID,
            'name' => trim($request->document_name),
            'civil_status' => trim($request->document_civil_status),
            'address' => $address,
            'item_lost' => trim($request->item_lost),
            'reason_of_loss' => trim($request->reason_of_loss),
            'valid_id_front' => $validIdFrontFilePath,
            'valid_id_back' => $validIdBackFilePath,
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now('Asia/Manila'),
        ];
    
        return AffidavitOfLoss::create($data);
    }

    private function createAffidavitOfGuardianship(Request $request, $address, $validIdFrontFilePath, $validIdBackFilePath, $documentRequestID) {
        $data = [
            'documentRequest_id' => $documentRequestID,
            'guardian_name' => trim($request->guardian_name),
            'civil_status' => trim($request->document_civil_status),
            'address' => $address,
            'minor_name' => trim($request->minor_name),
            'years_in_care' => $request->years_in_care,
            'valid_id_front' => $validIdFrontFilePath,
            'valid_id_back' => $validIdBackFilePath,
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now('Asia/Manila'),
        ];
    
        return AffidavitOfGuardianship::create($data);
    }

    private function createAffidavitOfNoIncome(Request $request, $address, $certOfIndigencyFilePath, $validIdFrontFilePath, $validIdBackFilePath, $documentRequestID) {
        $data = [
            'documentRequest_id' => $documentRequestID,
            'name' => trim($request->document_name),
            'civil_status' => trim($request->document_civil_status),
            'address' => $address,
            'year_of_no_income' => $request->year_of_no_income,
            'certificate_of_indigency' => $certOfIndigencyFilePath,
            'valid_id_front' => $validIdFrontFilePath,
            'valid_id_back' => $validIdBackFilePath,
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now('Asia/Manila'),
        ];

        if (!empty(trim($request->previous_employer_name)) && !empty(trim($request->previous_employer_contact))) {
            $data['previous_employer_name'] = trim($request->previous_employer_name);
            $data['previous_employer_contact'] = trim($request->previous_employer_contact);
        }

        return AffidavitOfNoIncome::create($data);
    }

    private function createAffidavitOfNoFixIncome(Request $request, $address, $certOfResidencyFilePath, $validIdFrontFilePath, $validIdBackFilePath, $documentRequestID) {
        $data = [
            'documentRequest_id' => $documentRequestID,
            'name' => trim($request->document_name),
            'civil_status' => trim($request->document_civil_status),
            'address' => $address,
            'year_of_no_income' => $request->year_of_no_income,
            'certificate_of_residency' => $certOfResidencyFilePath,
            'valid_id_front' => $validIdFrontFilePath,
            'valid_id_back' => $validIdBackFilePath,
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now('Asia/Manila'),
        ];
    
        return AffidavitOfNoFixIncome::create($data);
    }

    private function createExtraJudicial(Request $request, $titleOfPropertyFilePath, $survivingSpouseName, $spouseValidIdFrontFilePath, $spouseValidIdBackFilePath, $documentRequestID) {
        $data = [
            'documentRequest_id' => $documentRequestID,
            'title_of_property' => $titleOfPropertyFilePath,
            'title_holder' => trim($request->title_holder),
            'surviving_spouse' => $survivingSpouseName,
            'spouse_valid_id_front' => $spouseValidIdFrontFilePath,
            'spouse_valid_id_back' => $spouseValidIdBackFilePath,
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now('Asia/Manila'),
        ];
    
        return ExtraJudicial::create($data);
    }

    private function createHeirs(Request $request, $documentRequestID) {
        $survivingHeirs = $request->input('surviving_heir');
        $spousesOfHeirs = $request->input('spouse_of_heir');
    
        foreach ($survivingHeirs as $key => $heirName) {
            $heir = new Heir();
            $heir->surviving_heir = $heirName;
            $heir->documentRequest_id = $documentRequestID;

            if (isset($spousesOfHeirs[$key])) {
                $heir->spouse_of_heir = $spousesOfHeirs[$key];
            }
            $heir->save();
        }

        return true;
    }
    
    private function createDeedOfSale(Request $request, $address, $propertyDocumentFilePath, $vendorValidIdFrontFilePath, $vendorValidIdBackFilePath, $vendeeValidIdFrontFilePath, $vendeeValidIdBackFilePath, $witnessValidIdFrontFilePath, $witnessValidIdBackFilePath, $documentRequestID) {
        $data = [
            'documentRequest_id' => $documentRequestID,
            'name_of_vendor' => trim($request->name_of_vendor),
            'vendor_civil_status' => trim($request->document_civil_status),
            'vendor_address' => $address,
            'property_document' => $propertyDocumentFilePath,
            'property_price' => $request->property_price,
            'vendor_valid_id_front' => $vendorValidIdFrontFilePath,
            'vendor_valid_id_back' => $vendorValidIdBackFilePath,
            'name_of_vendee' => trim($request->name_of_vendee),
            'vendee_valid_id_front' => $vendeeValidIdFrontFilePath,
            'vendee_valid_id_back' => $vendeeValidIdBackFilePath,
            'name_of_witness' => trim($request->name_of_witness),
            'witness_valid_id_front' => $witnessValidIdFrontFilePath,
            'witness_valid_id_back' => $witnessValidIdBackFilePath,
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now('Asia/Manila'),
        ];
    
        return DeedOfSale::create($data);
    }

    private function createDeedOfDonation(Request $request, $address, $donorValidIdFrontFilePath, $donorValidIdBackFilePath, $address2, $doneeValidIdFrontFilePath, $doneeValidIdBackFilePath, $documentRequestID) {
        $data = [
            'documentRequest_id' => $documentRequestID,
            'donor_name' => trim($request->donor_name),
            'donor_civil_status' => trim($request->donor_civil_status),
            'donor_address' => $address,
            'donor_valid_id_front' => $donorValidIdFrontFilePath,
            'donor_valid_id_back' => $donorValidIdBackFilePath,
            'donee_name' => trim($request->donee_name),
            'donee_civil_status' => trim($request->donee_civil_status),
            'donee_address' => $address2,
            'donee_valid_id_front' => $doneeValidIdFrontFilePath,
            'donee_valid_id_back' => $doneeValidIdBackFilePath,
            'property_description' => trim($request->property_description),
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now('Asia/Manila'),
        ];
    
        return DeedOfDonation::create($data);
    }

    private function createOtherDocument($validIdFrontFilePath, $validIdBackFilePath, $documentRequestID) {
        $data = [
            'documentRequest_id' => $documentRequestID,
            'valid_id_front' => $validIdFrontFilePath,
            'valid_id_back' => $validIdBackFilePath,
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now('Asia/Manila'),
        ];
    
        return OtherDocument::create($data);
    }
    
    private function prepareMailData(Request $request, $documentRequestID) {
        return [
            'title' => 'Mail from PedroAID',
            'name' => trim($request->name),
            'message' => 'Document Request Received!',
            'tracking_id' => $documentRequestID,
            'link' => route('documentRequestDetails', ['documentRequest_id' => $documentRequestID, 'email' => $request->email]),
        ];
    }
    
    private function prepareMailSubject($documentRequestID, Request $request) {
        return '[#'. $documentRequestID . '] Document Request Sent: Document Request from ' . trim($request->name);
    }
    
    private function sendMail($email, $mailData, $mailSubject) {
        Mail::to($email)->send(new DocumentRequestMail($mailData, $mailSubject));
        return true;
    }
    
    private function successResponse() {
        return redirect()->route('documentRequestForm')->with('success', 'Document Request Sent! Kindly check your email for confirmation. ');
    }   
    
    private function failedResponse() {
        return redirect()->route('documentRequestForm')->with('failed', 'Failed to Send Document Request!');
    }   
}
