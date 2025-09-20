<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Incident Tracking System')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div id="app">
        @auth
            <!-- Navigation -->
            <nav class="bg-white shadow-lg">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800">
                                <i class="fas fa-shield-alt mr-2"></i>
                                Incident Tracker
                            </a>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('incidents.index') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-list mr-1"></i>Incidents
                            </a>
                            <a href="{{ route('incidents.create') }}" class="bg-blue-600 text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                                <i class="fas fa-plus mr-1"></i>Report Incident
                            </a>
                            
                            <div class="relative">
                                <button class="flex items-center text-gray-600 hover:text-gray-900 focus:outline-none">
                                    <i class="fas fa-user mr-1"></i>
                                    {{ Auth::user()->name }}
                                    <i class="fas fa-chevron-down ml-1"></i>
                                </button>
                                
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user mr-2"></i>Profile
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        @endauth

        <!-- Main Content -->
        <main class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- JavaScript for dropdown -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdown = document.querySelector('.relative button');
            const dropdownMenu = document.querySelector('.relative .hidden');
            
            if (dropdown && dropdownMenu) {
                dropdown.addEventListener('click', function() {
                    dropdownMenu.classList.toggle('hidden');
                });
                
                document.addEventListener('click', function(event) {
                    if (!dropdown.contains(event.target)) {
                        dropdownMenu.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>
