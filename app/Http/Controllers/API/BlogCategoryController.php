<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = BlogCategory::get();

        return response()->json([
            'status' => 'success',
            'count' => count($categories),
            'data' => $categories,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data['name'] = $request->name;
        $data['slug'] = Str::slug($request->name);

        $category = BlogCategory::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully',
            'data' => $category,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data['name'] = $request->name;
        $data['slug'] = Str::slug($request->name);

        $category = BlogCategory::find($id);

        if ($category) {
            $category->name = $request->name;
            $category->slug = Str::slug($request->name);
            $category->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Category updated successfully',
                'data' => $category,
            ], 201);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found',
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = BlogCategory::find($id);

        if ($category) {
            BlogCategory::destroy($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Category deleted successfully',
            ], 201);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found',
            ], 404);
        }
    }
}
