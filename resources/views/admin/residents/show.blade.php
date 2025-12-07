@extends('layouts.admin')

@section('title', 'Resident Profile')
@section('page-title', 'Resident Profile')

@section('content')
<div class="grid md:grid-cols-3 gap-6">
    <div class="md:col-span-2 bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ $resident->name }}</h2>
        <div class="space-y-3 text-sm text-gray-700">
            <div class="flex justify-between"><span class="text-gray-500">Email:</span><span>{{ $resident->email }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Status:</span><span class="capitalize">{{ $resident->account_status ?? 'pending' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Role:</span><span class="capitalize">{{ $resident->role }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Created:</span><span>{{ optional($resident->created_at)->format('M d, Y h:i A') }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Updated:</span><span>{{ optional($resident->updated_at)->format('M d, Y h:i A') }}</span></div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-3">Activity</h3>
        <div class="space-y-2 text-sm text-gray-700">
            <div class="flex justify-between"><span>Total Documents</span><span>{{ $stats['total_documents'] }}</span></div>
            <div class="flex justify-between"><span>Pending Documents</span><span>{{ $stats['pending_documents'] }}</span></div>
            <div class="flex justify-between"><span>Completed Documents</span><span>{{ $stats['completed_documents'] }}</span></div>
            <div class="flex justify-between"><span>Total Complaints</span><span>{{ $stats['total_complaints'] }}</span></div>
            <div class="flex justify-between"><span>Open Complaints</span><span>{{ $stats['open_complaints'] }}</span></div>
        </div>
    </div>
</div>
@endsection
