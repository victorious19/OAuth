<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    function getAll() {
        return Category::orderBy('created_at', 'DESC')->paginate(10);
    }
    function get() {
        return Category::orderBy('created_at', 'DESC')->get();
    }
    function create(Request $request) {
        $request->validate(['name' => 'required|string|unique:categories,name']);
        return Category::create(['name' => $request->name]);
    }
    function delete($id) {
        $category = Category::find($id);
        if (isset($category)) return Category::destroy($id);
        else return response(['errors'=>['name'=>["Category not found"]]],404);
    }
}
