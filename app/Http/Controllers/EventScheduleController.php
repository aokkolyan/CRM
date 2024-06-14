<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Referral;
use App\Models\Role;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\NotificationMail;
use Carbon\Carbon;

class EventScheduleController extends Controller
{
    public function lateReferrlAlert() : void {
        $targetStartDate = Carbon::now()->subDays(4)->startOfDay();
        $targetEndDate = Carbon::now()->subDays(2)->endOfDay();

        $LateReferrals = Referral::where('referral_status_id', 1)
                    ->whereBetween('created_at', [$targetStartDate, $targetEndDate])
                    ->get();
        // dd($LateReferrals);
                        //notification and mail
        $role_id=Role::where('name','Customer Service')->first()->id;
     

        foreach($LateReferrals as $lr){

            $user=User::with('roles')
            ->where(function($q) use($lr){
                $q->where('branch_id',$lr['branch_id'])
                ->orWhereHas('branches', function ($query) use($lr)  {
                    $query->where('branches.id',$lr['branch_id']);
                });
            })
            ->whereHas('roles', function ($query) use($role_id) {//all referrals
                $query->where('roles.id',$role_id);
            })
            ->get();
    
            foreach($user as $u){
                Notification::create([
                    'from_user_id'=>1,
                    'user_id'=>$u->id,
                    'role_id'=>$role_id,
                    'title'=>'Late Alert',
                    'message'=>'This referral has no update for 3 days already. Please check.',
                    'link'=>'/referrals/'.$lr->id,
                ]);
                $object=[
                    'topic'=>'Weekly Report',
                    'message'=>'This referral has no update for 3 days already. Please check',
                    'link'=>env('APP_URL').'/referrals/'.$lr->id,
                ];
                Mail::to($u->email)->send(new NotificationMail($object));
            }
        }
    }
}
