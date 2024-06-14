<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\ParentReferral;
use App\Models\Branch;
use App\Models\ReferralStatus;
use App\Models\Product;
use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use App\Policies\MyReferralPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ReferralCollection;
use App\Http\Resources\BranchResource;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\DB;

class MyReferralController extends Controller
{
    public function index()
    {
        $this->authorize('viewAnyMyReferral',Referral::class);
        return new ReferralCollection(Referral::with('branch')
        ->where('created_by',Auth::id())
        ->orderBy('id','desc')->get());
    }


    public function store(Request $request )
    {
        // dd($request->all());
        $this->authorize('createMyReferral',Referral::class);
        $validated=$request->validate([
            'parent_name' => ['required', 'max:30'],
            'phone' => ['required', 'max:10'],
            'branch_id'=>['required'],
        ]);
        $data=$request->all();
        $data['commission_percentage']=Branch::find($request->branch_id)->commission;
        // $data['commission_percentage'] = DB::table('branches')->find($request->branch_id)->commission;
        // $data['max_discount']=Branch::find($request->branch_id)->max_discount;
        $data['max_discount'] = DB::table('branches')->find($request->branch_id)->max_discount;
        $data['created_by']=Auth::id();
        $data['updated_by']=Auth::id();
        $data['payment_status_id']=1;
        $data['referral_status_id']=1;
        $data['commission_type']=Auth::user()->commissionType->commission_type;
        // dd(  $data['commission_type']=Auth::user()->commissionType->commission_type);
        //create for others
        if(!is_null($request->forUser)){
            if(is_string($request->forUser)){
                // $forUser=User::where('name',$request->forUser)->first();
                $forUser = DB::table('users')->where('name',$request->forUser)->first();
                if($forUser)$data['created_by']=$forUser->id;
            }else{
                $data['created_by']=$request->forUser['id'];
            }
        }
        $parent=ParentReferral::create($data);
        foreach($data['students'] as $student){
            if($student['student_name']){
                $d=$data;
                $d['student_name']=$student['student_name'];
                $d['program_id']=$student['program_id'];
                $d['program_id']=$student['program_id'];
                $d['parent_referral_id']=$parent->id;
                $referral = Referral::create($d);
            }
           
        }
         //notification and mail
        // $role_id=Role::where('name','Customer Service')->first()->id;
        $role_id = DB::table('roles')->where('name','Customer Service')->first()->id;
        $user=User::with('roles')
        
            ->where('branch_id',$request->branch_id)
            ->whereHas('roles', function ($query) use($role_id) {//all referrals
                $query->where('roles.id',$role_id);
            })
            ->get();
         foreach($user as $u){
             Notification::create([
                 'from_user_id'=>Auth::user()->id,
                 'user_id'=>$u->id,
                 'role_id'=>$role_id,
                 'title'=>'Referral',
                 'message'=>'Create Referral Successfully',
                 'link'=>'/referrals/'.$parent->id,
             ]);
             $object=[
                 'topic'=>'Referral',
                 'message'=>Auth::user()->name.' has create Referral successfully. '.$u->name.' please help check. ',
                 'link'=>env('APP_URL').'/referrals/'.$parent->id,
             ];
             Mail::to($u->email)->send(new NotificationMail($object));
         }
         //end notification and mail
        return [
            'message'=> 'Create Referral successfully ',
            'snackColor'=> 'success',
            'referral' =>$referral,
        ];

    }

    public function show(Referral $myReferral)
    {
        $this->authorize('viewMyReferral', $myReferral);
        return [
            'referral'=>$myReferral,
            'branches'=>BranchResource::collection(Branch::where('school_id','!=',1)->get()),
            'statuses'=>ReferralStatus::get(),
        ];
    }

    public function update(Request $request, Referral $myReferral)
    {
        
        $this->authorize('updateMyReferral', $myReferral);
        $validated=$request->validate([
            'parent_name' => ['required', 'max:30'],
            'phone' => ['required', 'max:10'],
            'branch_id'=>['required'],
        ]);
        $data=$request->all();
        $data['updated_by']=Auth::id();
        $data['referral_status_id']=1;
        $myReferral->update($data);
        return [
            'message'=> 'Submit Referral successfully ',
            'snackColor'=> 'success',
        ];
    }

    public function destroy(Referral $myReferral)
    {
        $this->authorize('deleteMyReferral', $myReferral);
    }
}
