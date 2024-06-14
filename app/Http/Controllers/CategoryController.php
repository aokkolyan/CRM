<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryCollection;

class CategoryController extends Controller
{
    public function __construct()
    {
        
        $this->authorizeResource(Category::class, 'Category');
    }

    public function index()
    {
        return new CategoryCollection(Category::orderBy('id')->get());
    }

    public function store(Request $request)
    {
        
        $validated=$request->validate([
            'name_en' => ['required'],
            'name_kh' => ['required'],
        ]);
        $category=Category::create([
            'name_en' => $request->name_en,
            'name_kh' => $request->name_kh,
            'code' => $request->code,
            'description'=>$request->description,
            'created_by'=>Auth::user()->id,
        ]);
        return [
            'message'=> 'Create Category successfully ',
            'snackColor'=> 'success',
        ];
    }


    public function update(Request $request, Category $category)
    {
        $validated=$request->validate([
            'name_en' => ['required'],
            'name_kh' => ['required'],
        ]);
        $category->update([
            'name_en' => $request->name_en,
            'name_kh' => $request->name_kh,
            'code' => $request->code,
            'description'=>$request->description,
        ]);
        return [
            'message'=> 'Update Category successfully ',
            'snackColor'=> 'success',
        ];
    }

    public function destroy(Category $category)
    {
        if($category->products){
            return [
                'message'=> 'Update Category error, there are products exist in this Category ',
                'snackColor'=> 'error',
            ];
        }
        $category->update([
            'deleted_by' => Auth::user()->id,
        ]);
        $category->delete();
        return [
            'message'=> 'Delete Category successfully ',
            'snackColor'=> 'success',
        ];
    }
}
