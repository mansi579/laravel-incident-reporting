<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Incident;
use App\Models\IncidentLog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function requestAjax(Request $request)
    {
        $query = Incident::query()->with('category');
        // dd($query);
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('category', fn($row) => optional($row->category)->name)
             ->editColumn('priority', function ($row) {
                    switch ($row->priority) {
                        case 0: return 'High';
                        case 1: return 'Medium';
                        case 2: return 'Low';
                        default: return 'N/A';
                    }
                })
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" class="btn text-danger delete" data-id="' . $row->id . '" target-url="' . route('requests.destroy', $row->id) . '"><i class="fa fa-trash"></i></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('super-admin.incident.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $incident = Incident::findOrFail($id);    
        $incident->delete();
        return response()->json(['status' => true, 'message' => 'Incident deleted successfully.']);
    }
}
