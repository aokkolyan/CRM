<?php

namespace App\Http\Controllers;


use App\Models\ParentReferral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ParentReferralCollection;

class ParentReferralController extends Controller
{
    public function update(Request $request, ParentReferral $parentReferral)
    {

        $validated=$request->validate([
            'parent_name' => ['required', 'max:100'],
            'phone'=>['required'],
        ]);
        $data=$request->all();
        $data['updated_by']=Auth::id();
        $parentReferral->update($data);
        return $parentReferral;
    }
    
}
