<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use App\Models\Logs;
use Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommitteeController extends Controller
{
    //display committee list
    public function index()
    {
        $committees = Committee::orderBy('created_at', 'ASC')->get();
        return view('committee.index', compact('committees'));
    }

    //redirect to add committee page
    public function addCommittee() 
    {
        return view('committee.addCommittee');
    }

    public function validateAddCommitteeForm(Request $request) {
        $Validator = Validator::make($request->all(), [
            '_token' => 'required',
            'name' => 'required|unique:committees,name',
            'chairman_firstName' => 'required',
            'chairman_lastName' => 'required',
            'viceChairman_firstName' => 'required',
            'viceChairman_lastName' => 'required',
            'member1_firstName' => 'required_with:member1_lastName|required_with:member2_firstName|required_with:member2_lastName|required_with:member3_firstName|required_with:member3_lastName',
            'member1_lastName' => 'required_with:member1_firstName|required_with:member2_firstName|required_with:member2_lastName|required_with:member3_firstName|required_with:member3_lastName',
            'member2_firstName' => 'required_with:member2_lastName|required_with:member3_firstName|required_with:member3_lastName',
            'member2_lastName' => 'required_with:member2_firstName|required_with:member3_firstName|required_with:member3_lastName',
            'member3_firstName' => 'required_with:member3_lastName',
            'member3_lastName' => 'required_with:member3_firstName',
        ],
        [
            'name.unique' => 'Committee already exists.',
        ]);

        if ($Validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $Validator->errors()]);
        }else {
            return response()->json(['message' => 'Validation passed']);
        }
    }
    
    //save new committee
    public function saveCommittee(Request $request)
    {
        $chairman = $request->chairman_firstName . ' ' . $request->chairman_lastName;
        $vice_chairman = $request->viceChairman_firstName . ' ' . $request->viceChairman_lastName;
    
        $members = $this->getMembers($request);
    
        $committeeData = $this->prepareCommitteeData($request->name, $chairman, $vice_chairman, $members);
    
        $createCommittee = Committee::create($committeeData);
    
        $user = Auth::user()->username;
    
        if ($createCommittee->save()) {
            $this->logCommitteeAddition($user, $request->name);
    
            return redirect()->route('committee')->with('success', 'Committee Added Successfully!');
        } else {
            return redirect()->route('committe')->with('failed', 'Failed to Add Committee!');
        }
    }
    
    private function getMembers(Request $request)
    {
        $members = [];
    
        for ($i = 1; $i <= 3; $i++) {
            $firstName = "member{$i}_firstName";
            $lastName = "member{$i}_lastName";
    
            if ($request->$firstName != null && $request->$lastName != null) {
                $members[] = $request->$firstName . ' ' . $request->$lastName;
            }
        }
    
        return $members;
    }
    
    private function prepareCommitteeData($name, $chairman, $viceChairman, $members)
    {
        $committeeData = [
            'name' => $name,
            'chairman' => $chairman,
            'vice_chairman' => $viceChairman,
        ];
    
        foreach ($members as $key => $member) {
            $committeeData["member_" . ($key + 1)] = $member;
        }
    
        $committeeData['created_at'] = now('Asia/Manila');
        $committeeData['updated_at'] = now('Asia/Manila');
    
        return $committeeData;
    }
    
    private function logCommitteeAddition($user, $committeeName)
    {
        Logs::create([
            'type' => 'Add Committee',
            'user' => $user,
            'subject' => 'Add Committee Success',
            'message' => $user . ' has successfully added Committee ' . $committeeName . '.',
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now('Asia/Manila'),
        ]);
    }

    //redirect to edit ordinance page
    public function editCommittee(string $id)
    {
        $committee = Committee::findOrFail($id);
    
        $currentChairmanLastName = $this->extractLastName($committee->chairman);
        $currentViceChairmanLastName = $this->extractLastName($committee->vice_chairman);
    
        $members = $this->extractMembersLastNames([$committee->member_1, $committee->member_2, $committee->member_3]);
        list($currentMember1LastName, $currentMember2LastName, $currentMember3LastName) = $members;
    
        return view('committee.editCommittee', compact('committee', 'currentChairmanLastName', 'currentViceChairmanLastName', 'currentMember1LastName', 'currentMember2LastName', 'currentMember3LastName'));
    }
    
    private function extractLastName($fullName)
    {
        $lastName = explode(' ', $fullName);
        return end($lastName);
    }
    
    private function extractMembersLastNames($members)
    {
        $lastNames = array_map(function ($member) {
            return $this->extractLastName($member);
        }, $members);
    
        // Ensure that the array has three elements
        $lastNames += array_fill(0, 3 - count($lastNames), '');
    
        return $lastNames;
    }    

    public function deleteMember1(string $id) {
        return $this->deleteMember($id, 'member_1', 'Member 1');
    }
    
    public function deleteMember2(string $id) {
        return $this->deleteMember($id, 'member_2', 'Member 2');
    }
    
    public function deleteMember3(string $id) {
        return $this->deleteMember($id, 'member_3', 'Member 3');
    }
    
    private function deleteMember($id, $memberField, $memberName) {
        $committee = Committee::findOrFail($id);
    
        if ($committee->$memberField != null) {
            if ($committee->member_2 == null && $committee->member_3 == null) {
                $committee->$memberField = null;
                $committee->updated_at = now('Asia/Manila');
                $committee->save();
    
                return redirect()->route('committee.editCommittee', $id)->with('success', "$memberName Deleted Successfully!");
            } elseif ($committee->member_2 != null && $committee->member_3 == null) {
                return redirect()->route('committee.editCommittee', $id)->with('failed', 'Delete Member 2 First!');
            } elseif ($committee->member_2 != null && $committee->member_3 != null) {
                return redirect()->route('committee.editCommittee', $id)->with('failed', 'Delete Member 3 First!');
            }
        }
    
        return redirect()->route('committee.editCommittee', $id)->with('failed', "Failed to Delete $memberName!");
    }    

    public function validateEditCommitteeForm(Request $request, string $id)
    {
        $committee = Committee::findOrFail($id);
    
        $rules = [
            'name' => 'required|unique:committees,name,' . $committee->id,
            'chairman_firstName' => 'required',
            'chairman_lastName' => 'required',
            'viceChairman_firstName' => 'required',
            'viceChairman_lastName' => 'required',
            'member1_firstName' => 'required_with:member1_lastName|required_with:member2_firstName|required_with:member2_lastName|required_with:member3_firstName|required_with:member3_lastName',
            'member1_lastName' => 'required_with:member1_firstName|required_with:member2_firstName|required_with:member2_lastName|required_with:member3_firstName|required_with:member3_lastName',
            'member2_firstName' => 'required_with:member2_lastName|required_with:member3_firstName|required_with:member3_lastName',
            'member2_lastName' => 'required_with:member2_firstName|required_with:member3_firstName|required_with:member3_lastName',
            'member3_firstName' => 'required_with:member3_lastName',
            'member3_lastName' => 'required_with:member3_firstName',
        ];
    
        // if ($committee->member_1 == null) {
        //     unset($rules['member1_firstName'], $rules['member1_lastName']);
        //     unset($rules['member2_firstName'], $rules['member2_lastName']);
        //     unset($rules['member3_firstName'], $rules['member3_lastName']);
        // } elseif ($committee->member_2 == null) {
        //     unset($rules['member2_firstName'], $rules['member2_lastName']);
        //     unset($rules['member3_firstName'], $rules['member3_lastName']);
        // } elseif ($committee->member_3 == null) {
        //     unset($rules['member3_firstName'], $rules['member3_lastName']);
        // }
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        } else {
            return response()->json(['message' => 'Validation passed']);
        }
    }
    
    private function splitName($fullName) {
        $firstName = implode(' ', array_slice(explode(' ', $fullName), 0, -1));
        $lastName = explode(' ', $fullName);
        $lastName = end($lastName);

        return [$firstName, $lastName];
    }

    private function logCommitteeUpdate($user, $committeeID, $messageStatus, $logStatus, $message, $redirectRoute, $redirectMessage) {
        $committee = Committee::findOrFail($committeeID);
        $committeeName = $committee->name;

        Logs::create([
            'type' => 'Edit Committee',
            'user' => $user,
            'subject' => $logStatus,
            'message' => $user . $message . $committeeName . '.',
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at'=> Carbon::now('Asia/Manila'),
        ]);

        return redirect()
            ->route($redirectRoute, $committeeID)
            ->with($messageStatus, $redirectMessage);
    }

    private function updateChairman($request, $currentFirstName, $currentLastName) {
        if($request->chairman_firstName != $currentFirstName && $request->chairman_lastName == $currentLastName) 
        {
            return $request->chairman_firstName . ' ' . $currentLastName;
        }

        if($request->chairman_firstName == $currentFirstName && $request->chairman_lastName != $currentLastName) 
        {
            return $currentFirstName . ' ' . $request->chairman_lastName;
        }

        if($request->chairman_firstName != $currentFirstName && $request->chairman_lastName != $currentLastName) 
        {
            return $request->chairman_firstName . ' ' . $request->chairman_lastName;
        }

        return $currentFirstName . ' ' . $currentLastName;
    }

    // Function to update vice chairman name
    private function updateViceChairman($request, $currentFirstName, $currentLastName) {
        if($request->viceChairman_firstName != $currentFirstName && $request->viceChairman_lastName == $currentLastName) 
        {
            return $request->viceChairman_firstName . ' ' . $currentLastName;
        }

        if($request->viceChairman_firstName == $currentFirstName && $request->viceChairman_lastName != $currentLastName) 
        {
            return $currentFirstName . ' ' . $request->viceChairman_lastName;
        }

        if($request->viceChairman_firstName != $currentFirstName && $request->viceChairman_lastName != $currentLastName) 
        {
            return $request->viceChairman_firstName . ' ' . $request->viceChairman_lastName;
        }

        return $currentFirstName . ' ' . $currentLastName;
    }

    private function updateMember($request, $currentFirstName, $currentLastName, $requestFirstName, $requestLastName) {
        if($request->$requestFirstName != $currentFirstName && $request->$requestLastName == $currentLastName)
        {
            return $request->$requestFirstName . ' ' . $currentLastName;
        }

        if($request->$requestFirstName == $currentFirstName && $request->$requestLastName != $currentLastName) 
        {
            return $currentFirstName . ' ' . $request->$requestLastName;
        }

        if($request->$requestFirstName != $currentFirstName && $request->$requestLastName != $currentLastName) 
        {
            return $request->$requestFirstName . ' ' . $request->$requestLastName;
        }

        return $currentFirstName . ' ' . $currentLastName;
    }

    //update ordinance details
    public function updateCommittee(Request $request, string $id)
    {
        $committee = Committee::findOrFail($id);
        $user = Auth::user()->username;

        list($currentChairmanFirstName, $currentChairmanLastName) = $this->splitName($committee->chairman);
        list($currentViceChairmanFirstName, $currentViceChairmanLastName) = $this->splitName($committee->vice_chairman);
        list($currentMember1FirstName, $currentMember1LastName) = $this->splitName($committee->member_1);
        list($currentMember2FirstName, $currentMember2LastName) = $this->splitName($committee->member_2);
        list($currentMember3FirstName, $currentMember3LastName) = $this->splitName($committee->member_3);

        if($committee->member_1 == null && $committee->member_2 == null && $committee->member_3 == null) {
            if($request->name != $committee->name || 
                $request->chairman_firstName != $currentChairmanFirstName || 
                $request->chairman_lastName != $currentChairmanLastName || 
                $request->viceChairman_firstName != $currentViceChairmanFirstName || 
                $request->viceChairman_lastName != $currentViceChairmanLastName || 
                $request->filled('member1_firstName') && $request->filled('member1_lastName') ||
                $request->filled('member2_firstName') && $request->filled('member2_lastName') ||
                $request->filled('member3_firstName') && $request->filled('member3_lastName'))
            {
                $committeeName = $committee->name;
                $committeeChairman = $committee->chairman;
                $committeeViceChairman = $committee->vice_chairman;
                
                $committee->name = $committeeName;
                $committee->chairman = $committeeChairman;
                $committee->vice_chairman = $committeeViceChairman;

                //committee name
                if($request->name != $committee->name) 
                {
                    $committee->name = $request->name;
                }

                $committee->chairman = $this->updateChairman($request, $currentChairmanFirstName, $currentChairmanLastName);
                $committee->vice_chairman = $this->updateViceChairman($request, $currentViceChairmanFirstName, $currentViceChairmanLastName);

                if($request->filled('member1_firstName') && $request->filled('member1_lastName')) {
                    $committee->member_1 = $this->updateMember($request, $currentMember1FirstName, $currentMember1LastName, 'member1_firstName', 'member1_lastName');
                }
                if($request->filled('member2_firstName') && $request->filled('member2_lastName')) {
                    $committee->member_2 = $this->updateMember($request, $currentMember2FirstName, $currentMember2LastName, 'member2_firstName', 'member2_lastName');
                }
                if($request->filled('member3_firstName') && $request->filled('member3_lastName')) {
                    $committee->member_3 = $this->updateMember($request, $currentMember3FirstName, $currentMember3LastName, 'member3_firstName', 'member3_lastName');
                }

                $committee->updated_at = Carbon::now('Asia/Manila');
                $updateCommittee = $committee->update();

                if($updateCommittee) 
                {
                    return $this->logCommitteeUpdate($user, $committee->id, 'success', 'Edit Committee Details Success', ' has successfully edited committee details of ', 'committee.editCommittee', 'Committee Details Updated Successfully!');
                }
                else 
                {
                    return $this->logCommitteeUpdate($user, $committee->id, 'failed', 'Edit Committee Details Failed', ' has failed to edit committee details of ', 'committee.editCommittee', 'Failed to Update Committee Details!');
                }

            }else {
                return redirect()
                ->route('committee.editCommittee', $committee->id)
                ->with('failed', 'Fill Up a Field!');
            }
        }

        if($committee->member_1 != null && $committee->member_2 == null && $committee->member_3 == null) {
            if($request->name != $committee->name || 
                $request->chairman_firstName != $currentChairmanFirstName || 
                $request->chairman_lastName != $currentChairmanLastName || 
                $request->viceChairman_firstName != $currentViceChairmanFirstName || 
                $request->viceChairman_lastName != $currentViceChairmanLastName || 
                $request->member1_firstName != $currentMember1FirstName || 
                $request->member1_lastName != $currentMember1LastName ||                
                $request->member2_firstName != null ||
                $request->member2_lastName != null ||
                $request->member3_firstName != null ||
                $request->member3_lastName != null)
            {
                $committeeName = $committee->name;
                $committeeChairman = $committee->chairman;
                $committeeViceChairman = $committee->vice_chairman;
                $committeeMember1 = $committee->member_1;

                $committee->name = $committeeName;
                $committee->chairman = $committeeChairman;
                $committee->vice_chairman = $committeeViceChairman;
                $committee->member_1 = $committeeMember1;

                //committee name
                if($request->name != $committee->name) 
                {
                    $committee->name = $request->name;
                }

                $committee->chairman = $this->updateChairman($request, $currentChairmanFirstName, $currentChairmanLastName);
                $committee->vice_chairman = $this->updateViceChairman($request, $currentViceChairmanFirstName, $currentViceChairmanLastName);

                if($request->filled('member1_firstName') && $request->filled('member1_lastName')) {
                    $committee->member_1 = $this->updateMember($request, $currentMember1FirstName, $currentMember1LastName, 'member1_firstName', 'member1_lastName');
                }
                if($request->filled('member2_firstName') && $request->filled('member2_lastName')) {
                    $committee->member_2 = $this->updateMember($request, $currentMember2FirstName, $currentMember2LastName, 'member2_firstName', 'member2_lastName');
                }
                if($request->filled('member3_firstName') && $request->filled('member3_lastName')) {
                    $committee->member_3 = $this->updateMember($request, $currentMember3FirstName, $currentMember3LastName, 'member3_firstName', 'member3_lastName');
                }

                $committee->updated_at = Carbon::now('Asia/Manila');
                $updateCommittee = $committee->update();

                if($updateCommittee) 
                {
                    return $this->logCommitteeUpdate($user, $committee->id, 'success', 'Edit Committee Details Success', ' has successfully edited committee details of ', 'committee.editCommittee', 'Committee Details Updated Successfully!');
                }
                else 
                {
                    return $this->logCommitteeUpdate($user, $committee->id, 'failed', 'Edit Committee Details Failed', ' has failed to edit committee details of ', 'committee.editCommittee', 'Failed to Update Committee Details!');
                }

            }else {
                return redirect()
                ->route('committee.editCommittee', $committee->id)
                ->with('failed', 'Fill Up a Field!');
            }
        }

        if($committee->member_1 != null && $committee->member_2 != null && $committee->member_3 == null) {
            if($request->name != $committee->name || 
                $request->chairman_firstName != $currentChairmanFirstName || 
                $request->chairman_lastName != $currentChairmanLastName || 
                $request->viceChairman_firstName != $currentViceChairmanFirstName || 
                $request->viceChairman_lastName != $currentViceChairmanLastName || 
                $request->member1_firstName != $currentMember1FirstName || 
                $request->member1_lastName != $currentMember1LastName ||
                $request->member2_firstName != $currentMember2FirstName ||
                $request->member2_lastName != $currentMember2LastName ||
                $request->member3_firstName != null ||
                $request->member3_lastName != null)
            {
                $committeeName = $committee->name;
                $committeeChairman = $committee->chairman;
                $committeeViceChairman = $committee->vice_chairman;
                $committeeMember1 = $committee->member_1;
                $committeeMember2 = $committee->member_2;

                $committee->name = $committeeName;
                $committee->chairman = $committeeChairman;
                $committee->vice_chairman = $committeeViceChairman;
                $committee->member_1 = $committeeMember1;
                $committee->member_2 = $committeeMember2;

                //committee name
                if($request->name != $committee->name) 
                {
                    $committee->name = $request->name;
                }

                $committee->chairman = $this->updateChairman($request, $currentChairmanFirstName, $currentChairmanLastName);
                $committee->vice_chairman = $this->updateViceChairman($request, $currentViceChairmanFirstName, $currentViceChairmanLastName);

                if($request->filled('member1_firstName') && $request->filled('member1_lastName')) {
                    $committee->member_1 = $this->updateMember($request, $currentMember1FirstName, $currentMember1LastName, 'member1_firstName', 'member1_lastName');
                }
                if($request->filled('member2_firstName') && $request->filled('member2_lastName')) {
                    $committee->member_2 = $this->updateMember($request, $currentMember2FirstName, $currentMember2LastName, 'member2_firstName', 'member2_lastName');
                }
                if($request->filled('member3_firstName') && $request->filled('member3_lastName')) {
                    $committee->member_3 = $this->updateMember($request, $currentMember3FirstName, $currentMember3LastName, 'member3_firstName', 'member3_lastName');
                }

                $committee->updated_at = Carbon::now('Asia/Manila');
                $updateCommittee = $committee->update();

                if($updateCommittee) 
                {
                    return $this->logCommitteeUpdate($user, $committee->id, 'success', 'Edit Committee Details Success', ' has successfully edited committee details of ', 'committee.editCommittee', 'Committee Details Updated Successfully!');
                }
                else 
                {
                    return $this->logCommitteeUpdate($user, $committee->id, 'failed', 'Edit Committee Details Failed', ' has failed to edit committee details of ', 'committee.editCommittee', 'Failed to Update Committee Details!');
                }

            }else {
                return redirect()
                ->route('committee.editCommittee', $committee->id)
                ->with('failed', 'Fill Up a Field!');
            }
        }

        if($committee->member_1 != null && $committee->member_2 != null && $committee->member_3 != null) {
            if($request->name != $committee->name || 
                $request->chairman_firstName != $currentChairmanFirstName || 
                $request->chairman_lastName != $currentChairmanLastName || 
                $request->viceChairman_firstName != $currentViceChairmanFirstName || 
                $request->viceChairman_lastName != $currentViceChairmanLastName || 
                $request->member1_firstName != $currentMember1FirstName || 
                $request->member1_lastName != $currentMember1LastName ||
                $request->member2_firstName != $currentMember2FirstName ||
                $request->member2_lastName != $currentMember2LastName ||
                $request->member3_firstName != $currentMember3FirstName ||
                $request->member3_lastName != $currentMember3LastName)
            {
                $committeeName = $committee->name;
                $committeeChairman = $committee->chairman;
                $committeeViceChairman = $committee->vice_chairman;
                $committeeMember1 = $committee->member_1;
                $committeeMember2 = $committee->member_2;
                $committeeMember3 = $committee->member_3;

                $committee->name = $committeeName;
                $committee->chairman = $committeeChairman;
                $committee->vice_chairman = $committeeViceChairman;
                $committee->member_1 = $committeeMember1;
                $committee->member_2 = $committeeMember2;
                $committee->member_3 = $committeeMember3;

                //committee name
                if($request->name != $committee->name) 
                {
                    $committee->name = $request->name;
                }

                $committee->chairman = $this->updateChairman($request, $currentChairmanFirstName, $currentChairmanLastName);
                $committee->vice_chairman = $this->updateViceChairman($request, $currentViceChairmanFirstName, $currentViceChairmanLastName);

                if($request->filled('member1_firstName') && $request->filled('member1_lastName')) {
                    $committee->member_1 = $this->updateMember($request, $currentMember1FirstName, $currentMember1LastName, 'member1_firstName', 'member1_lastName');
                }
                if($request->filled('member2_firstName') && $request->filled('member2_lastName')) {
                    $committee->member_2 = $this->updateMember($request, $currentMember2FirstName, $currentMember2LastName, 'member2_firstName', 'member2_lastName');
                }
                if($request->filled('member3_firstName') && $request->filled('member3_lastName')) {
                    $committee->member_3 = $this->updateMember($request, $currentMember3FirstName, $currentMember3LastName, 'member3_firstName', 'member3_lastName');
                }

                $committee->updated_at = Carbon::now('Asia/Manila');
                $updateCommittee = $committee->update();

                if($updateCommittee) 
                {
                    return $this->logCommitteeUpdate($user, $committee->id, 'success', 'Edit Committee Details Success', ' has successfully edited committee details of ', 'committee.editCommittee', 'Committee Details Updated Successfully!');
                }
                else 
                {
                    return $this->logCommitteeUpdate($user, $committee->id, 'failed', 'Edit Committee Details Failed', ' has failed to edit committee details of ', 'committee.editCommittee', 'Failed to Update Committee Details!');
                }

            }else {
                return redirect()
                ->route('committee.editCommittee', $committee->id)
                ->with('failed', 'Fill Up a Field!');
            }
        }
    }

    //redirect to delete committee page
    public function deleteCommittee(string $id)
    {
        $committee = Committee::findOrFail($id);
        return view('committee.deleteCommittee', compact('committee'));
    }

    //delete ordinance
    public function destroyCommittee(string $id)
    {
        try {
            DB::beginTransaction();
    
            $committee = Committee::findOrFail($id);
            $user = Auth::user()->username;
    
            $this->createCommitteeDeleteLog($user, $committee);
    
            Committee::destroy($id);
    
            DB::commit();
    
            return redirect()->route('committee')->with('success', 'Committee Deleted Successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
    
            $this->createCommitteeDeleteLog($user, $committee, 'Failed to delete Committee', $e->getMessage());
    
            return redirect()->route('committee')->with('failed', 'Failed to delete Committee!');
        }
    }
    
    // Function to create committee delete log
    private function createCommitteeDeleteLog($user, $committee, $subject = 'Delete Committee Success', $errorMessage = null)
    {
        $logData = [
            'type' => 'Delete Committee',
            'user' => $user,
            'subject' => $subject,
            'message' => $committee->name . ' has been ' . strtolower($subject) . ' by ' . $user . '.',
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];
    
        if ($errorMessage) {
            $logData['message'] .= ' Error: ' . $errorMessage;
        }
    
        Logs::create($logData);
    }
    
}