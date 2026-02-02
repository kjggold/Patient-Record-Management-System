@extends('layouts.app')

@section('title', 'Services')

@section('content')
    <div class="app flex min-h-screen">
        {{-- Sidebar --}}
        @include('layouts.sidebar')

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-6">
            <h1 class="text-2xl font-semibold text-slate-700 mb-4">Services</h1>

            <div class="flex justify-end items-center mb-6 gap-3">
                <input type="text" id="searchInput" placeholder="Search by name..." class="border rounded px-3 py-2 w-64">

                <!-- Add Medical Services Button -->
                <a href="{{ route('medical-services.index') }}"
                   class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700 flex items-center gap-2">
                    <i class="fa-solid fa-stethoscope"></i>
                    Medical Services
                </a>

                <button onclick="openAddServiceModal()"
                    class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700">
                    + Add Service
                </button>
            </div>

            <!-- SERVICE TABLE -->
            @if (isset($services) && $services->count())
                <div class="mb-8 bg-white rounded-xl shadow overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-sky-50 text-slate-600">
                            <tr>
                                <th class="px-4 py-3">Service Name</th>
                                <th class="px-4 py-3">Fee</th>
                                <th class="px-4 py-3">Description</th>
                                <th class="px-4 py-3">Created At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach ($services as $service)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $service->service_name }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $service->service_fee }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $service->description }}</td>
                                    <td class="px-4 py-3 text-gray-500 text-xs">
                                        {{ $service->created_at?->format('Y-m-d H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- PAGINATION -->
                    @if ($services->hasPages())
                        <div class="px-4 py-4 border-t bg-white rounded-b-xl">
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                <!-- Showing info -->
                                <div class="text-sm text-gray-600">
                                    Showing {{ $services->firstItem() }} to {{ $services->lastItem() }} of {{ $services->total() }} results
                                </div>

                                <!-- Pagination Links -->
                                <div class="flex items-center gap-1">
                                    <!-- Previous Page Link -->
                                    @if ($services->onFirstPage())
                                        <span class="px-3 py-2 rounded border text-gray-400 cursor-not-allowed">
                                            <i class="fa-solid fa-chevron-left"></i>
                                        </span>
                                    @else
                                        <a href="{{ $services->previousPageUrl() }}" class="px-3 py-2 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300">
                                            <i class="fa-solid fa-chevron-left"></i>
                                        </a>
                                    @endif

                                    <!-- Page Numbers -->
                                    @foreach ($services->getUrlRange(1, $services->lastPage()) as $page => $url)
                                        @if ($page == $services->currentPage())
                                            <span class="px-4 py-2 rounded border bg-sky-600 text-white font-medium border-sky-600">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <a href="{{ $url }}" class="px-4 py-2 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endforeach

                                    <!-- Next Page Link -->
                                    @if ($services->hasMorePages())
                                        <a href="{{ $services->nextPageUrl() }}" class="px-3 py-2 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300">
                                            <i class="fa-solid fa-chevron-right"></i>
                                        </a>
                                    @else
                                        <span class="px-3 py-2 rounded border text-gray-400 cursor-not-allowed">
                                            <i class="fa-solid fa-chevron-right"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-white rounded-xl shadow p-8 text-center">
                    <i class="fa-solid fa-list-check text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">No services found</h3>
                    <p class="text-gray-500 mb-4">Add your first service to get started</p>
                    <button onclick="openAddServiceModal()"
                        class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700">
                        + Add Service
                    </button>
                </div>
            @endif


    <!-- ADD SERVICE MODAL -->
    <div id="addServiceModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-blue-50 rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-hidden">
            <div class="flex justify-between items-center p-6 border-b">
                <h2 class="text-2xl font-bold text-gray-800">Add Service</h2>
                <button onclick="closeAddServiceModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>

            <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 140px)">
                <form id="addServiceForm" method="POST" action="{{ route('services.store') }}">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="serviceName">Service Name</label>
                        <input type="text" id="serviceName" name="service_name" placeholder="Enter service name"
                            class="w-full border border-blue-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            value="{{ old('service_name') }}" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="serviceFee">Service Fee</label>
                        <input type="text" id="serviceFee" name="service_fee" placeholder="Enter fee amount"
                            class="w-full border border-blue-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            value="{{ old('service_fee') }}" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="serviceDescription">Description
                            <span class="text-gray-500 font-normal text-sm">(Optional)</span></label>
                        <textarea id="serviceDescription" name="description" placeholder="Optional description" rows="3"
                            class="w-full border border-blue-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description') }}</textarea>
                    </div>
                </form>
            </div>

            <div class="p-6 border-t bg-blue-50 flex justify-end gap-3">
                <button onclick="closeAddServiceModal()"
                    class="px-6 py-2.5 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors duration-300">Cancel</button>
                <button type="submit" form="addServiceForm"
                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-300">Save
                    Service</button>
            </div>
        </div>
    </div>

    <style>
        /* Add Service Modal styles */
        #addServiceModal {
            animation: fadeIn 0.3s ease-out;
        }

        #addServiceModal>div {
            animation: slideUp 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Form input styling */
        #addServiceForm input,
        #addServiceForm textarea {
            font-size: 16px;
        }

        #addServiceForm input:focus,
        #addServiceForm textarea:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Pagination styling */
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pagination li {
            margin: 0 2px;
        }

        .pagination a,
        .pagination span {
            display: inline-block;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }

        .pagination .active span {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .pagination a:hover {
            background-color: #f8fafc;
            border-color: #93c5fd;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .flex.gap-3 {
                flex-direction: column;
                width: 100%;
            }

            .flex.gap-3 > * {
                width: 100%;
            }

            input[type="text"] {
                width: 100%;
            }

            /* Responsive pagination */
            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }

            .pagination li {
                margin: 2px;
            }
        }
    </style>

    @push('scripts')
        <script>
            function openAddServiceModal() {
                const form = document.getElementById('addServiceForm');
                if (form) form.reset();
                document.getElementById('addServiceModal').classList.remove('hidden');
            }

            function closeAddServiceModal() {
                document.getElementById('addServiceModal').classList.add('hidden');
            }

            // Search functionality
            document.getElementById('searchInput')?.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    const serviceName = row.querySelector('td:first-child').textContent.toLowerCase();
                    row.style.display = serviceName.includes(searchTerm) ? '' : 'none';
                });
            });
        </script>
    @endpush
@endsection