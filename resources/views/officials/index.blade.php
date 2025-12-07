@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <!-- Page Header -->
    <div class="mb-12 text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Barangay Officials</h1>
        <p class="text-lg text-gray-600">Meet the dedicated leaders serving our community</p>
    </div>

    <!-- Officials Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($officials as $official)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <!-- Photo -->
                @if($official->photo_path)
                    <img src="{{ asset('storage/' . $official->photo_path) }}" 
                         alt="{{ $official->name }}" 
                         class="w-full h-64 object-cover">
                @else
                    <div class="w-full h-64 bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center">
                        <svg class="w-24 h-24 text-white opacity-50" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    </div>
                @endif

                <!-- Content -->
                <div class="p-6 text-center">
                    <!-- Name -->
                    <h3 class="text-xl font-bold text-gray-800 mb-2">
                        {{ $official->name }}
                    </h3>

                    <!-- Position -->
                    <p class="text-blue-600 font-semibold mb-4">
                        {{ $official->position }}
                    </p>

                    <!-- Bio -->
                    @if($official->bio)
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            {{ $official->bio }}
                        </p>
                    @endif

                    <!-- Contact Info -->
                    <div class="space-y-2 text-sm text-gray-500">
                        @if($official->contact_number)
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                {{ $official->contact_number }}
                            </div>
                        @endif

                        @if($official->email)
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                {{ $official->email }}
                            </div>
                        @endif
                    </div>

                    <!-- Term -->
                    @if($official->term_start && $official->term_end)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-500">
                                Term: {{ $official->term_start->format('Y') }} - {{ $official->term_end->format('Y') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No Officials Listed</h3>
                <p class="text-gray-500">Official information will be available soon.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
