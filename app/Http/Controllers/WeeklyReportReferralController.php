<?php

namespace App\Http\Controllers;

use App\Exports\ExportallReferral;
use App\Exports\ExportUser;
use App\Exports\ExportWeekly;
use App\Models\Role;

use App\Models\User;
use App\Models\Referral;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\PaymentStatus;
use App\Mail\NotificationMail;
use App\Models\ParentReferral;
use App\Models\ReferralProcess;
use Illuminate\Support\Facades\DB;
 use App\Models\WeeklyReportReferral;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\ReferralResource;
use App\Http\Resources\WeeklyReportReferralResource;
use App\Http\Resources\WeeklyReportReferralCollection;
use Maatwebsite\Excel\Excel as ExcelExcel;

class WeeklyReportReferralController extends Controller
{
    public function __construct()
    {

        $this->authorizeResource(WeeklyReportReferral::class, 'weeklyReportReferral');
    }
    public function index()
    {
        return new WeeklyReportReferralCollection(WeeklyReportReferral::with('referrals')
            ->whereHas('referrals', function ($query) {
                $query->where('branch_id', Auth::user()->branch_id)
                    ->orWhereIn('branch_id', Auth::user()->branches->pluck('id'));
            })
            ->orderBy('id', 'desc')->get());
    }

    public function create()
    {
        return [
            'enrolled_students' => ReferralResource::collection(Referral::where('referral_status_id', 4)->whereNull('weekly_report_referral_id')->where('branch_id', Auth::user()->branch_id)->get()),
        ];
    }
    public function store(Request $request)
    {
        if (count($request->selected) > 0) {
            $weeklyReportReferral = WeeklyReportReferral::create([
                'branch_id' => Auth::user()->branch_id,
                'created_by' => Auth::id(),
                'prepared_by' => Auth::id(),
                'prepared_at' => now(),
                'from' => $request->from,
                'to' => $request->to,
            ]);
            foreach ($request->selected as $ref) {
                Referral::find($ref['id'])->update([
                    'weekly_report_referral_id' => $weeklyReportReferral->id,
                    'weekly_report_comment' => $ref['comment']
                ]);
            }
            //notification and mail
            $role_id = Role::where('name', 'Head Cashier')->first()->id;
            $user = User::with('roles')

                ->where(function ($query) { //in my branch only
                    $query->where('branch_id', Auth::user()->branch_id)
                        ->orWhereIn('branch_id', Auth::user()->branches->pluck('id'));
                })
                ->whereHas('roles', function ($query) use ($role_id) { //all referrals
                    $query->where('roles.id', $role_id);
                })
                ->get();
            foreach ($user as $u) {
                Notification::create([
                    'from_user_id' => Auth::user()->id,
                    'user_id' => $u->id,
                    'role_id' => $role_id,
                    'title' => 'Weekly Report',
                    'message' => 'Create Weekly Report Successfully',
                    'link' => '/weeklyReportReferrals/' . $weeklyReportReferral->id,
                ]);
                $object = [
                    'topic' => 'Weekly Report',
                    'message' => Auth::user()->name . ' has create Weekly Report successfully. ' . $u->name . ' please help check. ',
                    'link' => env('APP_URL') . '/referrals/' . $weeklyReportReferral->id,
                ];
                Mail::to($u->email)->send(new NotificationMail($object));
            }
            return $weeklyReportReferral->id;
        }
    }
    public function check($weekly_report_referral_id, Request $request)
    {
        $weeklyReportReferral = WeeklyReportReferral::find($weekly_report_referral_id);
        $weeklyReportReferral->update([
            'checked_by' => Auth::id(),
            'checked_at' => now(),
            'checked_remark' => $request->checked_remark,
        ]);
        //notification and mail
        $role_id = Role::where('name', 'School Principal')->first()->id;
        $user = User::with('roles')

            ->where(function ($query) { //in my branch only
                $query->where('branch_id', Auth::user()->branch_id)
                    ->orWhereIn('branch_id', Auth::user()->branches->pluck('id'));
            })
            ->whereHas('roles', function ($query) use ($role_id) { //all referrals
                $query->where('roles.id', $role_id);
            })
            ->get();
        foreach ($user as $u) {
            Notification::create([
                'from_user_id' => Auth::user()->id,
                'user_id' => $u->id,
                'role_id' => $role_id,
                'title' => 'Weekly Report',
                'message' => 'I have checked this Weekly Report. Please Approve',
                'link' => '/weeklyReportReferrals/' . $weeklyReportReferral->id,
            ]);
            $object = [
                'topic' => 'Weekly Report',
                'message' => Auth::user()->name . ' checked this Weekly Report. ' . $u->name . ' please help approve. ',
                'link' => env('APP_URL') . '/weeklyReportReferrals/' . $weeklyReportReferral->id,
            ];
            Mail::to($u->email)->send(new NotificationMail($object));
        }
        return new WeeklyReportReferralResource($weeklyReportReferral);
    }
    public function approve($weekly_report_referral_id, Request $request)
    {
        $weeklyReportReferral = WeeklyReportReferral::find($weekly_report_referral_id);
        $weeklyReportReferral->update([
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'approved_remark' => $request->approved_remark,
        ]);
        //notification and mail
        $role_id = Role::where('name', 'Head Cashier')->first()->id;
        $user = User::with('roles')

            ->where(function ($query) { //in my branch only
                $query->where('branch_id', Auth::user()->branch_id)
                    ->orWhereIn('branch_id', Auth::user()->branches->pluck('id'));
            })
            ->whereHas('roles', function ($query) use ($role_id) { //all referrals
                $query->where('roles.id', $role_id);
            })
            ->get();
        foreach ($user as $u) {
            Notification::create([
                'from_user_id' => Auth::user()->id,
                'user_id' => $u->id,
                'role_id' => $role_id,
                'title' => 'Weekly Report',
                'message' => 'I have approved this Weekly Report. Please make payment to referrers.',
                'link' => '/weeklyReportReferrals/' . $weeklyReportReferral->id,
            ]);
            $object = [
                'topic' => 'Weekly Report',
                'message' => Auth::user()->name . ' has approved this Weekly Report. ' . $u->name . ' please help make payment to referrers. ',
                'link' => env('APP_URL') . '/weeklyReportReferrals/' . $weeklyReportReferral->id,
            ];
            Mail::to($u->email)->send(new NotificationMail($object));
        }
        return new WeeklyReportReferralResource($weeklyReportReferral);
    }

    public function show(WeeklyReportReferral $weeklyReportReferral)
    {
        // $referrers=$weeklyReportReferral->approved_by!=null?User::with('referrals','referralsInMyBranchApprovedNotYetPaid','branch')
        //         ->whereHas('referrals', function ($query) use($weeklyReportReferral) {//all referrals
        //             $query->where('weekly_report_referral_id',$weeklyReportReferral->id);
        //             $query->where('branch_id',Auth::user()->branch_id)
        //             ->orWhereIn('branch_id',Auth::user()->branches->pluck('id'));
        //         })
        //         ->orWhereHas('referralsInMyBranchApprovedNotYetPaid', function ($query) use($weeklyReportReferral) {//all referrals
        //             $query->where('weekly_report_referral_id',$weeklyReportReferral->id);
        //         })
        //         ->get():null;


        $referrers = $weeklyReportReferral->approved_by != null ? User::with(
            [
                'referrals' => function ($query) use ($weeklyReportReferral) {
                    $query->where('weekly_report_referral_id', $weeklyReportReferral->id);
                },
                'referralsInMyBranchApprovedNotYetPaid' => function ($query) use ($weeklyReportReferral) {
                    $query->where('weekly_report_referral_id', $weeklyReportReferral->id);
                },
                'branch'
            ],

        )
            ->whereHas('referrals', function ($query) use ($weeklyReportReferral) { //all referrals
                $query->where('weekly_report_referral_id', $weeklyReportReferral->id);
            })
            ->orWhereHas('referralsInMyBranchApprovedNotYetPaid', function ($query) use ($weeklyReportReferral) { //all referrals
                $query->where('weekly_report_referral_id', $weeklyReportReferral->id);
            })
            ->get() : null;
        return [
            'weeklyReportReferral' => new WeeklyReportReferralResource($weeklyReportReferral),
            'can' => [
                'check' => Auth::user()->can('check', $weeklyReportReferral),
                'approve' => Auth::user()->can('approve', $weeklyReportReferral),
                'pay' => Auth::user()->can('pay', $weeklyReportReferral),
            ],
            'referrers' => $referrers,
            'paymentStatuses' => PaymentStatus::get(),
        ];
    }

    public function update(Request $request, WeeklyReportReferral $weeklyReportReferral)
    {
        //
    }

    public function destroy(WeeklyReportReferral $weeklyReportReferral)
    {
       
       // dd($weeklyReportReferral);
    }
    public function removeReferral($id,Request $request)
    {

       $referrers = Referral::find($id);
       $parent_referral=ParentReferral::find($referrers->parent_referral_id);
       ReferralProcess::create([
           'created_by'=>Auth::id(),
           'remark'=>$request->reason,
           'is_contact'=>true,
           'parent_referral_id'=>$referrers->parent_referral_id
       ]);
       $referrers->update([
        'weekly_report_referral_id' => null,
       ]);
       return [
        'message'   => "Weekly report referral hase been removed",
        'snackColor'=> "success",
    ];
    }
    public function exportWeeklyreport(Request $request)
    {
       return Excel::download( new ExportWeekly , 'Weeklyreport.xlsx');
      
    }
    public function exportReferral(Request $request)
    {
        return Excel::download( new ExportallReferral , 'allreferral.xlsx');
    }
    
}
