<?php

namespace App\Http\Controllers;

use App\Models\CommissionPayment;
use App\Models\Referral;
use App\Models\Module;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CommissionPaymentResource;
class CommissionPaymentController extends Controller
{
    public function index() : Object 
    {
        return CommissionPaymentResource::collection(CommissionPayment::get());
    }
    public function store(Request $request)
    {
        $this->authorize('create',CommissionPayment::class);
        if(count(json_decode($request->input('allUserReferrals')))>0){
           
            $data=json_decode($request->input('allUserReferrals'));
            $referrer_id=$data[0]->creator_id;
            $total=array_reduce($data, function ($accumulator, $currentNumber) {
                return $accumulator + $currentNumber->commission;
            }, 0);
            $commissionPayment=CommissionPayment::create([
                'for_user'=>$referrer_id,
                'paid_by'=>Auth::id(),
                'paid_at'=>now(),
                'total_payment'=>array_reduce($data, function ($accumulator, $currentNumber) {
                    return $accumulator + $currentNumber->commission;
                }, 0)
            ]);
            //file
            $file=$request->file('file');
            if($file){
                $module=Module::where('name','CommissionPayment')->first();
                $name=$file->getClientOriginalName();
                $stored=$file->store('ReferralAttachment','public');
                Attachment::create([
                    'module_id'=>$module->id??0,
                    'ref_id'=>$commissionPayment->id,    
                    'path'=>$stored,
                    'name'=>$name,
    
                ]);
            }
           
            foreach($data as $ref){
                Referral::find($ref->id)->update([
                    'commission_payment_id'=>$commissionPayment->id,
                    'payment_status_id'=>2,
                    'paid_at'=>now(),
                ]);
            }
        }
    }


}
