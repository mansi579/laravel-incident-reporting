<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Incident;
use App\Models\IncidentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Notifications\IncidentStatusUpdated;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\User;

class adminIncidentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Incident::query()->with('category');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

       if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }


        $incidents = $query->get();
        $categories = Category::all();

        $totalIncidents = Incident::count();
        $statusCounts = Incident::select('status', DB::raw('count(*) as total'))
                            ->groupBy('status')->pluck('total', 'status')->toArray();

    $categoryCounts = Incident::select('category_id', DB::raw('count(*) as total'))
        ->with('category')
        ->groupBy('category_id')
        ->get()
        ->map(function ($item) {
            return [
                'category' => $item->category->name ?? 'Unknown',
                'total' => $item->total
            ];
        });

        $labels = $categoryCounts->pluck('category')->toArray();
        $data = $categoryCounts->pluck('total')->toArray();

        $averageMinutes = Incident::whereNotNull('started_at')
            ->whereNotNull('resolved_at')
            ->get()
            ->average(function ($incident) {
                return $incident->resolved_at->diffInMinutes($incident->started_at);
            });

        $averageFormatted = gmdate('H:i', $averageMinutes * 60);

        return view('AdminIncident.index', compact(
            'incidents',
            'categories',
            'totalIncidents',
            'statusCounts',
            'categoryCounts',
            'labels',
            'data',
            'averageFormatted',
        ));
    }

    public function bulkResolve(Request $request)
    {
        // dd("hello");
        $request->validate([
            'incident_ids' => 'required|array',
            'incident_ids.*' => 'exists:incidents,id',
        ]);

        Incident::whereIn('id', $request->incident_ids)->update([
            'status' => 2, // Resolved
            'resolved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Selected incidents marked as resolved.');
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
    public function show(Incident $incident)
    {
        $incident->load('category');
        return view('AdminIncident.show', compact('incident'));
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
   public function update(Request $request, Incident $incident)
    {
        $prevStatus = $incident->status;
        $incident->status = $request->status;

        if ($request->status == 1 && !$incident->started_at) {
            $incident->started_at = now();
        }

        if ($request->status == 2 && !$incident->resolved_at) {
            $incident->resolved_at = now();
        }

        $incident->save();
        // dd($incident->created_by);
        $user = User::find($incident->created_by);

         if ($user) {
             \Log::info('Sending notification to user id: ' . $incident->created_by);
            $user->notify(new IncidentStatusUpdated($incident));
            \Log::info('Notification sent.');
            // dd($incident->user);
            // \Log::info(message: 'Sending notification to user id: ' . $incident->created_by);
            // $incident->user->notify(new IncidentStatusUpdated($incident));
            // \Log::info('Notification sent.');
        } else {
            \Log::warning('Incident user not found.');
        }
        return redirect()->route('adminIncidents.index')->with('success', 'Incident updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

   public function export()
    {
        $fileName = 'incident.csv';
        $incidents = Incident::with('category')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID', 'Title', 'Description', 'Status', 'Category', 'Priority'];

        $callback = function () use ($incidents, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($incidents as $incident) {
                fputcsv($file, [
                    $incident->id,
                    $incident->title,
                    $incident->description,
                    $incident->status,
                    $incident->category->name ?? 'N/A',
                    match ($incident->priority) {
                        0 => 'High',
                        1 => 'Medium',
                        2 => 'Low',
                        default => 'N/A',
                    },
                
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

 public function fetch()
    {
        return response()->json(auth()->user()->unreadNotifications->take(5)->map(function ($notification) {
            return [
                'id' => $notification->id,
                'message' => $notification->data['message'] ?? 'No message',
                'incident_id' => $notification->data['incident_id'] ?? null,
            ];
        }));
    }

}
