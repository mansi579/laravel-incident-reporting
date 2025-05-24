<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function categoryAjax(Request $request)
    {
        return DataTables::of(Category::query())
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="' . route('categories.edit', $row->id) . '" class="btn text-primary">';
                $btn .= '<i class="fa fa-edit"></i></a>';

                $btn .= '<a href="javascript:void(0)" class="btn text-danger delete" data-id="' . $row->id . '" target-url="' . route('categories.destroy', $row->id) . '">';
                $btn .= '<i class="fa fa-trash"></i></a>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->get();
        return view('super-admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('super-admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $data = $category;
        return view('super-admin.category.create', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
