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
                <!-- Search Input with clear button -->
                <div class="relative">
                    <input type="text"
                           id="searchInput"
                           placeholder="Search by name..."
                           class="border rounded px-3 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10"
                           value="{{ request('search') ?? '' }}"
                           autocomplete="off">
                    <!-- Clear button (shown only when there's text) -->
                    @if(request('search'))
                        <button onclick="clearSearch()"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    @endif
                </div>

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

            <!-- SERVICE TABLE CONTAINER -->
            <div id="servicesTableContainer">
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

                                        @if(request('search'))
                                            <span class="text-blue-600 ml-2">
                                                (Searching all {{ $services->total() }} matches)
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Pagination Links -->
                                    <div class="flex items-center gap-1">
                                        <!-- Previous Page Link -->
                                        @if ($services->onFirstPage())
                                            <span class="px-3 py-2 rounded border text-gray-400 cursor-not-allowed">
                                                <i class="fa-solid fa-chevron-left"></i>
                                            </span>
                                        @else
                                            <a href="{{ $services->previousPageUrl() . (request('search') ? '&search=' . urlencode(request('search')) : '') }}"
                                               class="px-3 py-2 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300">
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
                                                <a href="{{ $url . (request('search') ? '&search=' . urlencode(request('search')) : '') }}"
                                                   class="px-4 py-2 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300">
                                                    {{ $page }}
                                                </a>
                                            @endif
                                        @endforeach

                                        <!-- Next Page Link -->
                                        @if ($services->hasMorePages())
                                            <a href="{{ $services->nextPageUrl() . (request('search') ? '&search=' . urlencode(request('search')) : '') }}"
                                               class="px-3 py-2 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300">
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
                        <h3 class="text-lg font-medium text-gray-700 mb-2">
                            @if(request('search'))
                                No services found for "{{ request('search') }}"
                            @else
                                No services found
                            @endif
                        </h3>
                        <p class="text-gray-500 mb-4">Add your first service to get started</p>
                        <button onclick="openAddServiceModal()"
                            class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700">
                            + Add Service
                        </button>
                    </div>
                @endif
            </div>

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
                }
            </style>

            @push('scripts')
                <script>
                    // Variables
                    let searchTimeout;
                    let currentSearchTerm = "{{ request('search', '') }}";
                    let abortController = null;

                    // Update clear button visibility
                    function updateClearButtonVisibility(searchTerm) {
                        const searchInput = document.getElementById('searchInput');
                        const clearBtn = searchInput?.parentElement.querySelector('.fa-times');

                        if (clearBtn) {
                            clearBtn.parentElement.style.display = searchTerm ? 'block' : 'none';
                        }
                    }

                    // Debounce function
                    function debounce(func, wait) {
                        let timeout;
                        return function executedFunction(...args) {
                            const later = () => {
                                clearTimeout(timeout);
                                func(...args);
                            };
                            clearTimeout(timeout);
                            timeout = setTimeout(later, wait);
                        };
                    }

                    // Optimized AJAX search function
                    function performAjaxSearch(searchTerm) {
                        // Cancel previous request if still pending
                        if (abortController) {
                            abortController.abort();
                        }

                        // Create new AbortController for this request
                        abortController = new AbortController();

                        // Build URL - IMPORTANT: When search is empty, go to page 1
                        const url = new URL("{{ route('services.index') }}", window.location.origin);

                        // Only add search parameter if there's a search term
                        if (searchTerm) {
                            url.searchParams.set('search', searchTerm);
                        } else {
                            // When search is cleared, explicitly remove search param and go to page 1
                            url.searchParams.delete('search');
                            url.searchParams.delete('page'); // Go to page 1
                        }

                        // Add AJAX flag
                        url.searchParams.set('ajax', '1');

                        // Make AJAX request
                        fetch(url.toString(), {
                            signal: abortController.signal,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.text())
                        .then(html => {
                            // Parse the response
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');

                            // Find the table container in the response
                            const newTableContainer = doc.querySelector('#servicesTableContainer');

                            if (newTableContainer) {
                                // Update the table container
                                document.getElementById('servicesTableContainer').innerHTML = newTableContainer.innerHTML;
                            }

                            // Update URL without reloading page (remove ajax parameter)
                            const cleanUrl = url.toString().replace('&ajax=1', '').replace('?ajax=1', '');
                            window.history.replaceState({}, '', cleanUrl);

                            // Update current search term
                            currentSearchTerm = searchTerm;

                            // Update clear button visibility
                            updateClearButtonVisibility(searchTerm);
                        })
                        .catch(error => {
                            if (error.name === 'AbortError') {
                                console.log('Search request was aborted');
                                return;
                            }
                            console.error('Search error:', error);
                            // Fallback to traditional page reload
                            window.location.href = url.toString().replace('&ajax=1', '').replace('?ajax=1', '');
                        })
                        .finally(() => {
                            abortController = null;
                        });
                    }

                    // Search as you type
                    document.getElementById('searchInput')?.addEventListener('input', debounce(function(e) {
                        const searchTerm = e.target.value.trim();

                        // If search term hasn't changed, do nothing
                        if (searchTerm === currentSearchTerm) {
                            return;
                        }

                        // Update clear button visibility
                        updateClearButtonVisibility(searchTerm);

                        // Perform search
                        performAjaxSearch(searchTerm);
                    }, 500));

                    // Clear search function - go back to original page 1
                    function clearSearch() {
                        const searchInput = document.getElementById('searchInput');
                        searchInput.value = '';
                        searchInput.focus();

                        // Update clear button
                        updateClearButtonVisibility('');

                        // Immediately trigger search with empty term to go to page 1
                        performAjaxSearch('');
                    }

                    // Handle Enter key in search
                    document.getElementById('searchInput')?.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            const searchTerm = this.value.trim();
                            if (searchTerm !== currentSearchTerm) {
                                performAjaxSearch(searchTerm);
                            }
                        }
                    });

                    // Handle browser back/forward buttons
                    window.addEventListener('popstate', function() {
                        // Get search term from URL
                        const urlParams = new URLSearchParams(window.location.search);
                        const searchTerm = urlParams.get('search') || '';

                        // Update input field
                        const searchInput = document.getElementById('searchInput');
                        if (searchInput) {
                            searchInput.value = searchTerm;
                        }

                        // Update current search term
                        currentSearchTerm = searchTerm;

                        // Update clear button
                        updateClearButtonVisibility(searchTerm);

                        // Perform search with current term
                        performAjaxSearch(searchTerm);
                    });

                    // Modal functions
                    function openAddServiceModal() {
                        const form = document.getElementById('addServiceForm');
                        if (form) form.reset();
                        document.getElementById('addServiceModal').classList.remove('hidden');
                    }

                    function closeAddServiceModal() {
                        document.getElementById('addServiceModal').classList.add('hidden');
                    }

                    // Initial clear button setup
                    document.addEventListener('DOMContentLoaded', function() {
                        updateClearButtonVisibility(currentSearchTerm);

                        // Focus search input if it has value
                        const searchInput = document.getElementById('searchInput');
                        if (searchInput && searchInput.value) {
                            searchInput.focus();
                            searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
                        }
                    });
                </script>
            @endpush
        </main>
    </div>
@endsection
