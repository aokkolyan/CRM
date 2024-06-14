<?php

namespace App\Http\Controllers;

use App\Http\Resources\BranchCollection;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Response;
use App\Http\Resources\ErrorBadRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;
class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->authorizeResource(Branch::class, 'branch');
    }
    public function index()
    {
        return new BranchCollection(Branch::orderBy('id')->get());
    }

    public function store(Request $request)
    {
        $validated=$request->validate([
            'name_en' => ['required'],
            'name_kh' => ['required'],
            'code' => ['required'],
            'school_id' => ['required'],
        ]);
        $branch=Branch::create([
            'name_en' => $request->name_en,
            'name_kh' => $request->name_kh,
            'code' => $request->code,
            'commission' => $request->commission??5,
            'description'=>$request->description,
            'school_id'=>$request->school_id,
            'is_riel'=>School::find($request->school_id)->is_riel,
        ]);
        return [
            'message'=> 'Create Branch successfully ',
            'snackColor'=> 'success',
        ];
    }

    public function show(Branch $branch)
    {
        //
    }


    public function update(Request $request, Branch $branch)
    {
        $validated=$request->validate([
            'name_en' => ['required'],
            'name_kh' => ['required'],
            'code' => ['required'],
        ]);
        $branch->update([
            'name_en' => $request->name_en,
            'name_kh' => $request->name_kh,
            'code' => $request->code,
            'commission' => $request->commission??5,
            'max_discount' => $request->max_discount??15,
            'description'=>$request->description,
        ]);
        return [
            'message'=> 'Update School successfully ',
            'snackColor'=> 'success',
        ];
    }

    public function destroy(Branch $branch)
    {
        if($branch->branches){
            return [
                'message'=> 'Update School error, there are branches exist in this school ',
                'snackColor'=> 'error',
            ];
        }
        $branch->delete();
        return [
            'message'=> 'Delete School successfully ',
            'snackColor'=> 'success',
        ];
    }
}
