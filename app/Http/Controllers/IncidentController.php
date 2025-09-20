<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\IncidentComment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    public function index(Request $request)
    {
        $query = Incident::with(['user', 'assignedTo', 'comments']);

        // Filter incidents based on user role
        if (!Auth::user()->isAdmin()) {
            // Normal users can only see incidents they created or are assigned to
            $query->where(function ($q) {
                $q->where('user_id', Auth::id())
                  ->orWhere('assigned_to', Auth::id());
            });
        }

        // Filter by severity
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $incidents = $query->paginate(10);

        return view('incidents.index', compact('incidents'));
    }

    public function create()
    {
        return view('incidents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical',
        ]);

        Incident::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'severity' => $request->severity,
        ]);

        return redirect()->route('incidents.index')->with('success', 'Incident reported successfully!');
    }

    public function show(Incident $incident)
    {
        $incident->load(['user', 'assignedTo', 'comments.user']);
        return view('incidents.show', compact('incident'));
    }

    public function edit(Incident $incident)
    {
        if (!$incident->canBeUpdatedBy(Auth::user())) {
            abort(403, 'Unauthorized action.');
        }

        return view('incidents.edit', compact('incident'));
    }

    public function update(Request $request, Incident $incident)
    {
        if (!$incident->canBeUpdatedBy(Auth::user())) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical',
        ]);

        $incident->update($request->only(['title', 'description', 'severity']));

        return redirect()->route('incidents.show', $incident)->with('success', 'Incident updated successfully!');
    }

    public function assign(Request $request, Incident $incident)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only admins can assign incidents.');
        }

        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $incident->update(['assigned_to' => $request->assigned_to]);

        return redirect()->back()->with('success', 'Incident assigned successfully!');
    }

    public function updateStatus(Request $request, Incident $incident)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only admins can update incident status.');
        }

        $request->validate([
            'status' => 'required|in:open,in-progress,resolved',
        ]);

        $incident->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Incident status updated successfully!');
    }

    public function addComment(Request $request, Incident $incident)
    {
        // Check if user can comment on this incident
        if (!$incident->canBeCommentedBy(Auth::user())) {
            abort(403, 'You can only comment on incidents you created or are assigned to.');
        }

        $request->validate([
            'comment' => 'required|string',
        ]);

        IncidentComment::create([
            'incident_id' => $incident->id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Comment added successfully!');
    }
}
