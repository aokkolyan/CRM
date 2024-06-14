<?php

namespace App\Http\Controllers;

use App\Models\PaymentStatus;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\PaymentStatusCollection;

class PaymentStatusController extends Controller
{
    public function __construct()
    {
        
        $this->authorizeResource(PaymentStatus::class, 'paymentStatus');
    }

    public function index()
    {
        return new PaymentStatusCollection(PaymentStatus::orderBy('id')->get());
    }

    public function store(Request $request)
    {
        
        $validated=$request->validate([
            'name_en' => ['required'],
            'name_kh' => ['required'],
            'color' => ['required'],
        ]);
        $paymentStatus=PaymentStatus::create([
            'name_en' => $request->name_en,
            'name_kh' => $request->name_kh,
            'code' => $request->code,
            'color' => $request->color,
            'description'=>$request->description,
            'created_by'=>Auth::user()->id,
        ]);
        return [
            'message'=> 'Create PaymentStatus successfully ',
            'snackColor'=> 'success',
        ];
    }


    public function update(Request $request, PaymentStatus $paymentStatus)
    {
        $validated=$request->validate([
            'name_en' => ['required'],
            'name_kh' => ['required'],
        ]);
        $paymentStatus->update([
            'name_en' => $request->name_en,
            'name_kh' => $request->name_kh,
            'code' => $request->code,
            'color' => $request->color,
            'description'=>$request->description,
        ]);
        return [
            'message'=> 'Update PaymentStatus successfully ',
            'snackColor'=> 'success',
        ];
    }

    public function destroy(PaymentStatus $paymentStatus)
    {
        if($paymentStatus->leads){
            return [
                'message'=> 'Update PaymentStatus error, there are branches exist in this PaymentStatus ',
                'snackColor'=> 'error',
            ];
        }
        $paymentStatus->update([
            'deleted_by' => Auth::user()->id,
        ]);
        $paymentStatus->delete();
        return [
            'message'=> 'Delete PaymentStatus successfully ',
            'snackColor'=> 'success',
        ];
    }
}
