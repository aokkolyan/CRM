<?php

namespace App\Http\Controllers;

use App\Models\ReferralStatus;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\ReferralStatusCollection;

class ReferralStatusController extends Controller
{
    public function __construct()
    {
        
        $this->authorizeResource(ReferralStatus::class, 'referralStatus');
    }

    public function index()
    {
        return new ReferralStatusCollection(ReferralStatus::orderBy('id')->get());
    }

    public function store(Request $request)
    {
        
        $validated=$request->validate([
            'name_en' => ['required'],
            'name_kh' => ['required'],
            'color' => ['required'],
        ]);
        $referralStatus=ReferralStatus::create([
            'name_en' => $request->name_en,
            'name_kh' => $request->name_kh,
            'code' => $request->code,
            'color' => $request->color,
            'description'=>$request->description,
            'created_by'=>Auth::user()->id,
        ]);
        return [
            'message'=> 'Create ReferralStatus successfully ',
            'snackColor'=> 'success',
        ];
    }


    public function update(Request $request, ReferralStatus $referralStatus)
    {
        $validated=$request->validate([
            'name_en' => ['required'],
            'name_kh' => ['required'],
        ]);
        $referralStatus->update([
            'name_en' => $request->name_en,
            'name_kh' => $request->name_kh,
            'code' => $request->code,
            'color' => $request->color,
            'description'=>$request->description,
        ]);
        return [
            'message'=> 'Update ReferralStatus successfully ',
            'snackColor'=> 'success',
        ];
    }

    public function destroy(ReferralStatus $referralStatus)
    {
        if($referralStatus->leads){
            return [
                'message'=> 'Update ReferralStatus error, there are branches exist in this ReferralStatus ',
                'snackColor'=> 'error',
            ];
        }
        $referralStatus->update([
            'deleted_by' => Auth::user()->id,
        ]);
        $referralStatus->delete();
        return [
            'message'=> 'Delete ReferralStatus successfully ',
            'snackColor'=> 'success',
        ];
    }
}
