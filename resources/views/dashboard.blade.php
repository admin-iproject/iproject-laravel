@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Projects -->
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Projects</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_projects'] }}</p>
                </div>
                <div class="p-3 bg-primary-100 rounded-lg">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Projects -->
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Projects</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['active_projects'] }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- My Tasks -->
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">My Tasks</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['my_tasks'] }}</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Open Tickets -->
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Open Tickets</p>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['open_tickets'] }}</p>
                </div>
                <div class="p-3 bg-red-100 rounded-lg">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Projects -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Recent Projects</h3>
                <a href="{{ route('projects.index') }}" class="text-sm text-primary-600 hover:text-primary-700">View All →</a>
            </div>

            @if($recentProjects->count() > 0)
                <div class="space-y-3">
                    @foreach($recentProjects as $project)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex-1">
                                <a href="{{ route('projects.show', $project) }}" class="font-medium text-gray-900 hover:text-primary-600">
                                    {{ $project->name }}
                                </a>
                                <p class="text-sm text-gray-600">{{ $project->company->name }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500">{{ $project->percent_complete }}%</span>
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $project->percent_complete }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No projects yet</p>
            @endif
        </div>

        <!-- My Tasks -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">My Tasks</h3>
                <a href="{{ route('tasks.index') }}" class="text-sm text-primary-600 hover:text-primary-700">View All →</a>
            </div>

            @if($myTasks->count() > 0)
                <div class="space-y-3">
                    @foreach($myTasks as $task)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex-1">
                                <a href="{{ route('tasks.show', $task) }}" class="font-medium text-gray-900 hover:text-primary-600">
                                    {{ $task->name }}
                                </a>
                                <p class="text-sm text-gray-600">{{ $task->project->name }}</p>
                            </div>
                            <div class="text-right">
                                @if($task->end_date)
                                    <p class="text-sm {{ $task->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                        {{ $task->end_date->format('M d') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No active tasks</p>
            @endif
        </div>

        <!-- My Tickets -->
        <div class="card lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Assigned Tickets</h3>
                <a href="{{ route('tickets.index') }}" class="text-sm text-primary-600 hover:text-primary-700">View All →</a>
            </div>

            @if($myTickets->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ticket</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($myTickets as $ticket)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <a href="{{ route('tickets.show', $ticket) }}" class="text-sm font-medium text-gray-900 hover:text-primary-600">
                                            {{ $ticket->subject }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ $ticket->company->name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge {{ $ticket->priority == 1 ? 'badge-danger' : ($ticket->priority == 2 ? 'badge-warning' : 'badge-info') }}">
                                            Priority {{ $ticket->priority }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge badge-warning">{{ ucfirst($ticket->status) }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500">
                                        {{ $ticket->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No assigned tickets</p>
            @endif
        </div>
    </div>
@endsection
