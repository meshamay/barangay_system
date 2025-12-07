@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <a href="{{ route('announcements.index') }}" 
           class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Announcements
        </a>

        <!-- Announcement Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Image -->
            @if($announcement->image_path)
                <img src="{{ asset('storage/' . $announcement->image_path) }}" 
                     alt="{{ $announcement->title }}" 
                     class="w-full h-96 object-cover">
            @endif

            <!-- Content -->
            <div class="p-8">
                <!-- Category -->
                @if($announcement->category)
                    <span class="inline-block px-3 py-1 text-sm font-semibold text-blue-800 bg-blue-100 rounded-full mb-4">
                        {{ $announcement->category }}
                    </span>
                @endif

                <!-- Title -->
                <h1 class="text-4xl font-bold text-gray-800 mb-4">
                    {{ $announcement->title }}
                </h1>

                <!-- Meta Info -->
                <div class="flex items-center text-gray-500 text-sm mb-8 pb-8 border-b border-gray-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Published on {{ $announcement->published_at?->format('F d, Y') ?? $announcement->created_at->format('F d, Y') }}
                </div>

                <!-- Content -->
                <div class="prose prose-lg max-w-none">
                    {!! nl2br(e($announcement->content)) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
