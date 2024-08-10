<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use Illuminate\Http\Request;
use App\Models\Ordinances;
use App\Models\Committee;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrdinanceController extends Controller
{
    //display ordinance list
    public function index()
    {
        $ordinance = Ordinances::where('is_active', true)->orderBy('ordinance_number', 'ASC')->get();
        return view('ordinance.index', compact('ordinance'));
    }

    //redirect to add ordinance page
    public function addOrdinance() 
    {
        $committee = Committee::orderBy('name', 'ASC')->get();
        return view('ordinance.addOrdinance', compact('committee'));
    }

    public function validateAddOrdinanceForm(Request $request) {
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'committee' => 'required',
            'ordinance_number' => 'required|unique:ordinances,ordinance_number',
            'date_approved' => 'required|date',
            'description' => 'required',
            'ordinance_file' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        }else {
            return response()->json(['message' => 'Validation passed']);
        }
    }

    //store/save new ordinance
    public function saveOrdinance(Request $request)
    {
        $filePath = $this->uploadOrdinanceFile($request);
    
        $ordinanceData = [
            'committee' => $request->committee,
            'ordinance_number' => $request->ordinance_number,
            'date_approved' => $request->date_approved,
            'description' => $request->description,
            'ordinance_file' => $filePath,
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];
    
        $createOrdinance = Ordinances::create($ordinanceData);
    
        $user = Auth::user()->username;
    
        if ($createOrdinance) {
            $this->logAddOrdinanceSuccess($user, $request->ordinance_number);
            return redirect()->route('ordinance')->with('success', 'Ordinance Added Successfully!');
        } else {
            $this->logAddOrdinanceFailed($user, $request->ordinance_number);
            return redirect()->route('ordinance')->with('failed', 'Failed to Add Ordinance!');
        }
    }
    
    // upload ordinance file
    private function uploadOrdinanceFile(Request $request)
    {
        if ($request->hasFile('ordinance_file')) {
            $file = $request->file('ordinance_file');
            $originalFileName = $file->getClientOriginalName();
            
            // Replace spaces with underscores in the file name
            $fileName = time() . '_' . Str::slug(str_replace(' ', '_', pathinfo($originalFileName, PATHINFO_FILENAME))) . '.' . $file->getClientOriginalExtension();
            
            $filePath = 'uploads/ordinances/' . $fileName;
            $file->move('uploads/ordinances/', $fileName);
        } else {
            $filePath = ''; // Adjust this based on your default file path or behavior
        }
    
        return $filePath;
    }    
    
    // log the success add ordinance
    private function logAddOrdinanceSuccess($user, $ordinanceNumber)
    {
        Logs::create([
            'type' => 'Add Ordinance',
            'user' => $user,
            'subject' => 'Add Ordinance Success',
            'message' => "$user has successfully added Ordinance No. $ordinanceNumber.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }
    
    // log the failed add ordinance
    private function logAddOrdinanceFailed($user, $ordinanceNumber)
    {
        Logs::create([
            'type' => 'Add Ordinance',
            'user' => $user,
            'subject' => 'Add Ordinance Failed',
            'message' => "$user has failed to add Ordinance No. $ordinanceNumber.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }    

    //redirect to edit ordinance page
    public function editOrdinance(string $id)
    {
        $ordinance = Ordinances::findorFail($id);
        $committee = Committee::orderBy('name', 'ASC')->get();
        return view('ordinance.editOrdinance', compact('ordinance', 'committee'));
    }

    public function validateEditOrdinanceForm(Request $request, string $id) {
        $ordinance = Ordinances::findOrFail($id);

        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'ordinance_number' => 'numeric|unique:ordinances,ordinance_number,' . $ordinance->id,
            'date_approved' => 'date',
            'ordinance_file' => 'mimes:pdf',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        }else {
            return response()->json(['message' => 'Validation passed']);
        }
    }

    //update ordinance details
    public function updateOrdinance(Request $request, string $id)
    {
        $ordinance = Ordinances::findOrFail($id);
        $user = Auth::user()->username;
    
        if ($this->shouldUpdateOrdinance($ordinance, $request)) {
            $this->updateOrdinanceDetails($ordinance, $request);
            $this->handleFileUpload($ordinance, $request);
    
            if ($this->saveUpdatedOrdinance($ordinance, $user)) {
                return redirect()
                    ->route('ordinance.editOrdinance', $ordinance->id)
                    ->with('success', 'Ordinance Details Updated Successfully!');
            } else {
                return redirect()
                    ->route('ordinance.editOrdinance', $ordinance->id)
                    ->with('failed', 'Failed to Update Ordinance!');
            }
        } else {
            return redirect()
                ->route('ordinance.editOrdinance', $ordinance->id)
                ->with('failed', 'Fill Up a Field!');
        }
    }
    
    private function shouldUpdateOrdinance(Ordinances $ordinance, Request $request): bool
    {
        return $ordinance->committee != $request->committee ||
            $ordinance->ordinance_number != $request->ordinance_number ||
            $ordinance->date_approved != $request->date_approved ||
            $ordinance->description != $request->description ||
            $request->hasFile('ordinance_file');
    }
    
    private function updateOrdinanceDetails(Ordinances $ordinance, Request $request): void
    {
        if ($ordinance->committee != $request->committee) {
            $ordinance->committee = $request->committee;
        }
    
        if ($request->filled('ordinance_number')) {
            $ordinance->ordinance_number = $request->ordinance_number;
        }
    
        if ($ordinance->date_approved != $request->date_approved) {
            $ordinance->date_approved = $request->date_approved;
        }
    
        if ($ordinance->description != $request->description) {
            $ordinance->description = $request->description;
        }
    }
    
    private function handleFileUpload(Ordinances $ordinance, Request $request): void
    {
        if ($request->hasFile('ordinance_file')) {
            $file = $request->file('ordinance_file');
            
            // Replace spaces with underscores in the file name
            $originalFileName = str_replace(' ', '_', $file->getClientOriginalName());
            $fileName = time() . '_' . $originalFileName;
            $filePath = 'uploads/ordinances/' . $fileName;
            $file->move('uploads/ordinances/', $fileName);
    
            if ($ordinance->ordinance_file) {
                unlink(public_path($ordinance->ordinance_file));
            }
    
            $ordinance->ordinance_file = $filePath;
        }
    }    
    
    private function saveUpdatedOrdinance(Ordinances $ordinance, string $user): bool
    {
        $ordinance->updated_at = Carbon::now('Asia/Manila');
    
        $updateOrdinance = $ordinance->update();
    
        $this->logUpdateStatus($user, $ordinance, $updateOrdinance);
    
        return $updateOrdinance;
    }
    
    private function logUpdateStatus(string $user, Ordinances $ordinance, bool $updateStatus): void
    {
        $logData = [
            'type' => 'Edit Ordinance',
            'user' => $user,
            'subject' => $updateStatus ? 'Edit Ordinance Details Success' : 'Edit Ordinance Details Failed',
            'message' => $user . ' has ' . ($updateStatus ? 'successfully' : 'failed') .
                ' edited ordinance details of Ordinance No. ' . $ordinance->ordinance_number . '.',
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now('Asia/Manila'),
        ];
    
        Logs::create($logData);
    }

    //delete ordinance
    public function destroyOrdinance(string $id)
    {
        try {
            DB::beginTransaction();
    
            $ordinance = Ordinances::findOrFail($id);
            $user = Auth::user()->username;
    
            $this->createOrdinanceDeleteLog($user, $ordinance);
    
            $ordinance->is_active = false;
            $ordinance->updated_at = now('Asia/Manila');

            $ordinance->update();
    
            DB::commit();
    
            return redirect()->route('ordinance')->with('success', 'Ordinance Deleted Successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
    
            $this->createOrdinanceDeleteLog($user, $ordinance, 'Failed to delete Ordinance', $e->getMessage());
    
            return redirect()->route('ordinance')->with('failed', 'Failed to delete Ordinance!');
        }
    }
    
    // Function to create ordinance delete log
    private function createOrdinanceDeleteLog($user, $ordinance, $subject = 'Delete Ordinance Success', $errorMessage = null)
    {
        $logData = [
            'type' => 'Delete Ordinance',
            'user' => $user,
            'subject' => $subject,
            'message' => 'Ordinance No. ' . $ordinance->ordinance_number . ' has been ' . strtolower($subject) . ' by ' . $user . '.',
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];
    
        if ($errorMessage) {
            $logData['message'] .= ' Error: ' . $errorMessage;
        }
    
        Logs::create($logData);
    }    
}