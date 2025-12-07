<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'BARIS') }} - @yield('title', 'Admin Dashboard')</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-gray-800">
                <!-- Logo -->
                <div class="flex items-center justify-center h-16 bg-gray-900">
                    <span class="text-white text-xl font-bold">BARIS Admin</span>
                </div>
                
                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="block px-4 py-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                        Dashboard
                    </a>
                    
                    <a href="{{ route('admin.residents.index') }}" 
                       class="block px-4 py-3 rounded-lg {{ request()->routeIs('admin.residents.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                        Residents
                    </a>
                    
                    <a href="{{ route('admin.documents.index') }}" 
                       class="block px-4 py-3 rounded-lg {{ request()->routeIs('admin.documents.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                        Document Requests
                    </a>
                    
                    <a href="{{ route('admin.complaints.index') }}" 
                       class="block px-4 py-3 rounded-lg {{ request()->routeIs('admin.complaints.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                        Complaints
                    </a>
                    
                    <a href="{{ route('admin.announcements.index') }}" 
                       class="block px-4 py-3 rounded-lg {{ request()->routeIs('admin.announcements.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                        Announcements
                    </a>
                    
                    <a href="{{ route('admin.officials.index') }}" 
                       class="block px-4 py-3 rounded-lg {{ request()->routeIs('admin.officials.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                        Officials
                    </a>
                    
                    <a href="{{ route('admin.reports') }}" 
                       class="block px-4 py-3 rounded-lg {{ request()->routeIs('admin.reports') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                        Reports & Analytics
                    </a>
                    
                    @if(Auth::user()->role === 'super_admin')
                        <div class="border-t border-gray-700 my-4"></div>
                        
                        <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase">Super Admin</div>
                        
                        <a href="{{ route('superadmin.staff.index') }}" 
                           class="block px-4 py-3 rounded-lg {{ request()->routeIs('superadmin.staff.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                            Staff Management
                        </a>
                        
                        <a href="{{ route('superadmin.audit-logs.index') }}" 
                           class="block px-4 py-3 rounded-lg {{ request()->routeIs('superadmin.audit-logs.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                            Audit Logs
                        </a>
                    @endif
                </nav>
                
                <!-- User Section -->
                <div class="flex-shrink-0 border-t border-gray-700 p-4">
                    <div class="mb-3">
                        <p class="text-sm font-medium text-white">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                        <p class="text-xs text-gray-400">{{ Auth::user()->role === 'super_admin' ? 'Super Admin' : 'Admin' }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg">
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
                    <button @click="sidebarOpen = true" class="md:hidden text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="text-2xl font-semibold text-gray-800 ml-4 md:ml-0">@yield('page-title', 'Dashboard')</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-800 text-sm">
                        View Site →
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
</body>
</html>
