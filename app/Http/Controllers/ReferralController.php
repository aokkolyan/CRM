<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\School;
use App\Models\Product;
use App\Models\Referral;
use Illuminate\Http\Request;
use App\Models\ParentReferral;
use App\Models\ReferralStatus;
use App\Models\ReferralProcess;
use App\Models\CommissionPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\BranchResource;
use App\Http\Resources\ReferralResource;
use App\Http\Resources\ReferralCollection;

use App\Http\Resources\ParentReferralResource;
use App\Http\Resources\ParentReferralCollection;
use App\Http\Resources\ReferralProcessCollection;

class ReferralController extends Controller
{
    public function __construct()
    {
        
        $this->authorizeResource(Referral::class, 'referral');

        
    }

    public function index()
    {
        return new ParentReferralCollection(ParentReferral::with('referrals')
        ->whereHas('referrals', function ($query) {
            $query->where('branch_id',Auth::user()->branch_id)
            ->orWhereIn('branch_id',Auth::user()->branches->pluck('id'));
        })
        ->orderBy('id','desc')->get());
    }


    public function store(Request $request)
    {
        // dd($request->all());
        $validated=$request->validate([
            'student_name' => ['required', 'max:30'],
            'branch'=>['required'],
            'parent_referral_id'=>['required'],
        ]);
        $data=$request->all();
        $data['branch_id']=$request->branch['id'];
        $data['commission_percentage']=Branch::find($request->branch['id'])->commission;
        $data['created_by']=Auth::id();
        $data['updated_by']=Auth::id();
        $data['payment_status_id']=1;
        $data['referral_status_id']=1;
        $referral=Referral::create($data);

    }

    public function showParent($parent_referral_id)
    {
        return [
            'referral'=>new ParentReferralResource(ParentReferral::find($parent_referral_id)),
            // 'referral_processes'=>new ReferralProcessCollection($referral->processes),
            'branches'=>Branch::where('school_id','!=',1)->get(),
            'schools'=>School::where('id','!=',1)->get(),
            'programs'=>Product::where('category_id',1)->get(),
            'statuses'=>ReferralStatus::get(),
            'referral_processes' => new ReferralProcessCollection(ReferralProcess::where('parent_referral_id',$parent_referral_id)->get()),
        ];
    }

    public function update(Request $request, Referral $referral)
    {
      
        //  dd($request->all());
        $validated=$request->validate([
            'student_name' => ['required', 'max:30'],
            'branch'=>['required'],
            'discount_percentage'=>['required','max:100'],
        ]);
        $data=$request->all();
        unset($data['program']);
        unset($data['branch']);
        $data['branch_id']=$request->branch['id'];
        $school=School::find($request->branch['school_id']);
        $school_pointMultiplyier = $school->point_multiplyier;
        $school_coupon_percentage = $school->coupon_percentage;
        // dd($school_coupon_percentage);
    
     
        // if(!is_null($request->full_fee)){
            // $data['discount']=$request->discount_percentage>0?$request->full_fee*($request->discount_percentage/100):0;
            // $data['fee']=$request->discount_percentage>0?$request->full_fee*(1-$request->discount_percentage/100):$request->full_fee;
            $commissionBeforeTax=round($request->discount_percentage<=$referral->max_discount?$referral->commission_percentage/100*$data['fee']:0, 2);
        
            //Cash tax 15%
            if($data['commission_type']=='cash'){
                $data['wht']=round($commissionBeforeTax*0.15, 2);
                $data['commission']=$commissionBeforeTax-$data['wht'];

            }else if($data['commission_type']=='point'){//point x30 of commission
                $data['commission']=$commissionBeforeTax*$school_pointMultiplyier;
                $data['wht']=0;
            }else{//coupon , has no tax
                $data['commission']=$request->discount_percentage<=$referral->max_discount?$school_coupon_percentage:0;
                // $data['commission']=$request->discount_percentage<=$referral->max_discount?5:0;
                $data['wht']=0;
            }
           
        // }
        // if($request->full_fee==$referral->full_fee&&$request->discount_percentage==$referral->discount_percentage&&$data['commission']!=$request->commission)
        // {
        //     $data['commission']=$request->commission;
        // }
        if($request->referral_status_id==4&&$request->referral_status_id!=$referral->referral_status_id)
        {   

            $data['enrolled_at']=now();
            $data['enrolled_by']=Auth::id();
        }else{
            $data['enrolled_at']=$referral->enrolled_at;
        }    
        //if rejected, removed from report
        if($request->referral_status_id==3)
        {
            $data['weekly_report_referral_id']=null;
        }
        $data['updated_by']=Auth::id();
        $referral->update($data);
        return new ReferralResource($referral);
    }

    public function destroy(Referral $referral)
    {
        //
    }

    public function contact(Request $request,$parent_referral_id)
    {
        $parent_referral=ParentReferral::find($parent_referral_id);
        ReferralProcess::create([
            'created_by'=>Auth::id(),
            'remark'=>$request->remark,
            'is_contact'=>true,
            'parent_referral_id'=>$parent_referral_id
        ]);
        return new ReferralProcessCollection(ReferralProcess::where('parent_referral_id',$parent_referral_id)->get());

       
    }
    public function allReferrers()
    {
        $this->authorize('viewAnyReport',Referral::class);
     
        $referrers=User::withCount('referrals')->with('referrals','referralsInMyBranchApprovedNotYetPaid')
            ->whereHas('referrals', function ($query) {//all referrals
                $query->where('branch_id',Auth::user()->branch_id)
                ->orWhereIn('branch_id',Auth::user()->branches->pluck('id'));
            })
            ->orderBy('referrals_count', 'desc')
            ->get();
          
        return [
            'allReferrers'=>$referrers,
            'is_riel'=>Branch::find(Auth::user()->branch_id)->is_riel,
            'can'=>[
                'make_payment'=>Auth::user()->can('create',CommissionPayment::class),
            ],
            'allCustomBranches'=>count(Auth::user()->branches)>0?BranchResource::collection(Auth::user()->branches):null,
        ];
       
        
    }
    public function allUserReferrals($user_id){
        
        $this->authorize('create',CommissionPayment::class);

        return new ReferralCollection(User::find($user_id)->referralsInMyBranchApprovedNotYetPaid);
    }

}
