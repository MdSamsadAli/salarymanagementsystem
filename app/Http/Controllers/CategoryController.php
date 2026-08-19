<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::whereNull('parent_id')->with('childrenRecursive')->get();
        return view('category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $maincategory = Category::whereNull('parent_id')->with('childrenRecursive')->get();
        return view('category.create', compact('maincategory'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $data = $request->all();
        Category::create($data);
        return redirect()->route('categories.index')->with('success', 'Category added Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // dd($id);
        $category = Category::findOrFail($id);
        $maincategory = Category::whereNull('parent_id')->with('childrenRecursive')->get();
        return view('category.create', compact('category', 'maincategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        // dd($id);

        $data = $request->validate([
            'category'  => 'required|string|max:255',
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                function ($attribute, $value, $fail) use ($category) {
                    if ($value == $category->id) {
                        $fail('A category cannot be its own parent.');
                    }
                    if ($value && in_array($value, $category->descendantIds())) {
                        $fail('A category cannot become a child of its own subcategory.');
                    }
                },
            ],
        ]);

        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'Category Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Category::find($id);
        $data->delete();
        return redirect()->route('categories.index')->with('success', 'Category Deleted Successfully');
    }
}
