<?php

namespace App\Http\Controllers;

use App\Models\Source;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\SourceCollection;

class SourceController extends Controller
{
    public function __construct()
    {
        
        $this->authorizeResource(Source::class, 'source');
    }

    public function index()
    {
        return new SourceCollection(Source::orderBy('id')->get());
    }

    public function store(Request $request)
    {
        
        $validated=$request->validate([
            'name_en' => ['required'],
            'name_kh' => ['required'],
            'color' => ['required'],
        ]);
        $source=Source::create([
            'name_en' => $request->name_en,
            'name_kh' => $request->name_kh,
            'code' => $request->code,
            'color' => $request->color,
            'description'=>$request->description,
            'created_by'=>Auth::user()->id,
        ]);
        return [
            'message'=> 'Create source successfully ',
            'snackColor'=> 'success',
        ];
    }


    public function update(Request $request, Source $source)
    {
        $validated=$request->validate([
            'name_en' => ['required'],
            'name_kh' => ['required'],
        ]);
        $source->update([
            'name_en' => $request->name_en,
            'name_kh' => $request->name_kh,
            'code' => $request->code,
            'color' => $request->color,
            'description'=>$request->description,
        ]);
        return [
            'message'=> 'Update source successfully ',
            'snackColor'=> 'success',
        ];
    }

    public function destroy(Source $source)
    {
        if($source->leads){
            return [
                'message'=> 'Update source error, there are branches exist in this source ',
                'snackColor'=> 'error',
            ];
        }
        $source->update([
            'deleted_by' => Auth::user()->id,
        ]);
        $source->delete();
        return [
            'message'=> 'Delete source successfully ',
            'snackColor'=> 'success',
        ];
    }
}
