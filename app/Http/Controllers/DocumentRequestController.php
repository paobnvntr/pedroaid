<?php

namespace App\Http\Controllers;

use App\Models\AffidavitOfGuardianship;
use App\Models\AffidavitOfLoss;
use App\Models\AffidavitOfNoFixIncome;
use App\Models\AffidavitOfNoIncome;
use App\Models\DeedOfDonation;
use App\Models\DeedOfSale;
use App\Models\DocumentRequest;
use App\Models\DocumentRequestMessage;
use App\Models\ExtraJudicial;
use App\Models\Heir;
use App\Models\OtherDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Logs;
use App\Mail\DocumentRequestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use App\Models\Feedback;
use Illuminate\Validation\Rule;

class DocumentRequestController extends Controller
{
    public function index()
    {
        $approved = DocumentRequest::where('documentRequest_status', 'Approved')
                                         ->orderBy('created_at', 'ASC')
                                         ->get();

        $processing = DocumentRequest::where('documentRequest_status', 'Processing')
                                         ->orderBy('created_at', 'ASC')
                                         ->get();

        $on_hold = DocumentRequest::where('documentRequest_status', 'On Hold')
                                         ->orderBy('created_at', 'ASC')
                                         ->get();

        $cancelled = DocumentRequest::where('documentRequest_status', 'Cancelled')
                                         ->orderBy('created_at', 'ASC')
                                         ->get();
        
        return view('document-request.index', compact('approved', 'processing', 'on_hold', 'cancelled'));  
    }

    public function pendingDocumentRequest()
    {
        $pending = DocumentRequest::where('documentRequest_status', 'Pending')
                                         ->orderBy('created_at', 'ASC')
                                         ->get();
        $declined = DocumentRequest::where('documentRequest_status', 'Declined')
                                         ->orderBy('created_at', 'ASC')
                                         ->get();
        return view('document-request.pendingDocumentRequest', compact('pending', 'declined'));   
    }

    public function finishedDocumentRequest()
    {
        $to_claim = DocumentRequest::where('documentRequest_status', 'To Claim')
                                         ->orderBy('created_at', 'ASC')
                                         ->get();

        $claimed = DocumentRequest::where('documentRequest_status', 'Claimed')
                                         ->orderBy('created_at', 'ASC')
                                         ->get();

        $unclaimed = DocumentRequest::where('documentRequest_status', 'Unclaimed')
                                         ->orderBy('created_at', 'ASC')
                                         ->get();

        return view('document-request.finishedDocumentRequest', compact('to_claim', 'claimed', 'unclaimed'));   
    }

    public function addDocumentRequest()
    {
        return view('document-request.addDocumentRequest');   
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
        $user = Auth::user()->username;
        $address = $this->generateAddress($request);
        $documentAddress = $this->generateDocumentAddress($request);
        
        $documentRequestID = DocumentRequest::generateUniqueDocumentRequestID();
    
        $createDocumentRequest = $this->createDocumentRequest($request, $address, $documentRequestID);

        if ($request->document_type == 'Affidavit of Loss') {
            $validIdFrontFilePath = $this->uploadValidIdFrontAffidavitOfLoss($request);
            $validIdBackFilePath = $this->uploadValidIdBackAffidavitOfLoss($request);
    
            $createAffidavitOfLoss = $this->createAffidavitOfLoss($request, $documentAddress, $validIdFrontFilePath, $validIdBackFilePath, $documentRequestID);
            
            if ($createDocumentRequest && $createAffidavitOfLoss) {
                $this->logSuccess($user, $documentRequestID);
                $mailData = $this->prepareMailData($request, $documentRequestID);
                $mailSubject = $this->prepareMailSubject($documentRequestID, $request);
    
                $this->sendMail($request->email, $mailData, $mailSubject);
    
                return $this->successRedirect();
            } else {
                return $this->failedRedirect();
            }
        } else if ($request->document_type == 'Affidavit of Guardianship') {
            $validIdFrontFilePath = $this->uploadValidIdFrontAffidavitOfGuardianship($request);
            $validIdBackFilePath = $this->uploadValidIdBackAffidavitOfGuardianship($request);

            $createAffidavitOfGuardianship = $this->createAffidavitOfGuardianship($request, $documentAddress, $validIdFrontFilePath, $validIdBackFilePath, $documentRequestID);
    
            if ($createDocumentRequest && $createAffidavitOfGuardianship) {
                $this->logSuccess($user, $documentRequestID);
                $mailData = $this->prepareMailData($request, $documentRequestID);
                $mailSubject = $this->prepareMailSubject($documentRequestID, $request);
    
                $this->sendMail($request->email, $mailData, $mailSubject);
    
                return $this->successRedirect();
            } else {
                return $this->failedRedirect();
            }
        } else if ($request->document_type == 'Affidavit of No income') {
            $certOfIndigencyFilePath = $this->uploadCertOfIndigency($request);
            $validIdFrontFilePath = $this->uploadValidIdFrontAONI($request);
            $validIdBackFilePath = $this->uploadValidIdBackAONI($request);
    
            $createAffidavitOfNoIncome = $this->createAffidavitOfNoIncome($request, $documentAddress, $certOfIndigencyFilePath, $validIdFrontFilePath, $validIdBackFilePath, $documentRequestID);
    
            if ($createDocumentRequest && $createAffidavitOfNoIncome) {
                $this->logSuccess($user, $documentRequestID);
                $mailData = $this->prepareMailData($request, $documentRequestID);
                $mailSubject = $this->prepareMailSubject($documentRequestID, $request);
    
                $this->sendMail($request->email, $mailData, $mailSubject);
    
                return $this->successRedirect();
            } else {
                return $this->failedRedirect();
            }
        } else if ($request->document_type == 'Affidavit of No fix income') {
            $certOfResidencyFilePath = $this->uploadCertOfResidency($request);
            $validIdFrontFilePath = $this->uploadValidIdFrontAONFI($request);
            $validIdBackFilePath = $this->uploadValidIdBackAONFI($request);
    
            $createAffidavitOfNoFixIncome = $this->createAffidavitOfNoFixIncome($request, $documentAddress, $certOfResidencyFilePath, $validIdFrontFilePath, $validIdBackFilePath, $documentRequestID);
    
            if ($createDocumentRequest && $createAffidavitOfNoFixIncome) {
                $this->logSuccess($user, $documentRequestID);
                $mailData = $this->prepareMailData($request, $documentRequestID);
                $mailSubject = $this->prepareMailSubject($documentRequestID, $request);
    
                $this->sendMail($request->email, $mailData, $mailSubject);
    
                return $this->successRedirect();
            } else {
                return $this->failedRedirect();
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
                    $this->logSuccess($user, $documentRequestID);
                    $mailData = $this->prepareMailData($request, $documentRequestID);
                    $mailSubject = $this->prepareMailSubject($documentRequestID, $request);
        
                    $this->sendMail($request->email, $mailData, $mailSubject);
        
                    return $this->successRedirect();
                } else {
                    return $this->failedRedirect();
                }
            } else {
                $survivingSpouseName = $request->surviving_spouse;
                $spouseValidIdFrontFilePath = $this->uploadSpouseValidIdFront($request);
                $spouseValidIdBackFilePath = $this->uploadSpouseValidIdBack($request);

                $createExtraJudicial = $this->createExtraJudicial($request, $titleOfPropertyFilePath, $survivingSpouseName, $spouseValidIdFrontFilePath, $spouseValidIdBackFilePath, $documentRequestID);

                if ($createDocumentRequest && $createExtraJudicial) {
                    $this->logSuccess($user, $documentRequestID);
                    $mailData = $this->prepareMailData($request, $documentRequestID);
                    $mailSubject = $this->prepareMailSubject($documentRequestID, $request);
        
                    $this->sendMail($request->email, $mailData, $mailSubject);
        
                    return $this->successRedirect();
                } else {
                    return $this->failedRedirect();
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
                $this->logSuccess($user, $documentRequestID);
                $mailData = $this->prepareMailData($request, $documentRequestID);
                $mailSubject = $this->prepareMailSubject($documentRequestID, $request);
    
                $this->sendMail($request->email, $mailData, $mailSubject);
    
                return $this->successRedirect();
            } else {
                return $this->failedRedirect();
            }
        } else if ($request->document_type == 'Deed of Donation') {
            $donorValidIdFrontFilePath = $this->uploadDonorValidIdFront($request);
            $donorValidIdBackFilePath = $this->uploadDonorValidIdBack($request);
            $document2Address = $this->generateDocument2Address($request);
            $doneeValidIdFrontFilePath = $this->uploadDoneeValidIdFront($request);
            $doneeValidIdBackFilePath = $this->uploadDoneeValidIdBack($request);

            $createDeedOfDonation = $this->createDeedOfDonation($request, $documentAddress, $donorValidIdFrontFilePath, $donorValidIdBackFilePath, $document2Address, $doneeValidIdFrontFilePath, $doneeValidIdBackFilePath, $documentRequestID);
    
            if ($createDocumentRequest && $createDeedOfDonation) {
                $this->logSuccess($user, $documentRequestID);
                $mailData = $this->prepareMailData($request, $documentRequestID);
                $mailSubject = $this->prepareMailSubject($documentRequestID, $request);
    
                $this->sendMail($request->email, $mailData, $mailSubject);
    
                return $this->successRedirect();
            } else {
                return $this->failedRedirect();
            }
        } else if ($request->document_type == 'Other Document') {
            $validIdFrontFilePath = $this->uploadValidIdFrontOther($request);
            $validIdBackFilePath = $this->uploadValidIdBackOther($request);
            
            $createOtherDocument = $this->createOtherDocument($validIdFrontFilePath, $validIdBackFilePath, $documentRequestID);
    
            if ($createDocumentRequest && $createOtherDocument) {
                $this->logSuccess($user, $documentRequestID);
                $mailData = $this->prepareMailData($request, $documentRequestID);
                $mailSubject = $this->prepareMailSubject($documentRequestID, $request);
    
                $this->sendMail($request->email, $mailData, $mailSubject);
    
                return $this->successRedirect();
            } else {
                return $this->failedRedirect();
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
    
    private function logSuccess($user, $documentRequestID) {
        Logs::create([
            'type' => 'Add Document Request',
            'user' => $user,
            'subject' => 'Add Document Request Success',
            'message' => $user . ' has successfully added Document Request ID: ' . $documentRequestID . '.',
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now('Asia/Manila'),
        ]);
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
    
    private function successRedirect() {
        return redirect()->route('document-request.pendingDocumentRequest')->with('success', 'Document Request Sent Successfully!');
    }
    
    private function failedRedirect() {
        return redirect()->route('document-request.addDocumentRequest')->with('failed', 'Failed to Send Document Request!');
    }
    
    public function documentRequestDetails(string $documentRequest_id) {
        $documentRequest = DocumentRequest::where('documentRequest_id', $documentRequest_id)->first();
        $messages = DocumentRequestMessage::where('documentRequest_id', $documentRequest_id)->where('email', $documentRequest->email)->get();
        $staffName = Auth::user()->name;
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
        } else if ($documentRequest->document_type == 'Other Document') {
            $additional_info = OtherDocument::where('documentRequest_id', $documentRequest_id)->get()->first();
        }

        $feedback = Feedback::where('transaction_id', $documentRequest_id)->where('transaction_type', 'Document Request')->get();
        $rating = '';
        $comment = '';
        if($feedback->count() > 0) {
            $feedback = Feedback::where('transaction_id', $documentRequest_id)->where('transaction_type', 'Document Request')->get()->first();
            $rating = $feedback->rating;
            $comment = $feedback->comment;
        }  

        return view('document-request.documentRequestDetails', compact('documentRequest', 'messages', 'staffName', 'rating', 'comment', 'feedback', 'additional_info', 'heirs_info'));
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
        $this->logMessageSent($id, $documentRequest->name);
        $this->sendDocumentRequestMessageEmail($documentRequest, $id, $documentRequest_email);
    
        return redirect()
            ->route('document-request.documentRequestDetails', $id)
            ->with('success', 'Message Sent!');
    }
    
    private function validateMessageRequest(Request $request) {
        return Validator::make($request->all(), [
            '_token' => 'required',
            'message' => 'required',
        ]);
    }
    
    private function getDocumentRequestById(string $id) {
        return DocumentRequest::where('documentRequest_id', $id)->firstOrFail();
    }
    
    private function saveDocumentRequestMessage(string $id, string $documentRequest_email, string $message) {
        DocumentRequestMessage::create([
            'documentRequest_id' => $id,
            'email' => $documentRequest_email,
            'staff_name' => Auth::user()->name,
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
    
    private function logMessageSent(string $id, string $documentRequestName) {
        $user = Auth::user()->username;
        Logs::create([
            'type' => 'Message Document Request',
            'user' => $user,
            'subject' => 'Message Document Request Success',
            'message' => "$user has successfully sent a message to Document Request ID: $id for $documentRequestName.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }
    
    private function sendDocumentRequestMessageEmail($documentRequest, string $id, string $documentRequest_email) {
        $mailData = [
            'title' => 'Mail from PedroAID',
            'name' => $documentRequest->name,
            'message' => 'You received a message!',
            'tracking_id' => $id,
            'link' => route('documentRequestDetails', ['documentRequest_id' => $id, 'email' => $documentRequest_email]),
        ];
    
        $mailSubject = "[#$id] New Message: Document Request from $documentRequest->name";
    
        Mail::to($documentRequest_email)->send(new DocumentRequestMail($mailData, $mailSubject));
    }

    public function approveDocumentRequest(string $id) {
        $this->updateDocumentRequestStatus($id, 'Approved');
        return redirect()->route('document-request')->with('success', 'Document Request is approved.');
    }

    public function declineDocumentRequest(string $id) {
        $this->updateDocumentRequestStatus($id, 'Declined');
        return redirect()->route('document-request.pendingDocumentRequest')->with('success', 'Document Request is declined.');
    }

    public function processDocumentRequest(string $id) {
        $this->updateDocumentRequestStatus($id, 'Processing');
        return redirect()->route('document-request')->with('success', 'Document Request is processing.');
    }

    public function holdDocumentRequest(string $id) {
        $this->updateDocumentRequestStatus($id, 'On Hold');
        return redirect()->route('document-request')->with('success', 'Document Request is on hold.');
    }

    public function cancelDocumentRequest(string $id) {
        $this->updateDocumentRequestStatus($id, 'Cancelled');
        return redirect()->route('document-request')->with('success', 'Document Request is cancelled.');
    }

    public function toClaimDocumentRequest(string $id) {
        $this->updateDocumentRequestStatus($id, 'To Claim');
        return redirect()->route('document-request.finishedDocumentRequest')->with('success', 'Document Request is to claim.');
    }

    public function claimedDocumentRequest(string $id) {
        $this->updateDocumentRequestStatus($id, 'Claimed');
        return redirect()->route('document-request.finishedDocumentRequest')->with('success', 'Document Request is claimed.');
    }

    public function unclaimedDocumentRequest(string $id) {
        $this->updateDocumentRequestStatus($id, 'Unclaimed');
        return redirect()->route('document-request.finishedDocumentRequest')->with('success', 'Document Request is unclaimed.');
    }

    private function updateDocumentRequestStatus(string $id, string $status) {
        $documentRequest = DocumentRequest::where('documentRequest_id', $id)->first();
    
        if (!$documentRequest) {
            // Handle the case where the appointment is not found
            return redirect()->route('document-request.documentRequestDetails', $id)->with('failed', 'Request not found!');
        }
    
        DocumentRequest::where('documentRequest_id', $id)->update([
            'documentRequest_status' => $status,
            'updated_at' => now('Asia/Manila'),
            'date_claimed' => $status == 'Claimed' ? now('Asia/Manila') : null,
        ]);
    
        $documentRequest_email = $documentRequest->email;

        if($status == 'Claimed') {
            $additional_message = 'Kindly fill up the feedback form.';
        } elseif($status == 'Unclaimed') {
            $additional_message = 'Please claim your document request.';
        } else {
            $additional_message = '';
        }
    
        $mailData = [
            'title' => 'Mail from PedroAID',
            'name' => $documentRequest->name,
            'message' => 'Your document request status has been updated! See details below.',
            'tracking_id' => $id,
            'link' => route('documentRequestDetails', ['documentRequest_id' => $id, 'email' => $documentRequest_email]),
            'additional_message' => $additional_message,
        ];
    
        $mailSubject = "[#$id] $status Request: Document Request from $documentRequest->name";
    
        $this->sendAppointmentEmail($documentRequest_email, $mailData, $mailSubject);
        
        $user = Auth::user()->username;
        $this->logAppointmentStatus($status, $user, $id);
    }

    private function logAppointmentStatus(string $status, string $user, string $id) {
        Logs::create([
            'type' => 'Document Request Status',
            'user' => $user,
            'subject' => 'Document Request Status Success',
            'message' => "$user has successfully $status Document Request ID: $id.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    private function sendAppointmentEmail(string $email, array $data, string $subject) {
        Mail::to($email)->send(new DocumentRequestMail($data, $subject));
    }  

    public function editDocumentRequest(string $id)
    {
        $documentRequest = DocumentRequest::where('documentRequest_id', $id)->get()->first();
        $address = explode(', ', $documentRequest->address);

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

        $document_city = '';
        $document_city_2 = '';
        $document_final_barangay = '';
        $document_street = '';
        $document_other_city = '';
        $document_other_barangay = '';
        $document_other_street = '';
        $document_final_barangay_2 = '';
        $document_street_2 = '';
        $document_other_city_2 = '';
        $document_other_barangay_2 = '';
        $document_other_street_2 = '';

        if($documentRequest->document_type == 'Affidavit of Loss') {
            $additional_info = AffidavitOfLoss::where('documentRequest_id', $id)->get()->first();

            $document_address = explode(', ', $additional_info->aol_address);
            $document_city = $document_address[2];
            $document_city = trim($document_city);
    
            $document_final_barangay = '';
            $document_street = '';
            $document_other_city = '';
            $document_other_barangay = '';
            $document_other_street = '';
    
            if($document_city == 'San Pedro City') {
                $document_barangay = $document_address[1];
                $trim_document_barangay = explode('. ', $document_barangay);
                $document_final_barangay = $trim_document_barangay[1];
                $document_final_barangay = trim($document_final_barangay);
        
                $document_street = $document_address[0];
                $document_street = trim($document_street);
            } else {
                $document_other_city = $document_city;
                
                $document_other_barangay = $document_address[1];
                $document_other_barangay = trim($document_other_barangay);
    
                $document_other_street = $document_address[0];
                $document_other_street = trim($document_other_street);
            }
        } else if ($documentRequest->document_type == 'Affidavit of Guardianship') {
            $additional_info = AffidavitOfGuardianship::where('documentRequest_id', $id)->get()->first();

            $document_address = explode(', ', $additional_info->guardian_address);
            $document_city = $document_address[2];
            $document_city = trim($document_city);
    
            $document_final_barangay = '';
            $document_street = '';
            $document_other_city = '';
            $document_other_barangay = '';
            $document_other_street = '';
    
            if($document_city == 'San Pedro City') {
                $document_barangay = $document_address[1];
                $trim_document_barangay = explode('. ', $document_barangay);
                $document_final_barangay = $trim_document_barangay[1];
                $document_final_barangay = trim($document_final_barangay);
        
                $document_street = $document_address[0];
                $document_street = trim($document_street);
            } else {
                $document_other_city = $document_city;
                
                $document_other_barangay = $document_address[1];
                $document_other_barangay = trim($document_other_barangay);
    
                $document_other_street = $document_address[0];
                $document_other_street = trim($document_other_street);
            }

            $document_address2 = explode(', ', $additional_info->minor_address);
            $document_city_2 = $document_address2[2];
            $document_city_2 = trim($document_city_2);
    
            $document_final_barangay_2 = '';
            $document_street_2 = '';
            $document_other_city_2 = '';
            $document_other_barangay_2 = '';
            $document_other_street_2 = '';
    
            if($document_city_2 == 'San Pedro City') {
                $document_barangay_2 = $document_address2[1];
                $trim_document_barangay_2 = explode('. ', $document_barangay_2);
                $document_final_barangay_2 = $trim_document_barangay_2[1];
                $document_final_barangay_2 = trim($document_final_barangay_2);
        
                $document_street_2 = $document_address2[0];
                $document_street_2 = trim($document_street_2);
            } else {
                $document_other_city_2 = $document_city_2;
                
                $document_other_barangay_2 = $document_address2[1];
                $document_other_barangay_2 = trim($document_other_barangay_2);

                $document_other_street_2 = $document_address2[0];
                $document_other_street_2 = trim($document_other_street_2);
            }
        } else if ($documentRequest->document_type == 'Affidavit of No income') {
            $additional_info = AffidavitOfNoIncome::where('documentRequest_id', $id)->get()->first();

            $document_address = explode(', ', $additional_info->aoni_address);
            $document_city = $document_address[2];
            $document_city = trim($document_city);
    
            $document_final_barangay = '';
            $document_street = '';
            $document_other_city = '';
            $document_other_barangay = '';
            $document_other_street = '';
    
            if($document_city == 'San Pedro City') {
                $document_barangay = $document_address[1];
                $trim_document_barangay = explode('. ', $document_barangay);
                $document_final_barangay = $trim_document_barangay[1];
                $document_final_barangay = trim($document_final_barangay);
        
                $document_street = $document_address[0];
                $document_street = trim($document_street);
            } else {
                $document_other_city = $document_city;
                
                $document_other_barangay = $document_address[1];
                $document_other_barangay = trim($document_other_barangay);
    
                $document_other_street = $document_address[0];
                $document_other_street = trim($document_other_street);
            }
        } else if ($documentRequest->document_type == 'Affidavit of No fix income') {
            $additional_info = AffidavitOfNoFixIncome::where('documentRequest_id', $id)->get()->first();

            $document_address = explode(', ', $additional_info->aonfi_address);
            $document_city = $document_address[2];
            $document_city = trim($document_city);
    
            $document_final_barangay = '';
            $document_street = '';
            $document_other_city = '';
            $document_other_barangay = '';
            $document_other_street = '';
    
            if($document_city == 'San Pedro City') {
                $document_barangay = $document_address[1];
                $trim_document_barangay = explode('. ', $document_barangay);
                $document_final_barangay = $trim_document_barangay[1];
                $document_final_barangay = trim($document_final_barangay);
        
                $document_street = $document_address[0];
                $document_street = trim($document_street);
            } else {
                $document_other_city = $document_city;
                
                $document_other_barangay = $document_address[1];
                $document_other_barangay = trim($document_other_barangay);
    
                $document_other_street = $document_address[0];
                $document_other_street = trim($document_other_street);
            }
        } else if ($documentRequest->document_type == 'Extra Judicial') {
            $additional_info = ExtraJudicial::where('documentRequest_id', $id)->get()->first();
        } else if ($documentRequest->document_type == 'Deed of Sale') {
            $additional_info = DeedOfSale::where('documentRequest_id', $id)->get()->first();
        } else if ($documentRequest->document_type == 'Deed of Donation') {
            $additional_info = DeedOfDonation::where('documentRequest_id', $id)->get()->first();

            $document_address = explode(', ', $additional_info->donor_address);
            $document_city = $document_address[2];
            $document_city = trim($document_city);
    
            $document_final_barangay = '';
            $document_street = '';
            $document_other_city = '';
            $document_other_barangay = '';
            $document_other_street = '';
    
            if($document_city == 'San Pedro City') {
                $document_barangay = $document_address[1];
                $trim_document_barangay = explode('. ', $document_barangay);
                $document_final_barangay = $trim_document_barangay[1];
                $document_final_barangay = trim($document_final_barangay);
        
                $document_street = $document_address[0];
                $document_street = trim($document_street);
            } else {
                $document_other_city = $document_city;
                
                $document_other_barangay = $document_address[1];
                $document_other_barangay = trim($document_other_barangay);
    
                $document_other_street = $document_address[0];
                $document_other_street = trim($document_other_street);
            }

            $document_address2 = explode(', ', $additional_info->donee_address);
            $document_city_2 = $document_address2[2];
            $document_city_2 = trim($document_city_2);
    
            $document_final_barangay_2 = '';
            $document_street_2 = '';
            $document_other_city_2 = '';
            $document_other_barangay_2 = '';
            $document_other_street_2 = '';
    
            if($document_city_2 == 'San Pedro City') {
                $document_barangay_2 = $document_address2[1];
                $trim_document_barangay_2 = explode('. ', $document_barangay_2);
                $document_final_barangay_2 = $trim_document_barangay_2[1];
                $document_final_barangay_2 = trim($document_final_barangay_2);
        
                $document_street_2 = $document_address2[0];
                $document_street_2 = trim($document_street_2);
            } else {
                $document_other_city_2 = $document_city_2;
                
                $document_other_barangay_2 = $document_address2[1];
                $document_other_barangay_2 = trim($document_other_barangay_2);

                $document_other_street_2 = $document_address2[0];
                $document_other_street_2 = trim($document_other_street_2);
            }
        } else if ($documentRequest->document_type == 'Other Document') {
            $additional_info = OtherDocument::where('documentRequest_id', $id)->get()->first();
        }

        return view('document-request.editDocumentRequest', compact('documentRequest', 'city', 'final_barangay', 'street', 'other_city', 'other_barangay', 'other_street', 'additional_info', 'document_city', 'document_final_barangay', 'document_street', 'document_other_city', 'document_other_barangay', 'document_other_street', 'document_city_2', 'document_final_barangay_2', 'document_street_2', 'document_other_city_2', 'document_other_barangay_2', 'document_other_street_2'));
    }

    public function validateEditSameDocumentRequestForm(Request $request) {
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

            'valid_id_front' => 'image|mimes:jpg,jpeg,png',
            'valid_id_back' => 'image|mimes:jpg,jpeg,png',
            'cedula' => 'mimes:pdf',

            'guardian_name' => 'required_if:document_type,Affidavit of Guardianship',
            'guardian_age' => 'required_if:document_type,Affidavit of Guardianship|gte:18',
            'guardian_occupation' => 'required_if:document_type,Affidavit of Guardianship',
            'barangay_clearance' => 'mimes:pdf',
            'relationship' => 'required_if:document_type,Affidavit of Guardianship',
            'minor_name' => 'required_if:document_type,Affidavit of Guardianship',
            'minor_age' => 'required_if:document_type,Affidavit of Guardianship|lt:18',
            'minor_relationship' => 'required_if:document_type,Affidavit of Guardianship',

            'certificate_of_indigency' => 'mimes:pdf',
            'previous_employer_name' => 'required_with:previous_employer_contact',
            'previous_employer_contact' => 'required_with:previous_employer_name',
            'business_name' => 'required_if:document_type,Affidavit of No income',
            'registration_number' => 'required_if:document_type,Affidavit of No income',
            'business_address' => 'required_if:document_type,Affidavit of No income',
            'business_period' => 'required_if:document_type,Affidavit of No income',
            'no_income_period' => 'required_if:document_type,Affidavit of No income',

            'source_of_income' => 'required_if:document_type,Affidavit of No fix income',

            'death_certificate' => 'mimes:pdf',
            'heirship_documents' => 'mimes:pdf',
            'inventory_of_estate' => 'mimes:pdf',
            'tax_clearance' => 'mimes:pdf',
            'deed_of_extrajudicial_settlement' => 'mimes:pdf',

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

            'guardian_name.required_if' => 'The name field is required.',
            'guardian_age.required_if' => 'The age field is required.',
            'guardian_occupation.required_if' => 'The occupation field is required.',
            'relationship.required_if' => 'The relationship field is required.',
            'minor_name.required_if' => 'The name field is required.',
            'minor_age.required_if' => 'The age field is required.',
            'minor_relationship.required_if' => 'The relationship field is required.',

            'previous_employer_name.required_with' => 'The previous employer name field is required.',
            'previous_employer_contact.required_with' => 'The previous employer contact field is required.',
            'business_name.required_if' => 'The business name field is required.',
            'registration_number.required_if' => 'The registration number field is required.',
            'business_address.required_if' => 'The business address field is required.',
            'business_period.required_if' => 'The business period field is required.',
            'no_income_period.required_if' => 'The no income period field is required.',

            'source_of_income.required_if' => 'The source of income field is required.',

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

    public function validateEditNewDocumentRequestForm(Request $request) {
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

            'valid_id_front' => 'required_if:document_type,Affidavit of Loss,Other Document|image|mimes:jpg,jpeg,png',
            'valid_id_back' => 'required_if:document_type,Affidavit of Loss,Other Document|image|mimes:jpg,jpeg,png',
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

    public function updateDocumentRequest(Request $request, string $id) {
        $user = Auth::user()->name;
        $documentRequest = DocumentRequest::where('documentRequest_id', $id)->first();
    
        if ($this->shouldUpdateDocumentRequest($request, $documentRequest)){
            $address = $this->generateEditAddress($request);
            $updateDocumentRequestDetails = $this->updateDocumentRequestDetails($request, $address, $id);

            if($request->document_type == $documentRequest->document_type) {
                if($request->document_type == 'Affidavit of Loss') {
                        $document_address = $this->generateEditDocumentAddress($request);
                        
                        if($this->shouldUpdateAffidavitOfLoss($request, $id)) {
                            $updateAffidavitOfLossDetails = $this->updateAffidavitOfLoss($request, $document_address, $id);

                            if($updateDocumentRequestDetails && $updateAffidavitOfLossDetails) {
                                $this->logDocumentRequestEditSuccess($user, $id);
    
                                return $this->successEditRedirect($id);
                            } else {
                                return $this->failedEditRedirect($id);
                            }
                        } else {
                            if($updateDocumentRequestDetails) {
                                $this->logDocumentRequestEditSuccess($user, $id);
    
                                return $this->successEditRedirect($id);
                            } else {
                                return $this->failedEditRedirect($id);
                            }
                        }

                } else if($request->document_type == 'Affidavit of Guardianship') {

                    $document_address = $this->generateEditDocumentAddress($request);
                    $document_address2 = $this->generateEditDocument2Address($request);

                    if($this->shouldUpdateAffidavitOfGuardianship($request, $id)) {
                        $updateAffidavitOfGuardianshipDetails = $this->updateAffidavitOfGuardianship($request, $document_address, $document_address2, $id);

                        if($updateDocumentRequestDetails && $updateAffidavitOfGuardianshipDetails) {
                            $this->logDocumentRequestEditSuccess($user, $id);

                            return $this->successEditRedirect($id);
                        } else {
                            return $this->failedEditRedirect($id);
                        }
                    } else {
                        if($updateDocumentRequestDetails) {
                            $this->logDocumentRequestEditSuccess($user, $id);

                            return $this->successEditRedirect($id);
                        } else {
                            return $this->failedEditRedirect($id);
                        }
                    }
                    
                } else if($request->document_type == 'Affidavit of No income') {

                    $document_address = $this->generateEditDocumentAddress($request);
                    
                    if($this->shouldUpdateAffidavitOfNoIncome($request, $id)) {
                        $updateAffidavitOfNoIncomeDetails = $this->updateAffidavitOfNoIncome($request, $document_address, $id);

                        if($updateDocumentRequestDetails && $updateAffidavitOfNoIncomeDetails) {
                            $this->logDocumentRequestEditSuccess($user, $id);

                            return $this->successEditRedirect($id);
                        } else {
                            return $this->failedEditRedirect($id);
                        }
                    } else {
                        if($updateDocumentRequestDetails) {
                            $this->logDocumentRequestEditSuccess($user, $id);

                            return $this->successEditRedirect($id);
                        } else {
                            return $this->failedEditRedirect($id);
                        }
                    }

                } else if($request->document_type == 'Affidavit of No fix income') {

                    $document_address = $this->generateEditDocumentAddress($request);
                    
                    if($this->shouldUpdateAffidavitOfNoFixIncome($request, $id)) {
                        $updateAffidavitOfNoFixIncomeDetails = $this->updateAffidavitOfNoFixIncome($request, $document_address, $id);

                        if($updateDocumentRequestDetails && $updateAffidavitOfNoFixIncomeDetails) {
                            $this->logDocumentRequestEditSuccess($user, $id);

                            return $this->successEditRedirect($id);
                        } else {
                            return $this->failedEditRedirect($id);
                        }
                    } else {
                        if($updateDocumentRequestDetails) {
                            $this->logDocumentRequestEditSuccess($user, $id);

                            return $this->successEditRedirect($id);
                        } else {
                            return $this->failedEditRedirect($id);
                        }
                    }

                } else if($request->document_type == 'Extra Judicial') {

                    if($this->shouldUpdateExtraJudicial($request)) {
                        $updateExtraJudicialDetails = $this->updateExtraJudicial($request, $id);

                        if($updateDocumentRequestDetails && $updateExtraJudicialDetails) {
                            $this->logDocumentRequestEditSuccess($user, $id);

                            return $this->successEditRedirect($id);
                        } else {
                            return $this->failedEditRedirect($id);
                        }
                    } else {
                        if($updateDocumentRequestDetails) {
                            $this->logDocumentRequestEditSuccess($user, $id);

                            return $this->successEditRedirect($id);
                        } else {
                            return $this->failedEditRedirect($id);
                        }
                    }
                    
                } else if($request->document_type == 'Deed of Sale') {
                        
                    if($this->shouldUpdateDeedOfSale($request, $id)) {
                        $updateDeedOfSaleDetails = $this->updateDeedOfSale($request, $id);

                        if($updateDocumentRequestDetails && $updateDeedOfSaleDetails) {
                            $this->logDocumentRequestEditSuccess($user, $id);

                            return $this->successEditRedirect($id);
                        } else {
                            return $this->failedEditRedirect($id);
                        }
                    } else {
                        if($updateDocumentRequestDetails) {
                            $this->logDocumentRequestEditSuccess($user, $id);

                            return $this->successEditRedirect($id);
                        } else {
                            return $this->failedEditRedirect($id);
                        }
                    }

                } else if($request->document_type == 'Deed of Donation') {
                    $document_address = $this->generateEditDocumentAddress($request);
                    $document_address2 = $this->generateEditDocument2Address($request);
                    
                    if($this->shouldUpdateDeedOfDonation($request, $id)) {
                        $updateDeedOfDonationDetails = $this->updateDeedOfDonation($request, $document_address, $document_address2, $id);

                        if($updateDocumentRequestDetails && $updateDeedOfDonationDetails) {
                            $this->logDocumentRequestEditSuccess($user, $id);

                            return $this->successEditRedirect($id);
                        } else {
                            return $this->failedEditRedirect($id);
                        }
                    } else {
                        if($updateDocumentRequestDetails) {
                            $this->logDocumentRequestEditSuccess($user, $id);

                            return $this->successEditRedirect($id);
                        } else {
                            return $this->failedEditRedirect($id);
                        }
                    }

                } else if($request->document_type == 'Other Document') {
                    
                    if($this->shouldUpdateOtherDocument($request, $id)) {
                        $updateOtherDocumentDetails = $this->updateOtherDocument($request, $id);

                        if($updateDocumentRequestDetails && $updateOtherDocumentDetails) {
                            $this->logDocumentRequestEditSuccess($user, $id);

                            return $this->successEditRedirect($id);
                        } else {
                            return $this->failedEditRedirect($id);
                        }
                    } else {
                        if($updateDocumentRequestDetails) {
                            $this->logDocumentRequestEditSuccess($user, $id);

                            return $this->successEditRedirect($id);
                        } else {
                            return $this->failedEditRedirect($id);
                        }
                    }
                
                } else {
                    return $this->failedEditRedirect($id);
                }
            } else {
                if($request->document_type == 'Affidavit of Loss') {
                    
                    if($documentRequest->document_type == 'Affidavit of Loss') {

                        $additionalInfoDetails = AffidavitOfLoss::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->valid_id_front))) {
                            unlink(public_path($additionalInfoDetails->valid_id_front));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->valid_id_back))) {
                            unlink(public_path($additionalInfoDetails->valid_id_back));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->cedula))) {
                            unlink(public_path($additionalInfoDetails->cedula));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfLoss::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Affidavit of Guardianship') {
            
                        $additionalInfoDetails = AffidavitOfGuardianship::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->guardian_brgy_clearance))) {
                            unlink(public_path($additionalInfoDetails->guardian_brgy_clearance));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfGuardianship::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Affidavit of No income') {
            
                        $additionalInfoDetails = AffidavitOfNoIncome::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->certificate_of_indigency))) {
                            unlink(public_path($additionalInfoDetails->certificate_of_indigency));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfNoIncome::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Affidavit of No fix income') {
            
                        $additionalInfoDetails = AffidavitOfNoFixIncome::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->indigency))) {
                            unlink(public_path($additionalInfoDetails->indigency));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfNoFixIncome::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Extra Judicial') {
            
                        $additionalInfoDetails = ExtraJudicial::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->death_cert))) {
                            unlink(public_path($additionalInfoDetails->death_cert));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->heirship))) {
                            unlink(public_path($additionalInfoDetails->heirship));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->inv_estate))) {
                            unlink(public_path($additionalInfoDetails->inv_estate));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->tax_clearance))) {
                            unlink(public_path($additionalInfoDetails->tax_clearance));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->deed_extrajudicial))) {
                            unlink(public_path($additionalInfoDetails->deed_extrajudicial));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        ExtraJudicial::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Deed of Sale') {
            
                        DeedOfSale::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Deed of Donation') {
            
                        DeedOfDonation::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Other Document') {

                        $additionalInfoDetails = OtherDocument::where('documentRequest_id', $id)->first();

                        if (file_exists(public_path($additionalInfoDetails->valid_id_front))) {
                            unlink(public_path($additionalInfoDetails->valid_id_front));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->valid_id_back))) {
                            unlink(public_path($additionalInfoDetails->valid_id_back));
                        } else {
                            return $this->failedEditRedirect($id);
                        }

                        OtherDocument::where('documentRequest_id', $id)->delete();
            
                    }

                    $documentAddress = $this->generateEditDocumentAddress($request);
                    $validIdFrontFilePath = $this->uploadEditValidIdFront($request);
                    $validIdBackFilePath = $this->uploadEditValidIdBack($request);
                    $cedulaFilePath = $this->uploadEditCedula($request);
    
                    $createAffidavitOfLoss = $this->createAffidavitOfLoss($request, $documentAddress, $validIdFrontFilePath, $validIdBackFilePath, $cedulaFilePath, $id);

                    if($updateDocumentRequestDetails && $createAffidavitOfLoss) {
                        $this->logDocumentRequestEditSuccess($user, $id);
    
                        return $this->successEditRedirect($id);
                    } else {
                        return $this->failedEditRedirect($id);
                    }
            
                } else if($request->document_type == 'Affidavit of Guardianship') {

                    if($documentRequest->document_type == 'Affidavit of Loss') {

                        $additionalInfoDetails = AffidavitOfLoss::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->valid_id_front))) {
                            unlink(public_path($additionalInfoDetails->valid_id_front));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->valid_id_back))) {
                            unlink(public_path($additionalInfoDetails->valid_id_back));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->cedula))) {
                            unlink(public_path($additionalInfoDetails->cedula));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfLoss::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Affidavit of Guardianship') {
            
                        $additionalInfoDetails = AffidavitOfGuardianship::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->guardian_brgy_clearance))) {
                            unlink(public_path($additionalInfoDetails->guardian_brgy_clearance));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfGuardianship::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Affidavit of No income') {
            
                        $additionalInfoDetails = AffidavitOfNoIncome::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->certificate_of_indigency))) {
                            unlink(public_path($additionalInfoDetails->certificate_of_indigency));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfNoIncome::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Affidavit of No fix income') {
            
                        $additionalInfoDetails = AffidavitOfNoFixIncome::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->indigency))) {
                            unlink(public_path($additionalInfoDetails->indigency));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfNoFixIncome::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Extra Judicial') {
            
                        $additionalInfoDetails = ExtraJudicial::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->death_cert))) {
                            unlink(public_path($additionalInfoDetails->death_cert));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->heirship))) {
                            unlink(public_path($additionalInfoDetails->heirship));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->inv_estate))) {
                            unlink(public_path($additionalInfoDetails->inv_estate));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->tax_clearance))) {
                            unlink(public_path($additionalInfoDetails->tax_clearance));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->deed_extrajudicial))) {
                            unlink(public_path($additionalInfoDetails->deed_extrajudicial));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        ExtraJudicial::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Deed of Sale') {
            
                        DeedOfSale::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Deed of Donation') {
            
                        DeedOfDonation::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Other Document') {
                        
                        $additionalInfoDetails = OtherDocument::where('documentRequest_id', $id)->first();

                        if (file_exists(public_path($additionalInfoDetails->valid_id_front))) {
                            unlink(public_path($additionalInfoDetails->valid_id_front));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->valid_id_back))) {
                            unlink(public_path($additionalInfoDetails->valid_id_back));
                        } else {
                            return $this->failedEditRedirect($id);
                        }

                        OtherDocument::where('documentRequest_id', $id)->delete();
            
                    }

                    $documentAddress = $this->generateEditDocumentAddress($request);
                    $documentAddress2 = $this->generateEditDocument2Address($request);
                    $barangayClearanceFilePath = $this->uploadEditBarangayClearanceAOG($request);
    
                    $createAffidavitOfGuardianship = $this->createAffidavitOfGuardianship($request, $documentAddress, $documentAddress2, $barangayClearanceFilePath, $id);

                    if($updateDocumentRequestDetails && $createAffidavitOfGuardianship) {
                        $this->logDocumentRequestEditSuccess($user, $id);
    
                        return $this->successEditRedirect($id);
                    } else {
                        return $this->failedEditRedirect($id);
                    }
                    
                } else if($request->document_type == 'Affidavit of No income') {

                    if($documentRequest->document_type == 'Affidavit of Loss') {

                        $additionalInfoDetails = AffidavitOfLoss::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->valid_id_front))) {
                            unlink(public_path($additionalInfoDetails->valid_id_front));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->valid_id_back))) {
                            unlink(public_path($additionalInfoDetails->valid_id_back));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->cedula))) {
                            unlink(public_path($additionalInfoDetails->cedula));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfLoss::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Affidavit of Guardianship') {
            
                        $additionalInfoDetails = AffidavitOfGuardianship::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->guardian_brgy_clearance))) {
                            unlink(public_path($additionalInfoDetails->guardian_brgy_clearance));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfGuardianship::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Affidavit of No income') {
            
                        $additionalInfoDetails = AffidavitOfNoIncome::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->certificate_of_indigency))) {
                            unlink(public_path($additionalInfoDetails->certificate_of_indigency));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfNoIncome::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Affidavit of No fix income') {
            
                        $additionalInfoDetails = AffidavitOfNoFixIncome::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->indigency))) {
                            unlink(public_path($additionalInfoDetails->indigency));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfNoFixIncome::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Extra Judicial') {
            
                        $additionalInfoDetails = ExtraJudicial::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->death_cert))) {
                            unlink(public_path($additionalInfoDetails->death_cert));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->heirship))) {
                            unlink(public_path($additionalInfoDetails->heirship));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->inv_estate))) {
                            unlink(public_path($additionalInfoDetails->inv_estate));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->tax_clearance))) {
                            unlink(public_path($additionalInfoDetails->tax_clearance));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->deed_extrajudicial))) {
                            unlink(public_path($additionalInfoDetails->deed_extrajudicial));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        ExtraJudicial::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Deed of Sale') {
            
                        DeedOfSale::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Deed of Donation') {
            
                        DeedOfDonation::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Other Document') {
                        
                        $additionalInfoDetails = OtherDocument::where('documentRequest_id', $id)->first();

                        if (file_exists(public_path($additionalInfoDetails->valid_id_front))) {
                            unlink(public_path($additionalInfoDetails->valid_id_front));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->valid_id_back))) {
                            unlink(public_path($additionalInfoDetails->valid_id_back));
                        } else {
                            return $this->failedEditRedirect($id);
                        }

                        OtherDocument::where('documentRequest_id', $id)->delete();
            
                    }

                    $documentAddress = $this->generateEditDocumentAddress($request);
                    $certificateOfIndigencyFilePath = $this->uploadEditCertOfIndigencyAONI($request);

                    $createAffidavitOfNoIncome = $this->createAffidavitOfNoIncome($request, $documentAddress, $certificateOfIndigencyFilePath, $id);

                    if($updateDocumentRequestDetails && $createAffidavitOfNoIncome) {
                        $this->logDocumentRequestEditSuccess($user, $id);
    
                        return $this->successEditRedirect($id);
                    } else {
                        return $this->failedEditRedirect($id);
                    }

                } else if($request->document_type == 'Affidavit of No fix income') {

                    if($documentRequest->document_type == 'Affidavit of Loss') {

                        $additionalInfoDetails = AffidavitOfLoss::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->valid_id_front))) {
                            unlink(public_path($additionalInfoDetails->valid_id_front));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->valid_id_back))) {
                            unlink(public_path($additionalInfoDetails->valid_id_back));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->cedula))) {
                            unlink(public_path($additionalInfoDetails->cedula));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfLoss::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Affidavit of Guardianship') {
            
                        $additionalInfoDetails = AffidavitOfGuardianship::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->guardian_brgy_clearance))) {
                            unlink(public_path($additionalInfoDetails->guardian_brgy_clearance));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfGuardianship::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Affidavit of No income') {
            
                        $additionalInfoDetails = AffidavitOfNoIncome::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->certificate_of_indigency))) {
                            unlink(public_path($additionalInfoDetails->certificate_of_indigency));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfNoIncome::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Affidavit of No fix income') {
            
                        $additionalInfoDetails = AffidavitOfNoFixIncome::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->indigency))) {
                            unlink(public_path($additionalInfoDetails->indigency));
                        } else {
                            return $this->failedEditRedirect($id);
                        }

                        AffidavitOfNoFixIncome::where('documentRequest_id', $id)->delete();

                    } else if($documentRequest->document_type == 'Extra Judicial') {

                        $additionalInfoDetails = ExtraJudicial::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->death_cert))) {
                            unlink(public_path($additionalInfoDetails->death_cert));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->heirship))) {
                            unlink(public_path($additionalInfoDetails->heirship));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->inv_estate))) {
                            unlink(public_path($additionalInfoDetails->inv_estate));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->tax_clearance))) {
                            unlink(public_path($additionalInfoDetails->tax_clearance));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->deed_extrajudicial))) {
                            unlink(public_path($additionalInfoDetails->deed_extrajudicial));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        ExtraJudicial::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Deed of Sale') {
            
                        DeedOfSale::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Deed of Donation') {
            
                        DeedOfDonation::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Other Document') {
                        
                        $additionalInfoDetails = OtherDocument::where('documentRequest_id', $id)->first();

                        if (file_exists(public_path($additionalInfoDetails->valid_id_front))) {
                            unlink(public_path($additionalInfoDetails->valid_id_front));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->valid_id_back))) {
                            unlink(public_path($additionalInfoDetails->valid_id_back));
                        } else {
                            return $this->failedEditRedirect($id);
                        }

                        OtherDocument::where('documentRequest_id', $id)->delete();
            
                    }

                    $documentAddress = $this->generateEditDocumentAddress($request);
                    $indigencyFilePath = $this->uploadEditCertOfIndigencyAONFI($request);

                    $createAffidavitOfNoFixIncome = $this->createAffidavitOfNoFixIncome($request, $documentAddress, $indigencyFilePath, $id);

                    if($updateDocumentRequestDetails && $createAffidavitOfNoFixIncome) {
                        $this->logDocumentRequestEditSuccess($user, $id);
    
                        return $this->successEditRedirect($id);
                    } else {
                        return $this->failedEditRedirect($id);
                    }

                } else if($request->document_type == 'Extra Judicial') {

                    if($documentRequest->document_type == 'Affidavit of Loss') {

                        $additionalInfoDetails = AffidavitOfLoss::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->valid_id_front))) {
                            unlink(public_path($additionalInfoDetails->valid_id_front));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->valid_id_back))) {
                            unlink(public_path($additionalInfoDetails->valid_id_back));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->cedula))) {
                            unlink(public_path($additionalInfoDetails->cedula));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfLoss::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Affidavit of Guardianship') {
            
                        $additionalInfoDetails = AffidavitOfGuardianship::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->guardian_brgy_clearance))) {
                            unlink(public_path($additionalInfoDetails->guardian_brgy_clearance));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfGuardianship::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Affidavit of No income') {
            
                        $additionalInfoDetails = AffidavitOfNoIncome::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->certificate_of_indigency))) {
                            unlink(public_path($additionalInfoDetails->certificate_of_indigency));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfNoIncome::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Affidavit of No fix income') {
            
                        $additionalInfoDetails = AffidavitOfNoFixIncome::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->indigency))) {
                            unlink(public_path($additionalInfoDetails->indigency));
                        } else {
                            return $this->failedEditRedirect($id);
                        }

                        AffidavitOfNoFixIncome::where('documentRequest_id', $id)->delete();

                    } else if($documentRequest->document_type == 'Extra Judicial') {

                        $additionalInfoDetails = ExtraJudicial::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->death_cert))) {
                            unlink(public_path($additionalInfoDetails->death_cert));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->heirship))) {
                            unlink(public_path($additionalInfoDetails->heirship));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->inv_estate))) {
                            unlink(public_path($additionalInfoDetails->inv_estate));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->tax_clearance))) {
                            unlink(public_path($additionalInfoDetails->tax_clearance));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->deed_extrajudicial))) {
                            unlink(public_path($additionalInfoDetails->deed_extrajudicial));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        ExtraJudicial::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Deed of Sale') {
            
                        DeedOfSale::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Deed of Donation') {
            
                        DeedOfDonation::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Other Document') {
                        
                        $additionalInfoDetails = OtherDocument::where('documentRequest_id', $id)->first();

                        if (file_exists(public_path($additionalInfoDetails->valid_id_front))) {
                            unlink(public_path($additionalInfoDetails->valid_id_front));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->valid_id_back))) {
                            unlink(public_path($additionalInfoDetails->valid_id_back));
                        } else {
                            return $this->failedEditRedirect($id);
                        }

                        OtherDocument::where('documentRequest_id', $id)->delete();
            
                    }

                    $deathCertFilePath = $this->uploadEditDeathCertificate($request);
                    $heirshipFilePath = $this->uploadEditHeirship($request);
                    $invEstateFilePath = $this->uploadEditInvOfEstate($request);
                    $taxClearanceFilePath = $this->uploadEditTaxClearance($request);
                    $deedExtrajudicialFilePath = $this->uploadEditDeedOfExtraJudicialSettlement($request);

                    $createExtraJudicial = $this->createExtraJudicial($deathCertFilePath, $heirshipFilePath, $invEstateFilePath, $taxClearanceFilePath, $deedExtrajudicialFilePath, $id);

                    if($updateDocumentRequestDetails && $createExtraJudicial) {
                        $this->logDocumentRequestEditSuccess($user, $id);
    
                        return $this->successEditRedirect($id);
                    } else {
                        return $this->failedEditRedirect($id);
                    }
                    
                    
                } else if($request->document_type == 'Deed of Sale') {
                        
                    if($documentRequest->document_type == 'Affidavit of Loss') {

                        $additionalInfoDetails = AffidavitOfLoss::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->valid_id_front))) {
                            unlink(public_path($additionalInfoDetails->valid_id_front));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->valid_id_back))) {
                            unlink(public_path($additionalInfoDetails->valid_id_back));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->cedula))) {
                            unlink(public_path($additionalInfoDetails->cedula));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfLoss::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Affidavit of Guardianship') {
            
                        $additionalInfoDetails = AffidavitOfGuardianship::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->guardian_brgy_clearance))) {
                            unlink(public_path($additionalInfoDetails->guardian_brgy_clearance));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfGuardianship::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Affidavit of No income') {
            
                        $additionalInfoDetails = AffidavitOfNoIncome::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->certificate_of_indigency))) {
                            unlink(public_path($additionalInfoDetails->certificate_of_indigency));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfNoIncome::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Affidavit of No fix income') {
            
                        $additionalInfoDetails = AffidavitOfNoFixIncome::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->indigency))) {
                            unlink(public_path($additionalInfoDetails->indigency));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfNoFixIncome::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Extra Judicial') {
            
                        $additionalInfoDetails = ExtraJudicial::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->death_cert))) {
                            unlink(public_path($additionalInfoDetails->death_cert));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->heirship))) {
                            unlink(public_path($additionalInfoDetails->heirship));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->inv_estate))) {
                            unlink(public_path($additionalInfoDetails->inv_estate));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->tax_clearance))) {
                            unlink(public_path($additionalInfoDetails->tax_clearance));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->deed_extrajudicial))) {
                            unlink(public_path($additionalInfoDetails->deed_extrajudicial));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        ExtraJudicial::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Deed of Sale') {
            
                        DeedOfSale::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Deed of Donation') {
            
                        DeedOfDonation::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Other Document') {
                        
                        $additionalInfoDetails = OtherDocument::where('documentRequest_id', $id)->first();

                        if (file_exists(public_path($additionalInfoDetails->valid_id_front))) {
                            unlink(public_path($additionalInfoDetails->valid_id_front));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->valid_id_back))) {
                            unlink(public_path($additionalInfoDetails->valid_id_back));
                        } else {
                            return $this->failedEditRedirect($id);
                        }

                        OtherDocument::where('documentRequest_id', $id)->delete();
            
                    }

                    $createDeedOfSale = $this->createDeedOfSale($request, $id);

                    if($updateDocumentRequestDetails && $createDeedOfSale) {
                        $this->logDocumentRequestEditSuccess($user, $id);
    
                        return $this->successEditRedirect($id);
                    } else {
                        return $this->failedEditRedirect($id);
                    }

                } else if($request->document_type == 'Deed of Donation') {
                   
                    if($documentRequest->document_type == 'Affidavit of Loss') {

                        $additionalInfoDetails = AffidavitOfLoss::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->valid_id_front))) {
                            unlink(public_path($additionalInfoDetails->valid_id_front));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->valid_id_back))) {
                            unlink(public_path($additionalInfoDetails->valid_id_back));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->cedula))) {
                            unlink(public_path($additionalInfoDetails->cedula));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfLoss::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Affidavit of Guardianship') {
            
                        $additionalInfoDetails = AffidavitOfGuardianship::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->guardian_brgy_clearance))) {
                            unlink(public_path($additionalInfoDetails->guardian_brgy_clearance));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfGuardianship::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Affidavit of No income') {
            
                        $additionalInfoDetails = AffidavitOfNoIncome::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->certificate_of_indigency))) {
                            unlink(public_path($additionalInfoDetails->certificate_of_indigency));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfNoIncome::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Affidavit of No fix income') {
            
                        $additionalInfoDetails = AffidavitOfNoFixIncome::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->indigency))) {
                            unlink(public_path($additionalInfoDetails->indigency));
                        } else {
                            return $this->failedEditRedirect($id);
                        }

                        AffidavitOfNoFixIncome::where('documentRequest_id', $id)->delete();

                    } else if($documentRequest->document_type == 'Extra Judicial') {

                        $additionalInfoDetails = ExtraJudicial::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->death_cert))) {
                            unlink(public_path($additionalInfoDetails->death_cert));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->heirship))) {
                            unlink(public_path($additionalInfoDetails->heirship));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->inv_estate))) {
                            unlink(public_path($additionalInfoDetails->inv_estate));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->tax_clearance))) {
                            unlink(public_path($additionalInfoDetails->tax_clearance));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->deed_extrajudicial))) {
                            unlink(public_path($additionalInfoDetails->deed_extrajudicial));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        ExtraJudicial::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Deed of Sale') {
            
                        DeedOfSale::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Deed of Donation') {
            
                        DeedOfDonation::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Other Document') {
                        
                        $additionalInfoDetails = OtherDocument::where('documentRequest_id', $id)->first();

                        if (file_exists(public_path($additionalInfoDetails->valid_id_front))) {
                            unlink(public_path($additionalInfoDetails->valid_id_front));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->valid_id_back))) {
                            unlink(public_path($additionalInfoDetails->valid_id_back));
                        } else {
                            return $this->failedEditRedirect($id);
                        }

                        OtherDocument::where('documentRequest_id', $id)->delete();
            
                    }

                    $documentAddress = $this->generateEditDocumentAddress($request);
                    $documentAddress2 = $this->generateEditDocument2Address($request);

                    $createDeedOfDonation = $this->createDeedOfDonation($request, $documentAddress, $documentAddress2, $id);

                    if($updateDocumentRequestDetails && $createDeedOfDonation) {
                        $this->logDocumentRequestEditSuccess($user, $id);
    
                        return $this->successEditRedirect($id);
                    } else {
                        return $this->failedEditRedirect($id);
                    }

                } else if($request->document_type == 'Other Document') {
                    
                    if($documentRequest->document_type == 'Affidavit of Loss') {

                        $additionalInfoDetails = AffidavitOfLoss::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->valid_id_front))) {
                            unlink(public_path($additionalInfoDetails->valid_id_front));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->valid_id_back))) {
                            unlink(public_path($additionalInfoDetails->valid_id_back));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->cedula))) {
                            unlink(public_path($additionalInfoDetails->cedula));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfLoss::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Affidavit of Guardianship') {
            
                        $additionalInfoDetails = AffidavitOfGuardianship::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->guardian_brgy_clearance))) {
                            unlink(public_path($additionalInfoDetails->guardian_brgy_clearance));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfGuardianship::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Affidavit of No income') {
            
                        $additionalInfoDetails = AffidavitOfNoIncome::where('documentRequest_id', $id)->first();
            
                        if (file_exists(public_path($additionalInfoDetails->certificate_of_indigency))) {
                            unlink(public_path($additionalInfoDetails->certificate_of_indigency));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        AffidavitOfNoIncome::where('documentRequest_id', $id)->delete();
            
                    } else if($documentRequest->document_type == 'Affidavit of No fix income') {
            
                        $additionalInfoDetails = AffidavitOfNoFixIncome::where('documentRequest_id', $id)->first();

                        if (file_exists(public_path($additionalInfoDetails->indigency))) {
                            unlink(public_path($additionalInfoDetails->indigency));
                        } else {
                            return $this->failedEditRedirect($id);
                        }

                        AffidavitOfNoFixIncome::where('documentRequest_id', $id)->delete();

                    } else if($documentRequest->document_type == 'Extra Judicial') {

                        $additionalInfoDetails = ExtraJudicial::where('documentRequest_id', $id)->first();

                        if (file_exists(public_path($additionalInfoDetails->death_cert))) {
                            unlink(public_path($additionalInfoDetails->death_cert));
                        } else {
                            return $this->failedEditRedirect($id);
                        }

                        if (file_exists(public_path($additionalInfoDetails->heirship))) {
                            unlink(public_path($additionalInfoDetails->heirship));
                        } else {
                            return $this->failedEditRedirect($id);
                        }

                        if (file_exists(public_path($additionalInfoDetails->inv_estate))) {
                            unlink(public_path($additionalInfoDetails->inv_estate));
                        } else {
                            return $this->failedEditRedirect($id);
                        }

                        if (file_exists(public_path($additionalInfoDetails->tax_clearance))) {
                            unlink(public_path($additionalInfoDetails->tax_clearance));
                        } else {
                            return $this->failedEditRedirect($id);
                        }

                        if (file_exists(public_path($additionalInfoDetails->deed_extrajudicial))) {
                            unlink(public_path($additionalInfoDetails->deed_extrajudicial));
                        } else {
                            return $this->failedEditRedirect($id);
                        }

                        ExtraJudicial::where('documentRequest_id', $id)->delete();

                    } else if($documentRequest->document_type == 'Deed of Sale') {

                        DeedOfSale::where('documentRequest_id', $id)->delete();

                    } else if($documentRequest->document_type == 'Deed of Donation') {

                        DeedOfDonation::where('documentRequest_id', $id)->delete();

                    } else if($documentRequest->document_type == 'Other Document') {
                        
                        $additionalInfoDetails = OtherDocument::where('documentRequest_id', $id)->first();

                        if (file_exists(public_path($additionalInfoDetails->valid_id_front))) {
                            unlink(public_path($additionalInfoDetails->valid_id_front));
                        } else {
                            return $this->failedEditRedirect($id);
                        }
            
                        if (file_exists(public_path($additionalInfoDetails->valid_id_back))) {
                            unlink(public_path($additionalInfoDetails->valid_id_back));
                        } else {
                            return $this->failedEditRedirect($id);
                        }

                        OtherDocument::where('documentRequest_id', $id)->delete();

                    }

                    $validIdFrontFilePath = $this->uploadEditValidIdFrontOther($request);
                    $validIdBackFilePath = $this->uploadEditValidIdBackOther($request);

                    $createOtherDocument = $this->createOtherDocument($validIdFrontFilePath, $validIdBackFilePath, $id);

                    if($updateDocumentRequestDetails && $createOtherDocument) {
                        $this->logDocumentRequestEditSuccess($user, $id);
    
                        return $this->successEditRedirect($id);
                    } else {
                        return $this->failedEditRedirect($id);
                    }
                
                } else {
                    return $this->failedEditRedirect($id);
                } 
            }
        } else if ($request->document_type == 'Affidavit of Loss' && $this->shouldUpdateAffidavitOfLoss($request, $id)) {
            $document_address = $this->generateEditDocumentAddress($request);
            $updateAffidavitOfLossDetails = $this->updateAffidavitOfLoss($request, $document_address, $id);

            if($updateAffidavitOfLossDetails) {
                $this->logDocumentRequestEditSuccess($user, $id);

                return $this->successEditRedirect($id);
            } else {
                return $this->failedEditRedirect($id);
            }
        } else if ($request->document_type == 'Affidavit of Guardianship' && $this->shouldUpdateAffidavitOfGuardianship($request, $id)) {

            $document_address = $this->generateEditDocumentAddress($request);
            $document_address2 = $this->generateEditDocument2Address($request);

            $updateAffidavitOfGuardianshipDetails = $this->updateAffidavitOfGuardianship($request, $document_address, $document_address2, $id);

            if($updateAffidavitOfGuardianshipDetails) {
                $this->logDocumentRequestEditSuccess($user, $id);

                return $this->successEditRedirect($id);
            } else {
                return $this->failedEditRedirect($id);
            }
        } else if ($request->document_type == 'Affidavit of No income' && $this->shouldUpdateAffidavitOfNoIncome($request, $id)) {
            $document_address = $this->generateEditDocumentAddress($request);
            
            $updateAffidavitOfNoIncomeDetails = $this->updateAffidavitOfNoIncome($request, $document_address, $id);

            if($updateAffidavitOfNoIncomeDetails) {
                $this->logDocumentRequestEditSuccess($user, $id);

                return $this->successEditRedirect($id);
            } else {
                return $this->failedEditRedirect($id);
            }
        } else if ($request->document_type == 'Affidavit of No fix income' && $this->shouldUpdateAffidavitOfNoFixIncome($request, $id)) {
            $document_address = $this->generateEditDocumentAddress($request);
            
            $updateAffidavitOfNoFixIncomeDetails = $this->updateAffidavitOfNoFixIncome($request, $document_address, $id);

            if($updateAffidavitOfNoFixIncomeDetails) {
                $this->logDocumentRequestEditSuccess($user, $id);

                return $this->successEditRedirect($id);
            } else {
                return $this->failedEditRedirect($id);
            }
        } else if ($request->document_type == 'Extra Judicial' && $this->shouldUpdateExtraJudicial($request)) {
            $updateExtraJudicialDetails = $this->updateExtraJudicial($request, $id);

            if($updateExtraJudicialDetails) {
                $this->logDocumentRequestEditSuccess($user, $id);

                return $this->successEditRedirect($id);
            } else {
                return $this->failedEditRedirect($id);
            }
        } else if ($request->document_type == 'Deed of Sale' && $this->shouldUpdateDeedOfSale($request, $id)) {
            $updateDeedOfSaleDetails = $this->updateDeedOfSale($request, $id);

            if($updateDeedOfSaleDetails) {
                $this->logDocumentRequestEditSuccess($user, $id);

                return $this->successEditRedirect($id);
            } else {
                return $this->failedEditRedirect($id);
            }
        } else if ($request->document_type == 'Deed of Donation' && $this->shouldUpdateDeedOfDonation($request, $id)) {
            $document_address = $this->generateEditDocumentAddress($request);
            $document_address2 = $this->generateEditDocument2Address($request);
            $updateDeedOfDonationDetails = $this->updateDeedOfDonation($request, $document_address, $document_address2, $id);

            if($updateDeedOfDonationDetails) {
                $this->logDocumentRequestEditSuccess($user, $id);

                return $this->successEditRedirect($id);
            } else {
                return $this->failedEditRedirect($id);
            }

        } else if ($request->document_type == 'Other Document' && $this->shouldUpdateOtherDocument($request, $id)) {
            $updateOtherDocumentDetails = $this->updateOtherDocument($request, $id);

            if($updateOtherDocumentDetails) {
                $this->logDocumentRequestEditSuccess($user, $id);

                return $this->successEditRedirect($id);
            } else {
                return $this->failedEditRedirect($id);
            }
        
        } else {
            return redirect()->route('document-request.editDocumentRequest', $id)
                ->with('failed', 'Update Some Fields!');
        }
    }

    private function shouldUpdateDocumentRequest(Request $request, $documentRequest) {
        return $request->document_type != $documentRequest->document_type ||
            $request->name != $documentRequest->name ||
            $request->cellphone_number != $documentRequest->cellphone_number ||
            $request->email != $documentRequest->email ||
            $this->shouldUpdateAddress($request, $documentRequest);
    }
    
    private function shouldUpdateAddress(Request $request, $documentRequest) {
        $address = explode(', ', $documentRequest->address);
        $city = $address[2];
        $city = trim($city);

        $final_barangay = '';
        $street = '';
        $other_city = '';
        $other_barangay = '';
        $other_street = '';

        if($city == 'San Pedro City') {
            $barangay = $address[1];
            $trim_barangay = explode(' ', $barangay);
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

        return ($request->city == 'San Pedro City' && $request->barangay != $final_barangay && $request->street != $street) ||
            ($request->city == 'Other City' && $request->other_city != $other_city && $request->other_barangay != $other_barangay && $request->other_street != $other_street);
    }

    private function shouldUpdateAffidavitOfLoss(Request $request, $id) {
        $affidavitOfLossInfo = AffidavitOfLoss::where('documentRequest_id', $id)->get()->first();
        
        return $request->document_name != $affidavitOfLossInfo->aol_name ||
            $request->document_age != $affidavitOfLossInfo->aol_age ||
            $this->shouldUpdateDocumentAddress($request, $affidavitOfLossInfo->aol_address) ||
            $request->valid_id_front != null ||
            $request->valid_id_back != null ||
            $request->cedula != null;
    }

    private function shouldUpdateAffidavitOfGuardianship(Request $request, $id) {
        $affidavitOfGuardianshipInfo = AffidavitOfGuardianship::where('documentRequest_id', $id)->get()->first();

        return $request->guardian_name != $affidavitOfGuardianshipInfo->guardian_name ||
            $request->guardian_age != $affidavitOfGuardianshipInfo->guardian_age ||
            $this->shouldUpdateDocumentAddress($request, $affidavitOfGuardianshipInfo->guardian_address) ||
            $request->guardian_occupation != $affidavitOfGuardianshipInfo->guardian_occupation ||
            $request->barangay_clearance != null ||
            $request->relationship != $affidavitOfGuardianshipInfo->guardian_relationship ||
            $request->minor_name != $affidavitOfGuardianshipInfo->minor_name ||
            $request->minor_age != $affidavitOfGuardianshipInfo->minor_age ||
            $this->shouldUpdateDocument2Address($request, $affidavitOfGuardianshipInfo->minor_address) ||
            $request->minor_relationship != $affidavitOfGuardianshipInfo->minor_relationship;
    }

    private function shouldUpdateAffidavitOfNoIncome(Request $request, $id) {
        $affidavitOfNoIncomeInfo = AffidavitOfNoIncome::where('documentRequest_id', $id)->get()->first();

        return $request->document_name != $affidavitOfNoIncomeInfo->aoni_name ||
            $request->document_age != $affidavitOfNoIncomeInfo->aoni_age ||
            $this->shouldUpdateDocumentAddress($request, $affidavitOfNoIncomeInfo->aoni_address) ||
            $request->certificate_of_indigency != null ||
            $request->previous_employer_name != $affidavitOfNoIncomeInfo->previous_employer_name ||
            $request->previous_employer_contact != $affidavitOfNoIncomeInfo->previous_employer_contact ||
            $request->business_name != $affidavitOfNoIncomeInfo->business_name ||
            $request->registration_number != $affidavitOfNoIncomeInfo->registration_number ||
            $request->business_address != $affidavitOfNoIncomeInfo->business_address ||
            $request->business_period != $affidavitOfNoIncomeInfo->business_period ||
            $request->no_income_period != $affidavitOfNoIncomeInfo->no_income_period;
    }

    private function shouldUpdateAffidavitOfNoFixIncome(Request $request, $id) {
        $affidavitOfNoFixIncomeInfo = AffidavitOfNoFixIncome::where('documentRequest_id', $id)->get()->first();

        return $request->document_name != $affidavitOfNoFixIncomeInfo->aonfi_name ||
            $request->document_age != $affidavitOfNoFixIncomeInfo->aonfi_age ||
            $this->shouldUpdateDocumentAddress($request, $affidavitOfNoFixIncomeInfo->aonfi_address) ||
            $request->source_of_income != $affidavitOfNoFixIncomeInfo->source_income ||
            $request->indigency != null;
    }

    private function shouldUpdateExtraJudicial(Request $request) {
        
        return $request->death_certificate != null ||
            $request->heirship_documents != null ||
            $request->inventory_of_estate != null ||
            $request->tax_clearance != null ||
            $request->deed_of_extrajudicial_settlement != null;
    }

    private function shouldUpdateDeedOfSale(Request $request, $id) {
        $deedOfSaleInfo = DeedOfSale::where('documentRequest_id', $id)->get()->first();

        return $request->party1_name != $deedOfSaleInfo->name_identity_1 ||
            $request->party2_name != $deedOfSaleInfo->name_identity_2 ||
            $request->property_details != $deedOfSaleInfo->details;
    }

    private function shouldUpdateDeedOfDonation(Request $request, $id) {
        $deedOfDonationInfo = DeedOfDonation::where('documentRequest_id', $id)->get()->first();

        return $request->donor_name != $deedOfDonationInfo->donor_name ||
            $request->donor_age != $deedOfDonationInfo->donor_age ||
            $this->shouldUpdateDocumentAddress($request, $deedOfDonationInfo->donor_address) ||
            $request->donee_name != $deedOfDonationInfo->donee_name ||
            $request->donee_age != $deedOfDonationInfo->donee_age ||
            $this->shouldUpdateDocument2Address($request, $deedOfDonationInfo->donee_address);
    }

    private function shouldUpdateOtherDocument(Request $request, $id) {

        return $request->valid_id_front != null ||
            $request->valid_id_back != null;
    }

    private function shouldUpdateDocumentAddress(Request $request, $address) {
        $address = explode(', ', $address);
        $city = $address[2];
        $city = trim($city);

        $final_barangay = '';
        $street = '';
        $other_city = '';
        $other_barangay = '';
        $other_street = '';

        if($city == 'San Pedro City') {
            $barangay = $address[1];
            $trim_barangay = explode(' ', $barangay);
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

        return ($request->document_city == 'San Pedro City' && $request->document_barangay != $final_barangay && $request->document_street != $street) ||
            ($request->document_city == 'Other City' && $request->document_other_city != $other_city && $request->document_other_barangay != $other_barangay && $request->document_other_street != $other_street);
    }

    private function shouldUpdateDocument2Address(Request $request, $address) {
        $address = explode(', ', $address);
        $city = $address[2];
        $city = trim($city);

        $final_barangay = '';
        $street = '';
        $other_city = '';
        $other_barangay = '';
        $other_street = '';

        if($city == 'San Pedro City') {
            $barangay = $address[1];
            $trim_barangay = explode(' ', $barangay);
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

        return ($request->document_city_2 == 'San Pedro City' && $request->document_barangay_2 != $final_barangay && $request->document_street_2 != $street) ||
            ($request->document_city_2 == 'Other City' && $request->document_other_city_2 != $other_city && $request->document_other_barangay_2 != $other_barangay && $request->document_other_street_2 != $other_street);
    }
    
    private function generateEditAddress(Request $request) {
        $city = $request->city == 'San Pedro City' ? $request->city : $request->other_city;
        $street = $city == 'San Pedro City' ? $request->street : $request->other_street;
        $barangay = $city == 'San Pedro City' ? $request->barangay : $request->other_barangay;
    
        return trim($street) . ', Brgy. ' . trim($barangay) . ', ' . trim($city);
    }

    private function generateEditDocumentAddress(Request $request) {
        $city = $request->document_city == 'San Pedro City' ? $request->document_city : $request->document_other_city;
        $street = $city == 'San Pedro City' ? $request->document_street : $request->document_other_street;
        $barangay = $city == 'San Pedro City' ? $request->document_barangay : $request->document_other_barangay;
    
        return trim($street) . ', Brgy. ' . trim($barangay) . ', ' . trim($city);
    }
    private function generateEditDocument2Address(Request $request) {
        $city = $request->document_city_2 == 'San Pedro City' ? $request->document_city_2 : $request->document_other_city_2;
        $street = $city == 'San Pedro City' ? $request->document_street_2 : $request->document_other_street_2;
        $barangay = $city == 'San Pedro City' ? $request->document_barangay_2 : $request->document_other_barangay_2;
    
        return trim($street) . ', Brgy. ' . trim($barangay) . ', ' . trim($city);
    }

    private function uploadEditValidIdFront(Request $request) {
        $file = $request->file('valid_id_front');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/affidavitOfLoss/' . $fileName;
        $file->move('uploads/document-request/affidavitOfLoss/', $fileName);
    
        return $filePath;
    }

    private function uploadEditValidIdBack(Request $request) {
        $file = $request->file('valid_id_back');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/affidavitOfLoss/' . $fileName;
        $file->move('uploads/document-request/affidavitOfLoss/', $fileName);
    
        return $filePath;
    }

    private function uploadEditValidIdFrontOther(Request $request) {
        $file = $request->file('valid_id_front');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/otherDocument/' . $fileName;
        $file->move('uploads/document-request/otherDocument/', $fileName);
    
        return $filePath;
    }

    private function uploadEditValidIdBackOther(Request $request) {
        $file = $request->file('valid_id_back');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/otherDocument/' . $fileName;
        $file->move('uploads/document-request/otherDocument/', $fileName);
    
        return $filePath;
    }

    private function uploadEditCedula(Request $request) {
        $file = $request->file('cedula');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/affidavitOfLoss/' . $fileName;
        $file->move('uploads/document-request/affidavitOfLoss/', $fileName);
    
        return $filePath;
    }

    private function uploadEditBarangayClearanceAOG(Request $request) {
        $file = $request->file('barangay_clearance');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/affidavitOfGuardianship/' . $fileName;
        $file->move('uploads/document-request/affidavitOfGuardianship/', $fileName);
    
        return $filePath;
    }

    private function uploadEditCertOfIndigencyAONI(Request $request) {
        $file = $request->file('certificate_of_indigency');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/affidavitOfNoIncome/' . $fileName;
        $file->move('uploads/document-request/affidavitOfNoIncome/', $fileName);
    
        return $filePath;
    }

    private function uploadEditCertOfIndigencyAONFI(Request $request) {
        $file = $request->file('certificate_of_indigency');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/affidavitOfNoFixIncome/' . $fileName;
        $file->move('uploads/document-request/affidavitOfNoFixIncome/', $fileName);
    
        return $filePath;
    }

    private function uploadEditDeathCertificate(Request $request) {
        $file = $request->file('death_certificate');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/ExtraJudicial/' . $fileName;
        $file->move('uploads/document-request/ExtraJudicial/', $fileName);
    
        return $filePath;
    }

    private function uploadEditHeirship(Request $request) {
        $file = $request->file('heirship_documents');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/ExtraJudicial/' . $fileName;
        $file->move('uploads/document-request/ExtraJudicial/', $fileName);
    
        return $filePath;
    }

    private function uploadEditInvOfEstate(Request $request) {
        $file = $request->file('inventory_of_estate');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/ExtraJudicial/' . $fileName;
        $file->move('uploads/document-request/ExtraJudicial/', $fileName);
    
        return $filePath;
    }

    private function uploadEditTaxClearance(Request $request) {
        $file = $request->file('tax_clearance');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/ExtraJudicial/' . $fileName;
        $file->move('uploads/document-request/ExtraJudicial/', $fileName);
    
        return $filePath;
    }

    private function uploadEditDeedOfExtraJudicialSettlement(Request $request) {
        $file = $request->file('deed_of_extrajudicial_settlement');
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/document-request/ExtraJudicial/' . $fileName;
        $file->move('uploads/document-request/ExtraJudicial/', $fileName);
    
        return $filePath;
    }
    
    private function updateDocumentRequestDetails(Request $request, $address, $documentRequestID) {
        $data = [
            'name' => trim($request->name),
            'address' => $address,
            'cellphone_number' => trim($request->cellphone_number),
            'email' => trim($request->email),
            'document_type' => $request->document_type,
            'updated_at' => Carbon::now('Asia/Manila'),
        ];
    
        return DocumentRequest::where('documentRequest_id', $documentRequestID)->update($data);
    }

    private function updateAffidavitOfLoss(Request $request, $address, $documentRequestID) {
        $documentRequest = AffidavitOfLoss::where('documentRequest_id', $documentRequestID)->first();
    
        $data = [
            'aol_name' => trim($request->document_name),
            'aol_age' => $request->document_age,
            'aol_address' => $address,
            'updated_at' => Carbon::now('Asia/Manila'),
        ];
    
        if ($request->hasFile('valid_id_front')) {
            $filePath = $documentRequest->valid_id_front;
            if (file_exists(public_path($filePath))) {
                unlink(public_path($filePath));
            }

            $validIDFrontFilePath = $this->uploadEditValidIdFront($request);
            $data['valid_id_front'] = $validIDFrontFilePath;
        }
    
        if ($request->hasFile('valid_id_back')) {
            $filePath = $documentRequest->valid_id_back;
            if (file_exists(public_path($filePath))) {
                unlink(public_path($filePath));
            }

            $validIDBackFilePath = $this->uploadEditValidIdBack($request);
            $data['valid_id_back'] = $validIDBackFilePath;
        }
    
        if ($request->hasFile('cedula')) {
            $filePath = $documentRequest->cedula;
            if (file_exists(public_path($filePath))) {
                unlink(public_path($filePath));
            }

            $cedulaFilePath = $this->uploadEditCedula($request);
            $data['cedula'] = $cedulaFilePath;
        }
    
        return AffidavitOfLoss::where('documentRequest_id', $documentRequestID)->update($data);
    }
    

    private function updateAffidavitOfGuardianship(Request $request, $address, $address2, $documentRequestID) {
        $documentRequest = AffidavitOfGuardianship::where('documentRequest_id', $documentRequestID)->first();

        $data = [
            'guardian_name' => trim($request->guardian_name),
            'guardian_age' => $request->guardian_age,
            'guardian_address' => $address,
            'guardian_occupation' => trim($request->guardian_occupation),
            'guardian_relationship' => trim($request->relationship),
            'minor_name' => trim($request->minor_name),
            'minor_age' => $request->minor_age,
            'minor_address' => $address2,
            'minor_relationship' => trim($request->minor_relationship),
            'updated_at' => Carbon::now('Asia/Manila'),
        ];

        if ($request->hasFile('barangay_clearance')) {
            $filePath = $documentRequest->guardian_brgy_clearance;
            if (file_exists(public_path($filePath))) {
                unlink(public_path($filePath));
            }

            $BarangayClearancePath = $this->uploadEditBarangayClearanceAOG($request);
            $data['guardian_brgy_clearance'] = $BarangayClearancePath;
        }
    
        return AffidavitOfGuardianship::where('documentRequest_id', $documentRequestID)->update($data);
    }

    private function updateAffidavitOfNoIncome(Request $request, $address, $documentRequestID) {
        $documentRequest = AffidavitOfNoIncome::where('documentRequest_id', $documentRequestID)->first();

        $data = [
            'aoni_name' => trim($request->document_name),
            'aoni_age' => $request->document_age,
            'aoni_address' => $address,
            'business_name' => trim($request->business_name),
            'registration_number' => trim($request->registration_number),
            'business_address' => trim($request->business_address),
            'business_period' => trim($request->business_period),
            'no_income_period' => trim($request->no_income_period),
            'updated_at' => Carbon::now('Asia/Manila'),
        ];

        if ($request->hasFile('certificate_of_indigency')) {
            $filePath = $documentRequest->certificate_of_indigency;
            if (file_exists(public_path($filePath))) {
                unlink(public_path($filePath));
            }

            $CertOfIndigencyPath = $this->uploadEditCertOfIndigencyAONI($request);
            $data['certificate_of_indigency'] = $CertOfIndigencyPath;
        }

        if($request->previous_employer_name != $documentRequest->previous_employer_name || $request->previous_employer_contact != $documentRequest->previous_employer_contact) {
            $data['previous_employer_name'] = trim($request->previous_employer_name);
            $data['previous_employer_contact'] = trim($request->previous_employer_contact);
        }
    
        return AffidavitOfNoIncome::where('documentRequest_id', $documentRequestID)->update($data);
    }

    private function updateAffidavitOfNoFixIncome(Request $request, $address, $documentRequestID) {
        $documentRequest = AffidavitOfNoFixIncome::where('documentRequest_id', $documentRequestID)->first();

        $data = [
            'aonfi_name' => trim($request->document_name),
            'aonfi_age' => $request->document_age,
            'aonfi_address' => $address,
            'source_income' => trim($request->source_of_income),
            'updated_at' => Carbon::now('Asia/Manila'),
        ];

        if ($request->hasFile('certificate_of_indigency')) {
            $filePath = $documentRequest->indigency;
            if (file_exists(public_path($filePath))) {
                unlink(public_path($filePath));
            }

            $CertOfIndigencyPath = $this->uploadEditCertOfIndigencyAONFI($request);
            $data['indigency'] = $CertOfIndigencyPath;
        }
    
        return AffidavitOfNoFixIncome::where('documentRequest_id', $documentRequestID)->update($data);
    }

    private function updateExtraJudicial(Request $request, $documentRequestID) {
        $documentRequest = ExtraJudicial::where('documentRequest_id', $documentRequestID)->first();

        $data = [
            'updated_at' => Carbon::now('Asia/Manila'),
        ];
        
        if ($request->hasFile('death_certificate')) {
            $filePath = $documentRequest->death_cert;
            if (file_exists(public_path($filePath))) {
                unlink(public_path($filePath));
            }

            $DeathCertificatePath = $this->uploadEditDeathCertificate($request);
            $data['death_cert'] = $DeathCertificatePath;
        }

        if ($request->hasFile('heirship_documents')) {
            $filePath = $documentRequest->heirship;
            if (file_exists(public_path($filePath))) {
                unlink(public_path($filePath));
            }

            $HeirshipDocumentsPath = $this->uploadEditHeirship($request);
            $data['heirship'] = $HeirshipDocumentsPath;
        }

        if ($request->hasFile('inventory_of_estate')) {
            $filePath = $documentRequest->inv_estate;
            if (file_exists(public_path($filePath))) {
                unlink(public_path($filePath));
            }

            $InvOfEstatePath = $this->uploadEditInvOfEstate($request);
            $data['inv_estate'] = $InvOfEstatePath;
        }

        if ($request->hasFile('tax_clearance')) {
            $filePath = $documentRequest->tax_clearance;
            if (file_exists(public_path($filePath))) {
                unlink(public_path($filePath));
            }

            $TaxClearancePath = $this->uploadEditTaxClearance($request);
            $data['tax_clearance'] = $TaxClearancePath;
        }

        if ($request->hasFile('deed_of_extrajudicial_settlement')) {
            $filePath = $documentRequest->deed_extrajudicial;
            if (file_exists(public_path($filePath))) {
                unlink(public_path($filePath));
            }

            $DeedOfExtraJudicialSettlementPath = $this->uploadEditDeedOfExtraJudicialSettlement($request);
            $data['deed_extrajudicial'] = $DeedOfExtraJudicialSettlementPath;
        }

        return ExtraJudicial::where('documentRequest_id', $documentRequestID)->update($data);
    }

    private function updateDeedOfSale(Request $request, $documentRequestID) {
        $data = [
            'name_identity_1' => trim($request->party1_name),
            'name_identity_2' => trim($request->party2_name),
            'details' => trim($request->property_details),
            'updated_at' => Carbon::now('Asia/Manila'),
        ];
    
        return DeedOfSale::where('documentRequest_id', $documentRequestID)->update($data);
    }

    private function updateDeedOfDonation(Request $request, $address, $address2, $documentRequestID) {
        $data = [
            'donor_name' => trim($request->donor_name),
            'donor_age' => $request->donor_age,
            'donor_address' => $address,
            'donee_name' => trim($request->donee_name),
            'donee_age' => $request->donee_age,
            'donee_address' => $address2,
            'updated_at' => Carbon::now('Asia/Manila'),
        ];
    
        return DeedOfDonation::where('documentRequest_id', $documentRequestID)->update($data);
    }

    private function updateOtherDocument(Request $request, $documentRequestID) {
        $documentRequest = OtherDocument::where('documentRequest_id', $documentRequestID)->first();

        $data = [
            'updated_at' => Carbon::now('Asia/Manila'),
        ];

        if ($request->hasFile('valid_id_front')) {
            $filePath = $documentRequest->valid_id_front;
            if (file_exists(public_path($filePath))) {
                unlink(public_path($filePath));
            }

            $validIDFrontFilePath = $this->uploadEditValidIdFrontOther($request);
            $data['valid_id_front'] = $validIDFrontFilePath;
        }
    
        if ($request->hasFile('valid_id_back')) {
            $filePath = $documentRequest->valid_id_back;
            if (file_exists(public_path($filePath))) {
                unlink(public_path($filePath));
            }

            $validIDBackFilePath = $this->uploadEditValidIdBackOther($request);
            $data['valid_id_back'] = $validIDBackFilePath;
        }
    
        return OtherDocument::where('documentRequest_id', $documentRequestID)->update($data);
    }

    private function logDocumentRequestEditSuccess($user, $documentRequestID) {
        Logs::create([
            'type' => 'Edit Document Request',
            'user' => $user,
            'subject' => 'Edit Document Request Success',
            'message' => $user . ' has successfully edited Document Request ID: ' . $documentRequestID . '.',
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }
    
    private function successEditRedirect($documentRequestID) {
        return redirect()->route('document-request.editDocumentRequest', $documentRequestID)
                ->with('success', 'Document Request Updated Successfully!');
    }
    
    private function failedEditRedirect($documentRequestID) {
        return redirect()->route('document-request.editDocumentRequest', $documentRequestID)
                ->with('failed', 'Failed to Send Document Request!');
    }


    public function deleteDocumentRequest(string $id)
    {
        $documentRequest = DocumentRequest::where('documentRequest_id', $id)->get()->first();
        return view('document-request.deleteDocumentRequest', compact('documentRequest'));
    }

    public function destroyDocumentRequest(string $id)
    {
        try {
            DB::beginTransaction();
    
            $documentRequest = DocumentRequest::where('documentRequest_id', $id)->get()->first();
            $user = Auth::user()->username;
    
            $this->createDeleteDocumentRequestLog($user, $documentRequest);
    
            if ($documentRequest->document_type == 'Affidavit of Loss') {
                $additionalInfo = AffidavitOfLoss::where('documentRequest_id', $id)->get()->first();

                if (file_exists(public_path($additionalInfo->valid_id_front))) {
                    unlink(public_path($additionalInfo->valid_id_front));
                }

                if (file_exists(public_path($additionalInfo->valid_id_back))) {
                    unlink(public_path($additionalInfo->valid_id_back));
                }

            } else if ($documentRequest->document_type == 'Affidavit of Guardianship') {
                $additionalInfo = AffidavitOfGuardianship::where('documentRequest_id', $id)->get()->first();

                if (file_exists(public_path($additionalInfo->valid_id_front))) {
                    unlink(public_path($additionalInfo->valid_id_front));
                }

                if (file_exists(public_path($additionalInfo->valid_id_back))) {
                    unlink(public_path($additionalInfo->valid_id_back));
                }

            } else if ($documentRequest->document_type == 'Affidavit of No income') {
                $additionalInfo = AffidavitOfNoIncome::where('documentRequest_id', $id)->get()->first();

                if (file_exists(public_path($additionalInfo->certificate_of_indigency))) {
                    unlink(public_path($additionalInfo->certificate_of_indigency));
                }

                if (file_exists(public_path($additionalInfo->valid_id_front))) {
                    unlink(public_path($additionalInfo->valid_id_front));
                }

                if (file_exists(public_path($additionalInfo->valid_id_back))) {
                    unlink(public_path($additionalInfo->valid_id_back));
                }

            } else if ($documentRequest->document_type == 'Affidavit of No fix income') {
                $additionalInfo = AffidavitOfNoFixIncome::where('documentRequest_id', $id)->get()->first();

                if (file_exists(public_path($additionalInfo->certificate_of_residency))) {
                    unlink(public_path($additionalInfo->certificate_of_residency));
                }

                if (file_exists(public_path($additionalInfo->valid_id_front))) {
                    unlink(public_path($additionalInfo->valid_id_front));
                }

                if (file_exists(public_path($additionalInfo->valid_id_back))) {
                    unlink(public_path($additionalInfo->valid_id_back));
                }

            } else if ($documentRequest->document_type == 'Extra Judicial') {
                $additionalInfo = ExtraJudicial::where('documentRequest_id', $id)->get()->first();


                if (file_exists(public_path($additionalInfo->title_of_property))) {
                    unlink(public_path($additionalInfo->title_of_property));
                }

                if (file_exists(public_path($additionalInfo->spouse_valid_id_front))) {
                    unlink(public_path($additionalInfo->spouse_valid_id_front));
                }

                if (file_exists(public_path($additionalInfo->spouse_valid_id_back))) {
                    unlink(public_path($additionalInfo->spouse_valid_id_back));
                }

            } else if ($documentRequest->document_type == 'Deed of Sale') {
                $additionalInfo = DeedOfSale::where('documentRequest_id', $id)->get()->first();

                if (file_exists(public_path($additionalInfo->property_document))) {
                    unlink(public_path($additionalInfo->property_document));
                }

                if (file_exists(public_path($additionalInfo->vendor_valid_id_front))) {
                    unlink(public_path($additionalInfo->vendor_valid_id_front));
                }

                if (file_exists(public_path($additionalInfo->vendor_valid_id_back))) {
                    unlink(public_path($additionalInfo->vendor_valid_id_back));
                }

                if (file_exists(public_path($additionalInfo->vendee_valid_id_front))) {
                    unlink(public_path($additionalInfo->vendee_valid_id_front));
                }

                if (file_exists(public_path($additionalInfo->vendee_valid_id_back))) {
                    unlink(public_path($additionalInfo->vendee_valid_id_back));
                }

                if (file_exists(public_path($additionalInfo->witness_valid_id_front))) {
                    unlink(public_path($additionalInfo->witness_valid_id_front));
                }

                if (file_exists(public_path($additionalInfo->witness_valid_id_back))) {
                    unlink(public_path($additionalInfo->witness_valid_id_back));
                }

            } else if ($documentRequest->document_type == 'Deed of Donation') {
                $additionalInfo = DeedOfDonation::where('documentRequest_id', $id)->get()->first();

                if (file_exists(public_path($additionalInfo->donor_valid_id_front))) {
                    unlink(public_path($additionalInfo->donor_valid_id_front));
                }

                if (file_exists(public_path($additionalInfo->donor_valid_id_back))) {
                    unlink(public_path($additionalInfo->donor_valid_id_back));
                }

                if (file_exists(public_path($additionalInfo->donee_valid_id_front))) {
                    unlink(public_path($additionalInfo->donee_valid_id_front));
                }

                if (file_exists(public_path($additionalInfo->donee_valid_id_back))) {
                    unlink(public_path($additionalInfo->donee_valid_id_back));
                }
            
            } else if ($documentRequest->document_type == 'Other Document') {
                $additionalInfo = OtherDocument::where('documentRequest_id', $id)->get()->first();

                if (file_exists(public_path($additionalInfo->valid_id_front))) {
                    unlink(public_path($additionalInfo->valid_id_front));
                }

                if (file_exists(public_path($additionalInfo->valid_id_back))) {
                    unlink(public_path($additionalInfo->valid_id_back));
                }
            }
    
            $route = $this->getRouteByDocumentRequestStatus($documentRequest->documentRequest_status);
    
            DocumentRequest::where('documentRequest_id', $id)->delete();

            if(Feedback::where('transaction_id', $id)->where('transaction_type', 'Document Request')->get()->count() > 0) {
                Feedback::where('transaction_id', $id)->where('transaction_type', 'Document Request')->delete();
            }

            if(DB::table('notifications')->where('data->documentRequest_id', $id)->where('type', 'App\Notifications\NewDocumentRequest')->get()->count() > 0) {
                DB::table('notifications')
                ->where('data->documentRequest_id', $id)
                ->where('type', 'App\Notifications\NewDocumentRequest')
                ->delete();
            }

            if(DB::table('notifications')->where('data->documentRequest_id', $id)->where('type', 'App\Notifications\NewDocumentRequestMessage')->get()->count() > 0) {
                DB::table('notifications')
                ->where('data->documentRequest_id', $id)
                ->where('type', 'App\Notifications\NewDocumentRequestMessage')
                ->delete();
            }
    
            DB::commit();
    
            return redirect()->route($route)->with('success', 'Document Request Deleted Successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
    
            return redirect()->route('document-request')->with('failed', 'Failed to delete Document Request!');
        }
    }
    
    // Function to create delete document request log
    private function createDeleteDocumentRequestLog($user, $documentRequest)
    {
        $logData = [
            'type' => 'Delete Document Request',
            'user' => $user,
            'subject' => 'Delete Document Request Success',
            'message' => 'Document Request ID: ' . $documentRequest->documentRequest_id . ' has been successfully deleted by ' . $user . '.',
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];
    
        Logs::create($logData);
    }
    
    // Function to get route based on document request status
    private function getRouteByDocumentRequestStatus($status)
    {
        $routeMap = [
            'Pending' => 'document-request.pendingDocumentRequest',
            'Declined' => 'document-request.pendingDocumentRequest',
            'Approved' => 'document-request',
            'Cancelled' => 'document-request',
            'Processing' => 'document-request',
            'On Hold' => 'document-request',
            'To Claim' => 'document-request.finishedDocumentRequest',
            'Claimed' => 'document-request.finishedDocumentRequest',
            'Unclaimed' => 'document-request.finishedDocumentRequest',
        ];
    
        return $routeMap[$status] ?? 'document-request';
    }  
    
    public function documentRequestFeedback() {
        $feedback = Feedback::where('transaction_type', 'Document Request')->get();
        return view('document-request.documentRequestFeedbackTable', compact('feedback'));
    }

    public function feedbackForm(string $id) {
        $documentRequest = DocumentRequest::where('documentRequest_id', $id)->first();
        return view('document-request.documentRequestFeedbackForm', compact('documentRequest'));
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
        $feedback = Feedback::where('transaction_id', $id)->where('transaction_type', 'Document Request')->get();
        $rating = $request->rating;
        $comment = $request->comment;

        if($feedback->count() > 0) {
            Feedback::where('transaction_id', $id)->where('transaction_type', 'Document Request')->update([
                'rating' => $rating,
                'comment' => $comment,
                'updated_at' => now('Asia/Manila'),
            ]);
        } else {
            Feedback::create([
                'transaction_id' => $id,
                'transaction_type' => 'Document Request',
                'rating' => $rating,
                'comment' => $comment,
                'created_at' => now('Asia/Manila'),
                'updated_at' => now('Asia/Manila'),
            ]);
        }

        $user = Auth::user()->username;
        Logs::create([
            'type' => 'Add Document Request Feedback',
            'user' => $user,
            'subject' => 'Add Document Request Feedback Success',
            'message' => 'Document Request ID: ' . $id . ' has been successfully add feedback by ' . $user . '.',
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);

        return redirect()->route('document-request.documentRequestDetails', $id)->with('success', 'Feedback Submitted Successfully!');
    }

    public function feedbackEditForm(string $id) {
        $documentRequest = DocumentRequest::where('documentRequest_id', $id)->first();
        $feedback = Feedback::where('transaction_id', $id)->where('transaction_type', 'Document Request')->get()->first();
        $rating = $feedback->rating;
        $comment = $feedback->comment;
        return view('document-request.documentRequestEditFeedbackForm', compact('documentRequest', 'rating', 'comment'));
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
                'type' => 'Edit Document Request Feedback',
                'user' => $user,
                'subject' => 'Edit Document Request Feedback Success',
                'message' => 'Document Request ID: ' . $id . ' has been successfully edit feedback by ' . $user . '.',
                'created_at' => now('Asia/Manila'),
                'updated_at' => now('Asia/Manila'),
            ]);

            return redirect()->route('document-request.feedbackEditForm', $id)->with('success', 'Feedback Edited Successfully!');
        } else {
            return redirect()->route('document-request.feedbackEditForm', $id)->with('failed', 'No changes made!');
        }
    }

    public function deleteFeedback(string $id) {
        $feedback = Feedback::where('transaction_id', $id)->where('transaction_type', 'Document Request')->get()->first();
        return view('document-request.deleteFeedback', compact('feedback'));
    }

    public function destroyFeedback(string $id) {
        $feedback = Feedback::where('transaction_id', $id)->where('transaction_type', 'Document Request')->get()->first();
        $feedback->delete();

        $user = Auth::user()->username;
        Logs::create([
            'type' => 'Delete Document Request Feedback',
            'user' => $user,
            'subject' => 'Delete Document Request Feedback Success',
            'message' => 'Document Request ID: ' . $id . ' has been successfully delete feedback by ' . $user . '.',
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);

        return redirect()->route('document-request.documentRequestFeedback')->with('success', 'Feedback Deleted Successfully!');
    }
}
