@extends('layouts.app')

@section('title', 'Incidents')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Incidents
                </h1>
                <p class="text-gray-600">Manage and track security incidents</p>
            </div>
            <a href="{{ route('incidents.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Report New Incident
            </a>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white shadow rounded-lg p-6">
        <form method="GET" action="{{ route('incidents.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="Search by title or description..."
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                
                <div>
                    <label for="severity" class="block text-sm font-medium text-gray-700">Severity</label>
                    <select name="severity" id="severity" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">All Severities</option>
                        <option value="low" {{ request('severity') === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('severity') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('severity') === 'high' ? 'selected' : '' }}>High</option>
                        <option value="critical" {{ request('severity') === 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">All Statuses</option>
                        <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in-progress" {{ request('status') === 'in-progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    </select>
                </div>
                
                <div class="flex items-end {{ request()->hasAny(['search', 'severity', 'status', 'sort_by', 'sort_order']) ? 'space-x-2' : '' }}">
                    <button type="submit" class="{{ request()->hasAny(['search', 'severity', 'status', 'sort_by', 'sort_order']) ? 'flex-1' : 'w-full' }} bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    @if(request()->hasAny(['search', 'severity', 'status', 'sort_by', 'sort_order']))
                        <a href="{{ route('incidents.index') }}" class="flex-1 bg-gray-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-600 text-center">
                            <i class="fas fa-times mr-2"></i>Clear
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Incidents Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if($incidents->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'title', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}" class="hover:text-gray-700">
                                Title
                                @if(request('sort_by') === 'title')
                                    <i class="fas fa-sort-{{ request('sort_order') === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'severity', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}" class="hover:text-gray-700">
                                Severity
                                @if(request('sort_by') === 'severity')
                                    <i class="fas fa-sort-{{ request('sort_order') === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}" class="hover:text-gray-700">
                                Status
                                @if(request('sort_by') === 'status')
                                    <i class="fas fa-sort-{{ request('sort_order') === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reporter</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}" class="hover:text-gray-700">
                                Created
                                @if(request('sort_by') === 'created_at' || !request('sort_by'))
                                    <i class="fas fa-sort-{{ request('sort_order') === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($incidents as $incident)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $incident->title }}</div>
                                <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($incident->description, 50) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($incident->severity === 'critical') bg-red-100 text-red-800
                                    @elseif($incident->severity === 'high') bg-orange-100 text-orange-800
                                    @elseif($incident->severity === 'medium') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst($incident->severity) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($incident->status === 'open') bg-red-100 text-red-800
                                    @elseif($incident->status === 'in-progress') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst(str_replace('-', ' ', $incident->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $incident->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $incident->assignedTo ? $incident->assignedTo->name : 'Unassigned' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $incident->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('incidents.show', $incident) }}" class="text-blue-600 hover:text-blue-900 mr-3" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($incident->canBeUpdatedBy(Auth::user()))
                                    <a href="{{ route('incidents.edit', $incident) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $incidents->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No incidents found</h3>
                <p class="text-gray-500 mb-4">
                    @if(request()->hasAny(['search', 'severity', 'status']))
                        Try adjusting your filters or search terms.
                    @else
                        No incidents have been reported yet.
                    @endif
                </p>
                <a href="{{ route('incidents.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>Report New Incident
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
