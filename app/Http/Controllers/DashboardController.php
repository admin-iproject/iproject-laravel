<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get user's statistics
        $stats = [
            'total_projects' => Project::when(!$user->hasRole('admin'), function ($query) use ($user) {
                return $query->where('company_id', $user->company_id);
            })->count(),
            
            'active_projects' => Project::active()
                ->when(!$user->hasRole('admin'), function ($query) use ($user) {
                    return $query->where('company_id', $user->company_id);
                })->count(),
            
            'my_tasks' => Task::where('owner_id', $user->id)
                ->where('percent_complete', '<', 100)
                ->count(),
            
            'open_tickets' => 0, // TODO: Add when Ticket model is ready
        ];

        // Get recent projects
        $recentProjects = Project::with(['company', 'owner'])
            ->when(!$user->hasRole('admin'), function ($query) use ($user) {
                return $query->where('company_id', $user->company_id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get user's tasks
        $myTasks = Task::with(['project'])
            ->where('owner_id', $user->id)
            ->where('percent_complete', '<', 100)
            ->orderBy('end_date', 'asc')
            ->limit(10)
            ->get();

        // Get assigned tickets
        $myTickets = collect([]); // TODO: Add when Ticket model is ready

        return view('dashboard', compact('stats', 'recentProjects', 'myTasks'));
    }
}
