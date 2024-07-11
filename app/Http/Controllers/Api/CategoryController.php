<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::when(
            request()->search,
            function ($categories) {
                $categories = $categories->where('name', 'like', '%'
                    . request()->search . '%');
            }
        )->latest()->paginate(5);

        $categories->appends(['search' => request()->search]);


        return new CategoryResource(true, 'List Data Categories', $categories);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $categories = new Category();
        $categories->name = $request->name;
        $categories->slug = Str::slug($request->name);
        $categories->save();

        if ($categories) {
            return new CategoryResource(true, 'Category created', $categories);
        } else {
            return new CategoryResource(false, 'Category failed created', null);
        }
    }



    public function show(Category $category)
    {
        $categories =  Category::find($category);

        if ($categories) {
            return new CategoryResource(true, 'Catgory detail', $categories);
        }
    }


    public function update(Request $request, Category $categories)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $categories = Category::find($request->id);
        $categories->name = $request->name;
        $categories->slug = Str::slug($request->name);
        $categories->save();

        if ($categories) {
            return new CategoryResource(true, 'Category updated', $categories);
        } else {
            return new CategoryResource(true, 'Category failed update', null);
        }
    }


    public function destroy(Category $categories)
    {
        $categories->delete();

        if ($categories) {
            return new CategoryResource(true, 'Category deleted', null);
        }
    }
}
