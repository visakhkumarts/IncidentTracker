@extends('layouts.app')

@section('title', 'Incident Details')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Incident Header -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $incident->title }}</h1>
                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        <span>Reported by {{ $incident->user->name }}</span>
                        <span>•</span>
                        <span>{{ $incident->created_at->format('M d, Y \a\t g:i A') }}</span>
                        @if($incident->updated_at != $incident->created_at)
                            <span>•</span>
                            <span>Updated {{ $incident->updated_at->format('M d, Y \a\t g:i A') }}</span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    @if($incident->canBeUpdatedBy(Auth::user()))
                        <a href="{{ route('incidents.edit', $incident) }}" 
                           class="bg-yellow-600 text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-yellow-700">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                    @endif
                    <a href="{{ route('incidents.index') }}" 
                       class="bg-gray-600 text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-1"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
        
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Severity</label>
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full
                        @if($incident->severity === 'critical') bg-red-100 text-red-800
                        @elseif($incident->severity === 'high') bg-orange-100 text-orange-800
                        @elseif($incident->severity === 'medium') bg-yellow-100 text-yellow-800
                        @else bg-green-100 text-green-800
                        @endif">
                        {{ ucfirst($incident->severity) }}
                    </span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full
                        @if($incident->status === 'open') bg-red-100 text-red-800
                        @elseif($incident->status === 'in-progress') bg-yellow-100 text-yellow-800
                        @else bg-green-100 text-green-800
                        @endif">
                        {{ ucfirst(str_replace('-', ' ', $incident->status)) }}
                    </span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Assigned To</label>
                    <span class="text-sm text-gray-900">
                        {{ $incident->assignedTo ? $incident->assignedTo->name : 'Unassigned' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Incident Description -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-file-alt mr-2"></i>
                Description
            </h3>
        </div>
        <div class="px-6 py-4">
            <div class="prose max-w-none">
                {!! nl2br(e($incident->description)) !!}
            </div>
        </div>
    </div>

    <!-- Admin Actions -->
    @if(Auth::user()->isAdmin())
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-cogs mr-2"></i>
                    Admin Actions
                </h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <!-- Assign Incident -->
                <form method="POST" action="{{ route('incidents.assign', $incident) }}" class="flex items-end space-x-4">
                    @csrf
                    <div class="flex-1">
                        <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-1">Assign to User</label>
                        <select name="assigned_to" id="assigned_to" 
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Select a user</option>
                            @foreach(\App\Models\User::where('id', '!=', $incident->user_id)->get() as $user)
                                <option value="{{ $user->id }}" {{ $incident->assigned_to == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                        <i class="fas fa-user-plus mr-1"></i>Assign
                    </button>
                </form>
                
                <!-- Update Status -->
                <form method="POST" action="{{ route('incidents.status', $incident) }}" class="flex items-end space-x-4">
                    @csrf
                    <div class="flex-1">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Update Status</label>
                        <select name="status" id="status" 
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="open" {{ $incident->status === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in-progress" {{ $incident->status === 'in-progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ $incident->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700">
                        <i class="fas fa-check mr-1"></i>Update Status
                    </button>
                </form>
            </div>
        </div>
    @endif

    <!-- Comments Section -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-comments mr-2"></i>
                Comments ({{ $incident->comments->count() }})
            </h3>
        </div>
        
        <!-- Add Comment Form -->
        @if($incident->canBeCommentedBy(Auth::user()))
            <div class="px-6 py-4 border-b border-gray-200">
                <form method="POST" action="{{ route('incidents.comments', $incident) }}">
                    @csrf
                    <div>
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">Add Comment</label>
                        <textarea name="comment" id="comment" rows="3" required
                                  class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  placeholder="Add your comment here..."></textarea>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                            <i class="fas fa-paper-plane mr-1"></i>Add Comment
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="text-center py-4 text-gray-500">
                    <i class="fas fa-lock text-2xl mb-2"></i>
                    <p>You can only comment on incidents you created or are assigned to.</p>
                </div>
            </div>
        @endif
        
        <!-- Comments List -->
        <div class="px-6 py-4">
            @if($incident->comments->count() > 0)
                <div class="space-y-4">
                    @foreach($incident->comments as $comment)
                        <div class="border-l-4 border-blue-200 pl-4 py-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-medium text-gray-900">{{ $comment->user->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $comment->created_at->format('M d, Y \a\t g:i A') }}</span>
                                </div>
                            </div>
                            <div class="mt-1 text-sm text-gray-700">
                                {!! nl2br(e($comment->comment)) !!}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-comment-slash text-2xl mb-2"></i>
                    <p>No comments yet. Be the first to add a comment!</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
