@extends('layouts.app')

@section('title', 'Welcome to Barangay Daang Bakal')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                Welcome to Barangay Daang Bakal
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-blue-100">
                Automated Record and Information System (BARIS)
            </p>
            <p class="text-lg mb-8 max-w-3xl mx-auto">
                Access barangay services anytime, anywhere. Request documents, file complaints, 
                and stay updated with the latest announcements.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                @guest
                    <a href="{{ route('register') }}" class="bg-white text-blue-600 hover:bg-blue-50 px-8 py-3 rounded-lg font-semibold text-lg transition">
                        Register as Resident
                    </a>
                    <a href="{{ route('login') }}" class="bg-blue-700 hover:bg-blue-600 px-8 py-3 rounded-lg font-semibold text-lg transition border-2 border-white">
                        Login
                    </a>
                @else
                    <a href="{{ route('resident.dashboard') }}" class="bg-white text-blue-600 hover:bg-blue-50 px-8 py-3 rounded-lg font-semibold text-lg transition">
                        Go to Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </div>
</div>

<!-- About Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">About Barangay Daang Bakal</h2>
            <div class="w-24 h-1 bg-blue-600 mx-auto mb-8"></div>
        </div>
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h3 class="text-2xl font-semibold mb-4 text-gray-800">Our Mission</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    To provide efficient, transparent, and accessible services to all residents of Barangay Daang Bakal 
                    through modern technology and dedicated public service.
                </p>
                <h3 class="text-2xl font-semibold mb-4 text-gray-800">Our Vision</h3>
                <p class="text-gray-600 leading-relaxed">
                    A progressive, safe, and united community where every resident has easy access to essential 
                    barangay services and information.
                </p>
            </div>
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-8 rounded-lg">
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="bg-blue-600 rounded-full p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-1">Fast Processing</h4>
                            <p class="text-gray-600 text-sm">Document requests processed within 1 day</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="bg-blue-600 rounded-full p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-1">24/7 Access</h4>
                            <p class="text-gray-600 text-sm">Submit requests anytime, anywhere</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="bg-blue-600 rounded-full p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-1">Real-time Updates</h4>
                            <p class="text-gray-600 text-sm">Get notified of your request status</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Services Section -->
<div id="services" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Our Services</h2>
            <div class="w-24 h-1 bg-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Request official documents online and track your requests in real-time
            </p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Barangay Clearance -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition">
                <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mb-4 mx-auto">
                    <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 text-center mb-2">Barangay Clearance</h3>
                <p class="text-gray-600 text-sm text-center">
                    Proof of residency and good standing within the community
                </p>
            </div>

            <!-- Barangay Certificate -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition">
                <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mb-4 mx-auto">
                    <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 text-center mb-2">Barangay Certificate</h3>
                <p class="text-gray-600 text-sm text-center">
                    Official document for proof of residency and identity
                </p>
            </div>

            <!-- Indigency Clearance -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition">
                <div class="bg-yellow-100 rounded-full w-16 h-16 flex items-center justify-center mb-4 mx-auto">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 text-center mb-2">Indigency Clearance</h3>
                <p class="text-gray-600 text-sm text-center">
                    Certificate for residents with low or no income
                </p>
            </div>

            <!-- Resident Certificate -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition">
                <div class="bg-purple-100 rounded-full w-16 h-16 flex items-center justify-center mb-4 mx-auto">
                    <svg class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 text-center mb-2">Resident Certificate</h3>
                <p class="text-gray-600 text-sm text-center">
                    Confirmation of residence and duration of stay
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Officials Section -->
<div id="officials" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Barangay Officials</h2>
            <div class="w-24 h-1 bg-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Meet the dedicated leaders serving our community
            </p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($officials as $official)
                <div class="text-center">
                    <div class="w-40 h-40 mx-auto mb-4 rounded-full overflow-hidden bg-gray-200">
                        @if($official->photo_path)
                            <img src="{{ asset('storage/' . $official->photo_path) }}" alt="{{ $official->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-blue-100">
                                <svg class="h-20 w-20 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <h3 class="font-semibold text-lg text-gray-800">{{ $official->name }}</h3>
                    <p class="text-blue-600 font-medium">{{ $official->position }}</p>
                    @if($official->contact_number)
                        <p class="text-sm text-gray-600 mt-1">{{ $official->contact_number }}</p>
                    @endif
                </div>
            @empty
                <div class="col-span-4 text-center py-8 text-gray-500">
                    No officials listed yet.
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Announcements Section -->
<div id="announcements" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Latest Announcements</h2>
            <div class="w-24 h-1 bg-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Stay informed with the latest news and updates from the barangay
            </p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($announcements as $announcement)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    @if($announcement->image_path)
                        <img src="{{ asset('storage/' . $announcement->image_path) }}" alt="{{ $announcement->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                            <svg class="h-16 w-16 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                    @endif
                    <div class="p-6">
                        @if($announcement->category)
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mb-2">
                                {{ $announcement->category }}
                            </span>
                        @endif
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">{{ $announcement->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($announcement->content, 120) }}</p>
                        <div class="flex items-center text-xs text-gray-500">
                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $announcement->published_at?->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-8 text-gray-500">
                    No announcements available at this time.
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- FAQs Section -->
<div id="faqs" class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
            <div class="w-24 h-1 bg-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Find answers to common questions about our services
            </p>
        </div>
        <div class="space-y-4" x-data="{ openFaq: null }">
            @forelse($faqs as $index => $faq)
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <button @click="openFaq = openFaq === {{ $index }} ? null : {{ $index }}" class="w-full text-left px-6 py-4 bg-gray-50 hover:bg-gray-100 transition flex justify-between items-center">
                        <span class="font-semibold text-gray-800">{{ $faq->question }}</span>
                        <svg class="h-5 w-5 text-gray-500 transition-transform" :class="{ 'rotate-180': openFaq === {{ $index }} }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="openFaq === {{ $index }}" x-collapse class="px-6 py-4 bg-white">
                        <p class="text-gray-600">{{ $faq->answer }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    No FAQs available at this time.
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Get Started?</h2>
        <p class="text-xl mb-8 text-blue-100">
            Register now to access barangay services online
        </p>
        @guest
            <a href="{{ route('register') }}" class="bg-white text-blue-600 hover:bg-blue-50 px-8 py-3 rounded-lg font-semibold text-lg inline-block transition">
                Register Now
            </a>
        @else
            <a href="{{ route('resident.dashboard') }}" class="bg-white text-blue-600 hover:bg-blue-50 px-8 py-3 rounded-lg font-semibold text-lg inline-block transition">
                Go to Dashboard
            </a>
        @endguest
    </div>
</div>
@endsection
