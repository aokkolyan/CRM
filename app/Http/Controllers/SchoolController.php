<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Permission;
use App\Models\Module;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\SchoolCollection;
use App\Models\SchoolUserTypeCommissionType;

class SchoolController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(School::class, 'school');
    }

    public function index()
    {
        return new SchoolCollection(School::orderBy('id')->get());
    }

    public function store(Request $request)
    {
      
        $validated=$request->validate([
            'name_en' => ['required'],
            'name_kh' => ['required'],
            'code' => ['required'],
        ]);
        $school=School::create([
            'name_en' => $request->name_en,
            'name_kh' => $request->name_kh,
            'code' => $request->code,
            'description'=>$request->description,
            'is_riel'=>$request->is_riel,
            'created_by'=>Auth::user()->id,
        ]);
        return [
            'message'=> 'Create School successfully ',
            'school' => $school,
            'snackColor'=> 'success',
        ];
    }

    public function update(Request $request, School $school)
    {
        // dd($request->all());
        $validated=$request->validate([
            'name_en' => ['required'],
            'name_kh' => ['required'],
            'code' => ['required'],
        ]);
        $school->update([
            'name_en' => $request->name_en,
            'name_kh' => $request->name_kh,
            'code' => $request->code,
            'description'=>$request->description,
            'point_multiplyier'=>$request->point_multiplyier,
            'coupon_percentage'=>$request->coupon_percentage,
            'is_riel'=>$request->is_riel,
        ]);
        return [
            'message'=> 'Update School successfully ',
            'snackColor'=> 'success',
        ];
    }

    public function destroy(School $school)
    {
        if($school->branches){
            return [
                'message'=> 'Update School error, there are branches exist in this school ',
                'snackColor'=> 'error',
            ];
        }
        $school->update([
            'deleted_by' => Auth::user()->id,
        ]);
        $school->delete();
        return [
            'message'=> 'Delete School successfully ',
            'snackColor'=> 'success',
        ];
    }

    public function commissionConfig($school_id,Request $request){

        foreach($request->all() as $ut){
            $st=SchoolUserTypeCommissionType::where('school_id',$school_id)->where('user_type_id',$ut['user_type_id'])->first();
            if($st){
                $st->update([
                    'commission_type'=>$ut['value']
                ]);
            }else{
               
                SchoolUserTypeCommissionType::create([
                    'school_id'=>$school_id,
                    'user_type_id'=>$ut['user_type_id'],
                    'commission_type'=>$ut['value']??'cash'
                ]);
            }
        }
    }
}
