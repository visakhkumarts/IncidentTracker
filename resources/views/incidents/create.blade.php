@extends('layouts.app')

@section('title', 'Report New Incident')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-plus-circle mr-2"></i>
                Report New Incident
            </h1>
            <p class="text-gray-600 mt-1">Please provide details about the security incident you've encountered.</p>
        </div>
        
        <form method="POST" action="{{ route('incidents.store') }}" class="p-6 space-y-6">
            @csrf
            
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">
                    Incident Title <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title" required 
                       value="{{ old('title') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('title') border-red-500 @enderror"
                       placeholder="Brief description of the incident">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="severity" class="block text-sm font-medium text-gray-700">
                    Severity Level <span class="text-red-500">*</span>
                </label>
                <select name="severity" id="severity" required 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('severity') border-red-500 @enderror">
                    <option value="">Select severity level</option>
                    <option value="low" {{ old('severity') === 'low' ? 'selected' : '' }}>Low - Minor issue with minimal impact</option>
                    <option value="medium" {{ old('severity') === 'medium' ? 'selected' : '' }}>Medium - Moderate issue with some impact</option>
                    <option value="high" {{ old('severity') === 'high' ? 'selected' : '' }}>High - Serious issue with significant impact</option>
                    <option value="critical" {{ old('severity') === 'critical' ? 'selected' : '' }}>Critical - Severe issue requiring immediate attention</option>
                </select>
                @error('severity')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">
                    Detailed Description <span class="text-red-500">*</span>
                </label>
                <textarea name="description" id="description" rows="6" required
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('description') border-red-500 @enderror"
                          placeholder="Please provide a detailed description of the incident, including:
- What happened
- When it occurred
- Where it occurred
- Who was involved
- Any immediate actions taken
- Potential impact or consequences">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Be as detailed as possible to help with investigation and resolution.</p>
            </div>
            
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('incidents.index') }}" 
                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <i class="fas fa-arrow-left mr-2"></i>Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-paper-plane mr-2"></i>Report Incident
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
