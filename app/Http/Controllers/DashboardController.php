<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Referral;
use App\Models\ReferralStatus;
use App\Models\Notification;
use App\Models\Lead;
use App\Models\Branch;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\BranchResource;

class DashboardController extends Controller
{
    public function index()
    {
        $referrers=User::withCount('referrals')->with('referrals')
        ->whereHas('referrals', function ($query) {
            $query->where('branch_id',Auth::user()->branch_id)
            ->orWhereIn('branch_id',Auth::user()->branches->pluck('id'));
        })
        ->orderBy('referrals_count', 'desc')
        ->get();
        $myReferral=Referral::where('created_by',Auth::id())->get();
        $allReferrals=Auth::user()->school_id==1?Referral::get():Referral::where('branch_id',Auth::user()->branch_id)->get();
        return [
            'can'=>
                [
                    'referral'=>Auth::user()->can('viewAny', Referral::class)||Auth::user()->can('viewAnyMyReferral', Referral::class),
                    'lead'=>Auth::user()->can('viewAny', Lead::class)
                ],
            'allReferrers'=>$referrers,
            'myReferrals'=>$myReferral,
            'allReferrals'=>$allReferrals,
            'branches'=>Auth::user()->school_id==1?BranchResource::collection(Branch::where('school_id','!=',1)->orderBy('school_id')->get()):null,
            'referralStatuses'=>ReferralStatus::get(),
        ];
    }
    public function notifications(){
        $notifications=Notification::take(25)
        ->where('user_id',Auth::user()->id)
        ->where('status',0)
        ->orderBy('id','desc')
        ->get()->map(function($item){
            return[
                'id'=>$item->id,
                'title'=>$item->title,
                'message'=>$item->message,
                'link'=>$item->link,
                'status'=>$item->status,
                'created_at'=>$item->created_at->shortAbsoluteDiffForHumans(),
                'img'=>'https://ui-avatars.com/api/?name='.User::find($item->from_user_id)->name."&color=7F9CF5&background=EBF4FF",
            ];
        });
        return $notifications;
    }
    public function visit(Request $request){
        
        Notification::find($request->id)->update(['status'=>1]);
    }
}
