@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <!-- Page Header -->
    <div class="mb-12 text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Announcements</h1>
        <p class="text-lg text-gray-600">Stay updated with the latest news and announcements from our barangay</p>
    </div>

    <!-- Announcements Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($announcements as $announcement)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <!-- Image -->
                @if($announcement->image_path)
                    <img src="{{ asset('storage/' . $announcement->image_path) }}" 
                         alt="{{ $announcement->title }}" 
                         class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center">
                        <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                        </svg>
                    </div>
                @endif

                <!-- Content -->
                <div class="p-6">
                    <!-- Category Badge -->
                    @if($announcement->category)
                        <span class="inline-block px-3 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full mb-3">
                            {{ $announcement->category }}
                        </span>
                    @endif

                    <!-- Title -->
                    <h3 class="text-xl font-bold text-gray-800 mb-3">
                        {{ $announcement->title }}
                    </h3>

                    <!-- Excerpt -->
                    <p class="text-gray-600 mb-4 line-clamp-3">
                        {{ Str::limit(strip_tags($announcement->content), 150) }}
                    </p>

                    <!-- Footer -->
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">
                            {{ $announcement->published_at?->format('M d, Y') ?? $announcement->created_at->format('M d, Y') }}
                        </span>
                        <a href="{{ route('announcements.show', $announcement->id) }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                            Read More →
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                </svg>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No Announcements Yet</h3>
                <p class="text-gray-500">Check back later for updates from the barangay.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
