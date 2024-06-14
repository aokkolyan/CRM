<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\ProductCollection;

class ProductController extends Controller
{
    public function __construct()
    {
        
        $this->authorizeResource(Product::class, 'product');
    }

    public function index()
    {
        return new ProductCollection(Product::orderBy('id')->get());
    }

    public function store(Request $request)
    {
        
        $validated=$request->validate([
            'name_en' => ['required'],
            'name_kh' => ['required'],
        ]);
        $product=Product::create([
            'name_en' => $request->name_en,
            'name_kh' => $request->name_kh,
            'code' => $request->code,
            'description'=>$request->description,
            'category_id' => $request->category_id,
            'created_by'=>Auth::user()->id,
        ]);
        return [
            'message'=> 'Create Product successfully ',
            'snackColor'=> 'success',
        ];
    }


    public function update(Request $request, Product $product)
    {
        $validated=$request->validate([
            'name_en' => ['required'],
            'name_kh' => ['required'],
        ]);
        $product->update([
            'name_en' => $request->name_en,
            'name_kh' => $request->name_kh,
            'code' => $request->code,
            'description'=>$request->description,
            'category_id' => $request->category_id,
        ]);
        return [
            'message'=> 'Update Product successfully ',
            'snackColor'=> 'success',
        ];
    }

    public function destroy(Product $product)
    {
       
        $product->update([
            'deleted_by' => Auth::user()->id,
        ]);
        $product->delete();
        return [
            'message'=> 'Delete Product successfully ',
            'snackColor'=> 'success',
        ];
    }
}
