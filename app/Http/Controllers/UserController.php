<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\KycSubmission;
use App\Models\TempKyc;
use App\Models\LegacyUser;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\AptPayTransaction;
use App\Models\TempPop;
use App\Notifications\PopUploadSuccessfulNotification;
use App\Notifications\PopUploadedNotification;
use Illuminate\Support\Facades\Notification;



class UserController extends Controller
{

public function settings()
{
    $user = Auth::user();
    return view('settings', compact('user'));
}

public function update(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => "required|email|unique:users,email,{$user->id}",
        'password' => 'nullable|string|min:8|confirmed',
    ]);

    $user->name = $request->name;
    $user->email = $request->email;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return back()->with('success', 'Profile updated successfully.');
}

public function kycTmpUpload(Request $request){
    if ($request->hasFile('video')) {
    
    $video = $request->file('video');
    $filename = $video->store('kyc_temp');
    TempKyc::create([
        'file' => $filename,
    ]);
    return $filename;
    }
return '';
}


public function popTmpUpload(Request $request){
    // Validate the request to ensure a file is uploaded
    if ($request->hasFile('popUser')) {
    
    $pop = $request->file('popUser');
    $filename = $pop->store('pop_temp');
    TempPop::create([
        'file' => $filename,
    ]);
    return $filename;
    }
   
return '';
}

    public function submitPop(Request $request)
{    
        $request->validate([
        'txnId' => 'required',
        'popUser' => 'required',
    ]);

 $tmp_pop = TempPop::where('file', $request->popUser)->first();
    $id = $request->input('txnId');

   
    
    if ($tmp_pop) {
            Storage::copy($tmp_pop->file, 'pop/'.$tmp_pop->file);
    }


    

    $transaction = AptPayTransaction::find($id);
    if ($transaction) {
        $transaction->Pop = $tmp_pop ? $tmp_pop->file : null;
        $transaction->Status = 4; // Set status to "Processing"
        $transaction->save();

    // Notify the user about the POP submission\


    $user = $transaction ? LegacyUser::find($transaction->UserId) : null;
    $user->notify(new PopUploadSuccessfulNotification($transaction));
    
    Notification::route('mail', 'support@orion-pay.ca')
    ->notify(new PopUploadedNotification($transaction));

    } else {
        // handle not found case
    return redirect('/payment-management')->with('error', 'Transaction not found.');
    }
    // Clean up the temporary POP file
    Storage::delete($tmp_pop->file);


    return redirect()->route('payment.management')->with('success', 'Your Proof of payment has been submitted successfully. We will review it shortly.');
}

    public function submit(Request $request)
{
        $user = LegacyUser::find($request->user_id);
    $request->validate([
        'identificationType' => 'required|in:drivers_license,passport,other',
        'video' => 'required|max:51200',
        'proofOfAddress' => 'required|file|mimes:jpg,jpeg,png,pdf',
        'user_id' => 'required',
    ]);

    $tmp_kyc_vid = TempKyc::where('file', $request->video)->first();
    $idType = $request->input('identificationType');

    if ($idType === 'drivers_license') {
        $request->validate([
            'drivers_license_front' => 'required|file|mimes:jpg,jpeg,png,pdf',
            'drivers_license_back' => 'required|file|mimes:jpg,jpeg,png,pdf',
        ]);
        $frontPath = $request->file('drivers_license_front')->store('kyc_documents');
        $backPath = $request->file('drivers_license_back')->store('kyc_documents');
    } elseif ($idType === 'passport') {

        $request->validate([
            'passport_front' => 'required|file|mimes:jpg,jpeg,png,pdf',
        ]);    
        $frontPath = $request->file('passport_front')->store('kyc_documents');
        $backPath = null;
    } elseif ($idType === 'other') {
        $request->validate([
            'other_id_type' => 'required|string',
            'other_id_front' => 'required|file|mimes:jpg,jpeg,png,pdf',
            'other_id_back' => 'required|file|mimes:jpg,jpeg,png,pdf',
        ]);
        $frontPath = $request->file('other_id_front')->store('kyc_documents');
        $backPath = $request->file('other_id_back')->store('kyc_documents');
    }
    if ($tmp_kyc_vid) {
            Storage::copy($tmp_kyc_vid->file, 'kyc_videos/'.$tmp_kyc_vid->file);
    }
            $poaPath = $request->file('proofOfAddress')->store('kyc_documents');


    $kycSubmission = KycSubmission::create([
        'id' => Str::uuid(),
        'user_id' => $request->user_id,
        'identification_type' => $idType,
        'other_id_type' => $request->input('other_id_type'),
        'poa_path' => $poaPath,
        'document_front_path' => $frontPath,
        'document_back_path' => $backPath,
        'video_path' => $tmp_kyc_vid ? $tmp_kyc_vid->file : null,
        'status' => 0,
        'submitted_at' => now(),
        'from_admin' => true,
    ]);
    Storage::delete($tmp_kyc_vid->file);
    // Notify the user about the KYC submission
    if ($kycSubmission) {
            return redirect()->back()->with('success', 'KYC submitted successfully. Your submission is under review.');

    } else {
        return redirect()->back()->with('error', 'Failed to submit KYC. Please try again.');

    }


}
}