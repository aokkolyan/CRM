<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\StageCollection;

class StageController extends Controller
{
    public function __construct()
    {
        
        $this->authorizeResource(Stage::class, 'stage');
    }

    public function index()
    {
        return new StageCollection(Stage::orderBy('id')->get());
    }

    public function store(Request $request)
    {
        
        $validated=$request->validate([
            'name_en' => ['required'],
            'name_kh' => ['required'],
            'color' => ['required'],
        ]);
        $stage=Stage::create([
            'name_en' => $request->name_en,
            'name_kh' => $request->name_kh,
            'code' => $request->code,
            'color' => $request->color,
            'description'=>$request->description,
            'status_id' => $request->status_id,
            'created_by'=>Auth::user()->id,
        ]);
        return [
            'message'=> 'Create Stage successfully ',
            'snackColor'=> 'success',
        ];
    }


    public function update(Request $request, Stage $stage)
    {
        $validated=$request->validate([
            'name_en' => ['required'],
            'name_kh' => ['required'],
        ]);
        $stage->update([
            'name_en' => $request->name_en,
            'name_kh' => $request->name_kh,
            'code' => $request->code,
            'color' => $request->color,
            'description'=>$request->description,
            'status_id' => $request->status_id,
        ]);
        return [
            'message'=> 'Update Stage successfully ',
            'snackColor'=> 'success',
        ];
    }

    public function destroy(Stage $stage)
    {
        if($stage->leads){
            return [
                'message'=> 'Update Stage error, there are branches exist in this Stage ',
                'snackColor'=> 'error',
            ];
        }
        $stage->update([
            'deleted_by' => Auth::user()->id,
        ]);
        $stage->delete();
        return [
            'message'=> 'Delete Stage successfully ',
            'snackColor'=> 'success',
        ];
    }
}
