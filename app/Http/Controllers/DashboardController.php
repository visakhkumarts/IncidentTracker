<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $incidents = Incident::with(['user', 'assignedTo'])->latest()->take(10)->get();
            $totalIncidents = Incident::count();
            $openIncidents = Incident::where('status', 'open')->count();
            $inProgressIncidents = Incident::where('status', 'in-progress')->count();
            $resolvedIncidents = Incident::where('status', 'resolved')->count();
            
            return view('dashboard.admin', compact('incidents', 'totalIncidents', 'openIncidents', 'inProgressIncidents', 'resolvedIncidents'));
        } else {
            // Get incidents created by user or assigned to user
            $incidents = Incident::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhere('assigned_to', $user->id);
            })->with(['user', 'assignedTo'])->latest()->take(10)->get();
            
            $totalIncidents = Incident::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhere('assigned_to', $user->id);
            })->count();
            
            $openIncidents = Incident::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhere('assigned_to', $user->id);
            })->where('status', 'open')->count();
            
            return view('dashboard.user', compact('incidents', 'totalIncidents', 'openIncidents'));
        }
    }
}
