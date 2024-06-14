<?php

namespace App\Http\Controllers;

use App\Models\CompetitorProgram;
use App\Models\MapLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CompetitorProgramController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        //    dd($request->all());
         $competitorProgram=CompetitorProgram::create([
            'promotion'=>$request->promotion,
            'program_price' => $request->program_price,
            'program_student' => $request->program_student,
            'program_id' => $request->program_id,
            'map_location_id' => $request->map_location_id,
            'created_by'=>Auth::user()->id,
        ]);
        return [
            'message'=> 'Create Program successfully ',
            'snackColor'=> 'success',
        ];
    }

  
    public function show(CompetitorProgram $competitorProgram)
    {
        //
    }

    
    public function update(Request $request, CompetitorProgram $competitorProgram)
    {
       //dd($request->all());
        // $validated=$request->validate([
        //     'program_price' => ['required'],
        //     'program_student' => ['required'],
        // ]);
        $competitorProgram->update([
            'program_id'=> $request->program_id,
            'map_location_id'=>$request->map_location_id,
            'program_price' => $request->program_price,
            'program_student' => $request->program_student,
            'promotion' => $request->promotion,
            
        ]);
       // dd($competitorProgram);
       
        return [
            'message'=> 'Update Program successfully ',
            'snackColor'=> 'success',
        ];
    }
    public function destroy(CompetitorProgram $competitorProgram)
    {
    //    dd($competitorProgram);
        $competitorProgram->update([
            'deleted_by' => Auth::user()->id,
        ]);
        $competitorProgram->delete();
        return [
            'message'=> 'Delete Program successfully ',
            'snackColor'=> 'success',
        ];
    
    }
}
