<?php

namespace App\Http\Controllers;

use App\Models\MapLocation;
use App\Models\Attachment;
use App\Models\Module;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\MapLocationCollection;
use Image;

class MapLocationController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(MapLocation::class, 'mapLocation');
    }
    public function index()
    {
        return new MapLocationCollection(MapLocation::orderBy('id')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated=$request->validate([
            'name' => ['required', 'max:30'],
            'lat'=>['required'],
            'long'=>['required'],
        ]);
        $data=$request->all();
        $data['icon']=$data['icon_id'];
        MapLocation::create($data);
      
    }
    public function uploadIcon(Request $request)
    {
        $validated=$request->validate([
            'file' => ['required'],
        ]);
        $file=$request->file('file');
        if($file){
            // Resize the image to the desired size (e.g., 200x200 pixels)
            $resizedImage = Image::make($file)->fit(36, 36);
            // Generate a unique filename for the resized image
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            // Generate a unique filename for the resized image

            $name=$file->getClientOriginalName();
             // Save the resized image to the storage/app/public directory
            $resizedImage->save(public_path('storage/' . $filename));
            // $stored=$resizedImage->store('MapIcons','public');
            $module=Module::where('name','MapLocation')->first();
            Attachment::create([
                'module_id'=>$module->id??17,
                'ref_id'=>0,
                'path'=>$filename,
                'name'=>$name,
            ]);
        }
        return[
            'icons'=>Attachment::where('module_id',$module->id??17)->get()
        ];
      
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MapLocation  $mapLocation
     * @return \Illuminate\Http\Response
     */
    public function show(MapLocation $mapLocation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MapLocation  $mapLocation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MapLocation $mapLocation)
    {
     
        $validated=$request->validate([
            'name' => ['required', 'max:30'],
            'lat'=>['required'],
            'long'=>['required'],
        ]);

        $mapLocation->update([
            'name'=> $request->name,
            'icon'=> $request->icon_id,
            'branch_id'=> $request->branch_id,
            'lat'=> $request->lat,
            'long'=> $request->long,
            'name_branch_manager'=> $request->name_branch_manager,
            'cultural_level'=> $request->cultural_level,
            'price_program'=> $request->price_program,
            'specail_program'=>$request->specail_program,
            'address'=> $request->address,
            'qaunity_staff'=>$request->qaunity_staff,
            'qaunity_student'=>$request->qaunity_student,
            'social_media'=>$request->social_media,
            'other'=>$request->other,
            'is_competitor'=>$request->is_competitor,
        ]);
      
     
        return [
            'message'   => "Maplocation hase been Updated",
            'snackColor'=> "success",
        ];
        
        // MapLocation::create($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MapLocation  $mapLocation
     * @return \Illuminate\Http\Response
     */
    public function destroy(MapLocation $mapLocation)
    {
        $mapLocation->update([
        'deleted_by' => Auth::user()->id,
        ]);
        $mapLocation->delete();
        return [
            'message'   => "Maplocation hase been deleted",
            'snackColor'=> "success",
        ];
    }

}
