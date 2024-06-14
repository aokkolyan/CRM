<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\ProgramCollection;

class ProgramController extends Controller
{
    // public function __construct()
    // {
    //     $this->authorizeResource(Program::class, 'Program');
    // }

    public function index()
    {
        return new ProgramCollection(Program::orderBy('id')->get());
    }

    public function store(Request $request)
    {
       
        $validated=$request->validate([
            'name_en' => ['required'],
            'name_kh' => ['required'],
        ]);
        $program=Program::create([
            'name_en' => $request->name_en,
            'name_kh' => $request->name_kh,
            'code' => $request->code,
            'created_by'=>Auth::user()->id,
        ]);
        return [
            'message'=> 'Create Program successfully ',
            'snackColor'=> 'success',
        ];
    }


    public function update(Request $request, Program $program)
    {
        $validated=$request->validate([
            'name_en' => ['required'],
            'name_kh' => ['required'],
        ]);
        $program->update([
            'name_en' => $request->name_en,
            'name_kh' => $request->name_kh,
            'code' => $request->code,
            
        ]);
        return [
            'message'=> 'Update Program successfully ',
            'snackColor'=> 'success',
        ];
    }

    public function destroy(Program $program)
    {
       
        $program->update([
            'deleted_by' => Auth::user()->id,
        ]);
        $program->delete();
        return [
            'message'=> 'Delete Program successfully ',
            'snackColor'=> 'success',
        ];
    }
}
