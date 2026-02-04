@extends('layouts.app')

@section('title', $title ?? 'Approval')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center p-6">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-lg p-8">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold text-gray-900">{{ $title ?? 'Approval' }}</h1>
            <a href="{{ route('welcome') }}" class="text-sm text-blue-600 hover:text-blue-700">Return to Welcome</a>
        </div>
        <div class="rounded-lg border p-4 text-sm
            @if(($status ?? '') === 'success')
                bg-green-50 border-green-200 text-green-800
            @elseif(($status ?? '') === 'warning')
                bg-red-50 border-red-200 text-red-800
            @else
                bg-gray-50 border-gray-200 text-gray-800
            @endif">
            {{ $message ?? '' }}
        </div>
        @if(($status ?? '') === 'success')
            <div class="mt-6 text-sm text-gray-600">
                You can now close this page. The user may open the app on any device (desktop or mobile) and log in using their email and password.
            </div>
        @elseif(($status ?? '') === 'warning')
            <div class="mt-6 text-sm text-gray-600">
                This registration has been declined. The user will not be able to log in with this email.
            </div>
        @endif
    </div>
</div>
@endsection

