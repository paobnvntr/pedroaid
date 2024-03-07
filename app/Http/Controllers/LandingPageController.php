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
use App\Models\Ordinances;
use App\Models\User;
use App\Notifications\NewAppointment;
use App\Notifications\NewAppointmentMessage;
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
                'message' => 'Appointment Request Received!',
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
                'message' => 'Inquiry Received!',
                'tracking_id' => $inquiryID,
                'link' => 'http://127.0.0.1:8000/tracker/inquiry-details/check-details?inquiry_id=' . $inquiryID . '&email=' . $request->email,
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
            'document_age' => 'required_if:document_type,Affidavit of Loss,Affidavit of No income,Affidavit of No fix income|gte:18',

            'document_city' => 'required_if:document_type,Affidavit of Loss,Affidavit of Guardianship,Affidavit of No Income,Affidavit of No fix income,Deed of Donation',
            'document_barangay' => 'required_if:document_city,San Pedro City',
            'document_street' => 'required_if:document_city,San Pedro City',
            'document_other_city' => 'required_if:document_city,Other City',
            'document_other_barangay' => 'required_if:document_city,Other City',
            'document_other_street' => 'required_if:document_city,Other City',

            'document_city_2' => 'required_if:document_type,Affidavit of Guardianship,Deed of Donation',
            'document_barangay_2' => 'required_if:document_city_2,San Pedro City',
            'document_street_2' => 'required_if:document_city_2,San Pedro City',
            'document_other_city_2' => 'required_if:document_city_2,Other City',
            'document_other_barangay_2' => 'required_if:document_city_2,Other City',
            'document_other_street_2' => 'required_if:document_city_2,Other City',

            'valid_id_front' => 'required_if:document_type,Affidavit of Loss|image|mimes:jpg,jpeg,png',
            'valid_id_back' => 'required_if:document_type,Affidavit of Loss|image|mimes:jpg,jpeg,png',
            'cedula' => 'required_if:document_type,Affidavit of Loss|mimes:pdf',

            'guardian_name' => 'required_if:document_type,Affidavit of Guardianship',
            'guardian_age' => 'required_if:document_type,Affidavit of Guardianship|gte:18',
            'guardian_occupation' => 'required_if:document_type,Affidavit of Guardianship',
            'barangay_clearance' => 'required_if:document_type,Affidavit of Guardianship|mimes:pdf',
            'relationship' => 'required_if:document_type,Affidavit of Guardianship',
            'minor_name' => 'required_if:document_type,Affidavit of Guardianship',
            'minor_age' => 'required_if:document_type,Affidavit of Guardianship|lt:18',
            'minor_relationship' => 'required_if:document_type,Affidavit of Guardianship',

            'certificate_of_indigency' => 'required_if:document_type,Affidavit of No income,Affidavit of No fix income|mimes:pdf',
            'previous_employer_name' => 'required_with:previous_employer_contact',
            'previous_employer_contact' => 'required_with:previous_employer_name',
            'business_name' => 'required_if:document_type,Affidavit of No income',
            'registration_number' => 'required_if:document_type,Affidavit of No income',
            'business_address' => 'required_if:document_type,Affidavit of No income',
            'business_period' => 'required_if:document_type,Affidavit of No income',
            'no_income_period' => 'required_if:document_type,Affidavit of No income',

            'source_of_income' => 'required_if:document_type,Affidavit of No fix income',

            'death_certificate' => 'required_if:document_type,Extra Judicial|mimes:pdf',
            'heirship_documents' => 'required_if:document_type,Extra Judicial|mimes:pdf',
            'inventory_of_estate' => 'required_if:document_type,Extra Judicial|mimes:pdf',
            'tax_clearance' => 'required_if:document_type,Extra Judicial|mimes:pdf',
            'deed_of_extrajudicial_settlement' => 'required_if:document_type,Extra Judicial|mimes:pdf',

            'party1_name' => 'required_if:document_type,Deed of Sale',
            'party2_name' => 'required_if:document_type,Deed of Sale',
            'property_details' => 'required_if:document_type,Deed of Sale',

            'donor_name' => 'required_if:document_type,Deed of Donation',
            'donor_age' => 'required_if:document_type,Deed of Donation',
            'donee_name' => 'required_if:document_type,Deed of Donation',
            'donee_age' => 'required_if:document_type,Deed of Donation',
        ],
        [
            'barangay.required_if' => 'The barangay field is required.',
            'street.required_if' => 'The street field is required.',
            'other_city.required_if' => 'The city field is required.',
            'other_barangay.required_if' => 'The barangay field is required.',
            'other_street.required_if' => 'The street field is required.',

            'document_name.required_if' => 'The name field is required.',
            'document_age.required_if' => 'The age field is required.',
            
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
            'cedula.required_if' => 'The cedula field is required.',

            'guardian_name.required_if' => 'The name field is required.',
            'guardian_age.required_if' => 'The age field is required.',
            'guardian_occupation.required_if' => 'The occupation field is required.',
            'barangay_clearance.required_if' => 'The barangay clearance field is required.',
            'relationship.required_if' => 'The relationship field is required.',
            'minor_name.required_if' => 'The name field is required.',
            'minor_age.required_if' => 'The age field is required.',
            'minor_relationship.required_if' => 'The relationship field is required.',

            'certificate_of_indigency.required_if' => 'The certificate of indigency field is required.',
            'previous_employer_name.required_with' => 'The previous employer name field is required.',
            'previous_employer_contact.required_with' => 'The previous employer contact field is required.',
            'business_name.required_if' => 'The business name field is required.',
            'registration_number.required_if' => 'The registration number field is required.',
            'business_address.required_if' => 'The business address field is required.',
            'business_period.required_if' => 'The business period field is required.',
            'no_income_period.required_if' => 'The no income period field is required.',

            'source_of_income.required_if' => 'The source of income field is required.',

            'death_certificate.required_if' => 'The death certificate field is required.',
            'heirship_documents.required_if' => 'The heirship documents field is required.',
            'inventory_of_estate.required_if' => 'The inventory of estate field is required.',
            'tax_clearance.required_if' => 'The tax clearance field is required.',
            'deed_of_extrajudicial_settlement.required_if' => 'The deed of extrajudicial settlement field is required.',

            'party1_name.required_if' => 'The name field is required.',
            'party2_name.required_if' => 'The name field is required.',
            'property_details.required_if' => 'The details field is required.',

            'donor_name.required_if' => 'The name field is required.',
            'donor_age.required_if' => 'The age field is required.',
            'donor_address.required_if' => 'The address field is required.',
            'donee_name.required_if' => 'The name field is required.',
            'donee_age.required_if' => 'The age field is required.',
            'donee_address.required_if' => 'The address field is required.',
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
            $validIdFrontFilePath = $this->uploadValidIdFront($request);
            $validIdBackFilePath = $this->uploadValidIdBack($request);
            $cedulaFilePath = $this->uploadCedula($request);
    
            $createAffidavitOfLoss = $this->createAffidavitOfLoss($request, $documentAddress, $validIdFrontFilePath, $validIdBackFilePath, $cedulaFilePath, $documentRequestID);
            
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
            $barangayClearanceFilePath = $this->uploadBarangayClearanceAOG($request);
            $document2Address = $this->generateDocument2Address($request);

            $createAffidavitOfGuardianship = $this->createAffidavitOfGuardianship($request, $documentAddress, $document2Address, $barangayClearanceFilePath, $documentRequestID);
    
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
            $certOfIndigencyFilePath = $this->uploadCertOfIndigencyAONI($request);
    
            $createAffidavitOfNoIncome = $this->createAffidavitOfNoIncome($request, $documentAddress, $certOfIndigencyFilePath, $documentRequestID);
    
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
            $certOfIndigencyFilePath = $this->uploadCertOfIndigencyAONFI($request);
    
            $createAffidavitOfNoFixIncome = $this->createAffidavitOfNoFixIncome($request, $documentAddress, $certOfIndigencyFilePath, $documentRequestID);
    
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
            $deathCertificateFilePath = $this->uploadDeathCertificate($request);
            $heirshipFilePath = $this->uploadHeirship($request);
            $invOfEstateFilePath = $this->uploadInvOfEstate($request);
            $taxClearanceFilePath = $this->uploadTaxClearance($request);
            $deedOfExtraJudicialSettlementFilePath = $this->uploadDeedOfExtraJudicialSettlement($request);
    
            $createExtraJudicial = $this->createExtraJudicial($deathCertificateFilePath, $heirshipFilePath, $invOfEstateFilePath, $taxClearanceFilePath, $deedOfExtraJudicialSettlementFilePath, $documentRequestID);
    
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
        } else if ($request->document_type == 'Deed of Sale') {
            $createDeedOfSale = $this->createDeedOfSale($request, $documentRequestID);
    
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
            $document2Address = $this->generateDocument2Address($request);
            $createDeedOfDonation = $this->createDeedOfDonation($request, $documentAddress, $document2Address, $documentRequestID);
    
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

    private function uploadValidIdFront(Request $request) {
        $file = $request->file('valid_id_front');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/affidavitOfLoss/' . $fileName;
        $file->move('uploads/document-request/affidavitOfLoss/', $fileName);
    
        return $filePath;
    }

    private function uploadValidIdBack(Request $request) {
        $file = $request->file('valid_id_back');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/affidavitOfLoss/' . $fileName;
        $file->move('uploads/document-request/affidavitOfLoss/', $fileName);
    
        return $filePath;
    }

    private function uploadCedula(Request $request) {
        $file = $request->file('cedula');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/affidavitOfLoss/' . $fileName;
        $file->move('uploads/document-request/affidavitOfLoss/', $fileName);
    
        return $filePath;
    }

    private function uploadBarangayClearanceAOG(Request $request) {
        $file = $request->file('barangay_clearance');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/affidavitOfGuardianship/' . $fileName;
        $file->move('uploads/document-request/affidavitOfGuardianship/', $fileName);
    
        return $filePath;
    }

    private function uploadCertOfIndigencyAONI(Request $request) {
        $file = $request->file('certificate_of_indigency');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/affidavitOfNoIncome/' . $fileName;
        $file->move('uploads/document-request/affidavitOfNoIncome/', $fileName);
    
        return $filePath;
    }

    private function uploadCertOfIndigencyAONFI(Request $request) {
        $file = $request->file('certificate_of_indigency');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/affidavitOfNoFixIncome/' . $fileName;
        $file->move('uploads/document-request/affidavitOfNoFixIncome/', $fileName);
    
        return $filePath;
    }

    private function uploadDeathCertificate(Request $request) {
        $file = $request->file('death_certificate');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/ExtraJudicial/' . $fileName;
        $file->move('uploads/document-request/ExtraJudicial/', $fileName);
    
        return $filePath;
    }

    private function uploadHeirship(Request $request) {
        $file = $request->file('heirship_documents');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/ExtraJudicial/' . $fileName;
        $file->move('uploads/document-request/ExtraJudicial/', $fileName);
    
        return $filePath;
    }

    private function uploadInvOfEstate(Request $request) {
        $file = $request->file('inventory_of_estate');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/ExtraJudicial/' . $fileName;
        $file->move('uploads/document-request/ExtraJudicial/', $fileName);
    
        return $filePath;
    }

    private function uploadTaxClearance(Request $request) {
        $file = $request->file('tax_clearance');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/ExtraJudicial/' . $fileName;
        $file->move('uploads/document-request/ExtraJudicial/', $fileName);
    
        return $filePath;
    }

    private function uploadDeedOfExtraJudicialSettlement(Request $request) {
        $file = $request->file('deed_of_extrajudicial_settlement');
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

    private function createAffidavitOfLoss(Request $request, $address, $validIdFrontFilePath, $validIdBackFilePath, $cedulaFilePath, $documentRequestID) {
        $data = [
            'documentRequest_id' => $documentRequestID,
            'aol_name' => trim($request->document_name),
            'aol_age' => $request->document_age,
            'aol_address' => $address,
            'valid_id_front' => $validIdFrontFilePath,
            'valid_id_back' => $validIdBackFilePath,
            'cedula' => $cedulaFilePath,
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now('Asia/Manila'),
        ];
    
        return AffidavitOfLoss::create($data);
    }

    private function createAffidavitOfGuardianship(Request $request, $address, $address2, $barangayClearanceFilePath, $documentRequestID) {
        $data = [
            'documentRequest_id' => $documentRequestID,
            'guardian_name' => trim($request->guardian_name),
            'guardian_age' => $request->guardian_age,
            'guardian_address' => $address,
            'guardian_occupation' => trim($request->guardian_occupation),
            'guardian_brgy_clearance' => $barangayClearanceFilePath,
            'guardian_relationship' => trim($request->relationship),
            'minor_name' => trim($request->minor_name),
            'minor_age' => $request->minor_age,
            'minor_address' => $address2,
            'minor_relationship' => trim($request->minor_relationship),
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now('Asia/Manila'),
        ];
    
        return AffidavitOfGuardianship::create($data);
    }

    private function createAffidavitOfNoIncome(Request $request, $address, $certOfIndigencyFilePath, $documentRequestID) {
        $data = [
            'documentRequest_id' => $documentRequestID,
            'aoni_name' => trim($request->document_name),
            'aoni_age' => $request->document_age,
            'aoni_address' => $address,
            'certificate_of_indigency' => $certOfIndigencyFilePath,
            'business_name' => trim($request->business_name),
            'registration_number' => trim($request->registration_number),
            'business_address' => trim($request->business_address),
            'business_period' => trim($request->business_period),
            'no_income_period' => trim($request->no_income_period),
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now('Asia/Manila'),
        ];

        if (!empty(trim($request->previous_employer_name)) && !empty(trim($request->previous_employer_contact))) {
            $data['previous_employer_name'] = trim($request->previous_employer_name);
            $data['previous_employer_contact'] = trim($request->previous_employer_contact);
        }

        return AffidavitOfNoIncome::create($data);
    }

    private function createAffidavitOfNoFixIncome(Request $request, $address, $certOfIndigencyFilePath, $documentRequestID) {
        $data = [
            'documentRequest_id' => $documentRequestID,
            'aonfi_name' => trim($request->document_name),
            'aonfi_age' => $request->document_age,
            'aonfi_address' => $address,
            'source_income' => trim($request->source_of_income),
            'indigency' => $certOfIndigencyFilePath,
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now('Asia/Manila'),
        ];
    
        return AffidavitOfNoFixIncome::create($data);
    }

    private function createExtraJudicial($deathCertificateFilePath, $heirshipDocumentsFilePath, 
    $inventoryOfEstateFilePath, $taxClearanceFilePath, $deedOfExtraJudicialSettlementFilePath, 
    $documentRequestID) {
        $data = [
            'documentRequest_id' => $documentRequestID,
            'death_cert' => $deathCertificateFilePath,
            'heirship' => $heirshipDocumentsFilePath,
            'inv_estate' => $inventoryOfEstateFilePath,
            'tax_clearance' => $taxClearanceFilePath,
            'deed_extrajudicial' => $deedOfExtraJudicialSettlementFilePath,
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now('Asia/Manila'),
        ];
    
        return ExtraJudicial::create($data);
    }

    private function createDeedOfSale(Request $request, $documentRequestID) {
        $data = [
            'documentRequest_id' => $documentRequestID,
            'name_identity_1' => trim($request->party1_name),
            'name_identity_2' => trim($request->party2_name),
            'details' => trim($request->property_details),
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now('Asia/Manila'),
        ];
    
        return DeedOfSale::create($data);
    }

    private function createDeedOfDonation(Request $request, $address, $address2, $documentRequestID) {
        $data = [
            'documentRequest_id' => $documentRequestID,
            'donor_name' => trim($request->donor_name),
            'donor_age' => $request->donor_age,
            'donor_address' => $address,
            'donee_name' => trim($request->donee_name),
            'donee_age' => $request->donee_age,
            'donee_address' => $address2,
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now('Asia/Manila'),
        ];
    
        return DeedOfDonation::create($data);
    }
    
    private function prepareMailData(Request $request, $documentRequestID) {
        return [
            'title' => 'Mail from PedroAID',
            'name' => trim($request->name),
            'message' => 'Document Request Received!',
            'tracking_id' => $documentRequestID,
            'link' => 'http://127.0.0.1:8000/tracker/documentRequest-details/check-details?documentRequest_id=' . $documentRequestID . '&email=' . trim($request->email),
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
        return redirect()->route('documentRequestForm')->with('success', 'Document Request Sent Successfully! Kindly check your email for confirmation.');
    }
    
    private function failedResponse() {
        return redirect()->route('documentRequestForm')->with('failed', 'Failed to Send Document Request!');
    }   
}
