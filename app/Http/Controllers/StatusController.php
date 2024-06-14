<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\StatusCollection;

class StatusController extends Controller
{
    public function __construct()
    {
        
        $this->authorizeResource(Status::class, 'status');
    }

    public function index()
    {
        return new StatusCollection(Status::orderBy('id')->get());
    }

    public function store(Request $request)
    {
        
        $validated=$request->validate([
            'name_en' => ['required'],
            'name_kh' => ['required'],
            'color' => ['required'],
        ]);
        $status=Status::create([
            'name_en' => $request->name_en,
            'name_kh' => $request->name_kh,
            'code' => $request->code,
            'color' => $request->color,
            'description'=>$request->description,
            'created_by'=>Auth::user()->id,
        ]);
        return [
            'message'=> 'Create Status successfully ',
            'snackColor'=> 'success',
        ];
    }


    public function update(Request $request, Status $status)
    {
        $validated=$request->validate([
            'name_en' => ['required'],
            'name_kh' => ['required'],
        ]);
        $status->update([
            'name_en' => $request->name_en,
            'name_kh' => $request->name_kh,
            'code' => $request->code,
            'color' => $request->color,
            'description'=>$request->description,
        ]);
        return [
            'message'=> 'Update Status successfully ',
            'snackColor'=> 'success',
        ];
    }

    public function destroy(Status $status)
    {
        if($status->leads){
            return [
                'message'=> 'Update Status error, there are branches exist in this Status ',
                'snackColor'=> 'error',
            ];
        }
        $status->update([
            'deleted_by' => Auth::user()->id,
        ]);
        $status->delete();
        return [
            'message'=> 'Delete Status successfully ',
            'snackColor'=> 'success',
        ];
    }
}
