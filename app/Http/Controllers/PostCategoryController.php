<?php
namespace App\Http\Controllers;

use App\Models\PostCategory;
use Illuminate\Http\Request;

class PostCategoryController extends Controller
{
    public function index()
    {
        return response()->json(PostCategory::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:post_categories']);
        $category = PostCategory::create($request->all());
        return response()->json($category, 201);
    }

    public function show(PostCategory $postCategory)
    {
        return response()->json($postCategory, 200);
    }
    
    public function update(Request $request, $id)
    {
        $postCategory = PostCategory::find($id);
    
        if (!$postCategory) {
            return response()->json(['message' => 'Category not found'], 404);
        }
    
        logger()->info('Data dari Postman: ' . json_encode($postCategory));
    
        $request->validate([
            'name' => 'required|unique:post_categories,name,' . $id,
        ]);
    
        $postCategory->update($request->all());
    
        return response()->json($postCategory, 200);
    }
    

    public function destroy($id)
    {
        $postCategory = PostCategory::find($id);
    
        if (!$postCategory) {
            return response()->json(['message' => 'PostCategory not found'], 404);
        }
    
        logger()->info('Menerima request untuk menghapus Post categories ID: ' . json_encode($postCategory));
    
        $postCategory->posts()->delete();
    
        $postCategory->delete();
    
        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
    
}

