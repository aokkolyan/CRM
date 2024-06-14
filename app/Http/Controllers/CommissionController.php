<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\Branch;
use App\Models\ReferralStatus;
use App\Policies\CommissionPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ReferralCollection;
use App\Models\Module;
use App\Models\Attachment;

class CommissionController extends Controller
{
    public function index()
    {
        $this->authorize('viewAnyCommission',Referral::class);
        return new ReferralCollection(Referral::with('branch')
        ->where('referral_status_id', 4)
        ->where(function ($query) {
            $query->where('branch_id',Auth::user()->branch_id)
                ->orWhereIn('branch_id',Auth::user()->branches->pluck('id'));
        })
        ->orderBy('id','desc')->get());
    }


    public function store(Request $request)
    {
        $this->authorize('createCommission',Referral::class);
        $validated=$request->validate([
            'parent_name' => ['required', 'max:30'],
            'phone' => ['required', 'max:10'],
            'branch_id'=>['required'],
        ]);
        $data=$request->all();
        $data['commission_percentage']=Branch::find($request->branch_id)->commission;
        $data['created_by']=Auth::id();
        $data['updated_by']=Auth::id();
        $referral=Referral::create($data);
        return [
            'message'=> 'Create Referral successfully ',
            'snackColor'=> 'success',
            'referral'=>$referral,
        ];

    }

    public function show(Referral $commission)
    {
        $this->authorize('viewCommission', $commission);
        return [
            'referral'=>$commission,
            'branches'=>Branch::where('school_id','!=',1)->get(),
            'statuses'=>ReferralStatus::get(),
        ];
    }

    public function update(Request $request, Referral $commission)
    {
        $commission->update([
            'payment_status_id'=>2,
            'paid_at'=>now(),
        ]);
        return [
            'message'=> 'Payment Marked As Paid ',
            'snackColor'=> 'success',
        ];
       
    }
    public function import(Request $request,$commission_id)
    {
        $commission=Referral::find($commission_id);
        $this->authorize('update', $commission);
      
        $validated=$request->validate([
            'file' => ['required'],
        ]);
        $file=$request->file('file');
        $name=$file->getClientOriginalName();
        $stored=$file->store('ReferralAttachment','public');
        $module=Module::where('name','commission')->first();
        Attachment::create([
            'module_id'=>$module->id??0,
            'ref_id'=>$commission->id,
            'path'=>$stored,
            'name'=>$name,
        ]);
       
        return [
            'attachments'=>Referral::find($commission_id)->attachments,
            'message'=> 'Upload successfully ',
            'snackColor'=> 'success',
        ];
    }
    public function destroy(Referral $commission)
    {
        $this->authorize('deleteCommission', $commission);
    }
}
