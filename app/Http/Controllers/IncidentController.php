<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Incident;
use App\Models\IncidentLog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Notifications\IncidentStatusUpdated;
use Illuminate\Support\Facades\Log;

class IncidentController extends Controller
{
   public function incidentAjax(Request $request)
    {
        $query = Incident::where('created_by', Auth::id());

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('category', fn($row) => optional($row->category)->name)
            ->editColumn('priority', function ($row) {
                return match ($row->priority) {
                    0 => 'High',
                    1 => 'Medium',
                    2 => 'Low',
                    default => 'N/A',
                };
            })
            ->rawColumns(['file'])
            ->make(true);
    }

    public function index(Request $request)
    {
        $categories = Category::all();
        return view('user.Incident.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        $categories = Category::all();
        return view('user.incident.create', compact('categories'));    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required',
            'date' => 'required|date',
            'file' => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $incident = new Incident();
        $incident->title = $request->title;
        $incident->description = $request->description;
        $incident->category_id = $request->category_id;
        $incident->priority = $request->priority;
        $incident->date = $request->date;
        $incident->created_by = Auth::id();

          if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('evidence', $filename, 'public');
            $incident->file = $path;
        }
      
        $incident->save();

        // $incident->user->notify(new IncidentStatusUpdated($incident));
            $user = Auth::user();
        if ($user) {
            Log::info('Attempting to send notification to user ID: ' . $user->id);
            $user->notify(new IncidentStatusUpdated($incident));
            Log::info('Notification successfully sent to user ID: ' . $user->id);
        } else {
            Log::warning('Failed to send notification: No authenticated user.');
        }

        return redirect()->route('incidents.index')->with('success', 'Incident request submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Incident $incident)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Incident $incident)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Incident $incident)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Incident $incident)
    {
        
    }
}
