<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'BARIS') }} - @yield('title', 'Dashboard')</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-white border-r border-gray-200">
                <!-- Logo -->
                <div class="flex items-center justify-center h-16 bg-blue-600">
                    <span class="text-white text-xl font-bold">BARIS</span>
                </div>
                
                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                    <a href="{{ route('resident.dashboard') }}" 
                       class="block px-4 py-3 rounded-lg {{ request()->routeIs('resident.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                        Dashboard
                    </a>
                    
                    <a href="{{ route('resident.documents.index') }}" 
                       class="block px-4 py-3 rounded-lg {{ request()->routeIs('resident.documents.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                        Document Requests
                    </a>
                    
                    <a href="{{ route('resident.complaints.index') }}" 
                       class="block px-4 py-3 rounded-lg {{ request()->routeIs('resident.complaints.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                        My Complaints
                    </a>
                    
                    <a href="{{ route('resident.announcements') }}" 
                       class="block px-4 py-3 rounded-lg {{ request()->routeIs('resident.announcements') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                        Announcements
                    </a>
                    
                    <a href="{{ route('resident.profile') }}" 
                       class="block px-4 py-3 rounded-lg {{ request()->routeIs('resident.profile') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                        My Profile
                    </a>
                </nav>
                
                <!-- User Section -->
                <div class="flex-shrink-0 border-t border-gray-200 p-4">
                    <div class="mb-3">
                        <p class="text-sm font-medium text-gray-700">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                        <p class="text-xs text-gray-500">Resident</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Top Bar -->
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200">
                <div class="flex items-center">
                    <button @click="sidebarOpen = true" class="md:hidden text-gray-500 focus:outline-none text-2xl mr-4">
                        ☰
                    </button>
                    <h1 class="text-2xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-800 text-sm">
                        ← Back to Home
                    </a>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-sm text-green-800">{{ session('success') }}</p>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-800">{{ session('error') }}</p>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" 
         class="fixed inset-0 z-50 bg-black bg-opacity-50 md:hidden" 
         x-cloak>
        <div @click.stop class="fixed inset-y-0 left-0 w-64 bg-white">
            <!-- Same sidebar content as desktop -->
            <div class="flex items-center justify-between h-16 px-4 bg-blue-600">
                <span class="text-white text-xl font-bold">BARIS</span>
                <button @click="sidebarOpen = false" class="text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <!-- Copy navigation from above -->
        </div>
    </div>
</body>
</html>
