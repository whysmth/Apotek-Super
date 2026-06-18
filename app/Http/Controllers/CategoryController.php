<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function storeAjax(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'category' => $category
        ]);
    }
}
