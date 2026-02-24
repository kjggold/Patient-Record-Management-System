@extends('layouts.app')

@section('title', 'Services')

@section('content')
    <div class="app flex min-h-screen">
        {{-- Sidebar --}}
        @include('layouts.sidebar')

        <!-- MAIN CONTENT -->
        <div class="flex-1 p-6 bg-gray-50 ml-60">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-slate-700">Services</h1>
            </div>

            <div class="flex justify-between items-center mb-6 gap-3">
                <!-- Search Input -->
                <div class="relative w-full md:w-auto">
                    <input type="text"
                           id="searchInput"
                           placeholder="Search by name..."
                           class="w-full md:w-80 border border-blue-300 rounded-lg px-4 py-2 pl-10 pr-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ request('search') ?? '' }}"
                           autocomplete="off">

                    <!-- Search Icon - Left side -->
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>

                    <!-- Clear button - Right side -->
                    <button onclick="clearSearch()"
                            id="clearSearchBtn"
                            class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 {{ request('search') ? '' : 'hidden' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Add Service Button -->
                <button onclick="openAddServiceModal()"
                    class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    Add Service
                </button>
            </div>

            <!-- SERVICE TABLE CONTAINER -->
            <div id="servicesTableContainer">
                @include('services.partials.service-table', ['services' => $services, 'search' => request('search')])
            </div>

        </div> <!-- End of main content -->
    </div> <!-- End of app container -->

    <!-- ADD SERVICE MODAL -->
    <div id="addServiceModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-hidden ml-60">
            <div class="flex justify-between items-center p-6 border-b">
                <h2 class="text-xl font-bold text-gray-800">Add Service</h2>
                <button onclick="closeAddServiceModal()"
                        class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>

            <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 140px)">
                <form id="addServiceForm" method="POST" action="{{ route('services.store') }}">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="serviceName">Service Name</label>
                        <input type="text" id="serviceName" name="service_name" placeholder="Enter service name"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            value="{{ old('service_name') }}" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="serviceFee">Service Fee</label>
                        <input type="text" id="serviceFee" name="service_fee" placeholder="Enter fee amount"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            value="{{ old('service_fee') }}" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="serviceDescription">Description
                            <span class="text-gray-500 font-normal text-sm">(Optional)</span></label>
                        <textarea id="serviceDescription" name="description" placeholder="Optional description" rows="3"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description') }}</textarea>
                    </div>
                </form>
            </div>

            <div class="p-6 border-t bg-gray-50 flex justify-end gap-3">
                <button onclick="closeAddServiceModal()"
                    class="px-6 py-2.5 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors duration-300">Cancel</button>
                <button type="submit" form="addServiceForm"
                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-300">Save
                    Service</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let searchTimeout = null;
        let currentSearchTerm = "{{ request('search', '') }}";
        let abortController = null;
        const csrfToken = '{{ csrf_token() }}';

        // Update clear button visibility
        function updateClearButtonVisibility(searchTerm) {
            const clearBtn = document.getElementById('clearSearchBtn');
            if (clearBtn) {
                if (searchTerm && searchTerm.length > 0) {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                }
            }
        }

        // Perform search via AJAX
        function performSearch(page = 1) {
            const searchTerm = document.getElementById('searchInput').value.trim();

            // Cancel previous request if still pending
            if (abortController) {
                abortController.abort();
            }

            // Create new AbortController for this request
            abortController = new AbortController();

            // Show loading state
            const container = document.getElementById('servicesTableContainer');
            container.innerHTML = '<div class="text-center py-12"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-sky-600"></div><p class="mt-2 text-gray-600">Searching...</p></div>';

            // Build URL with search term and page
            let url = '{{ route("services.index") }}?page=' + page;
            if (searchTerm.trim() !== '') {
                url += '&search=' + encodeURIComponent(searchTerm);
            }

            // Add AJAX flag
            url += '&ajax=1';

            // Fetch search results
            fetch(url, {
                signal: abortController.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.html) {
                    container.innerHTML = data.html;

                    // Update URL without reloading page
                    const cleanUrl = window.location.pathname + (searchTerm ? '?search=' + encodeURIComponent(searchTerm) : '');
                    window.history.pushState({ path: cleanUrl }, '', cleanUrl);

                    // Update current search term
                    currentSearchTerm = searchTerm;

                    // Update clear button visibility
                    updateClearButtonVisibility(searchTerm);

                    // Re-attach event listeners to pagination links
                    attachPaginationListeners();
                }
            })
            .catch(error => {
                if (error.name === 'AbortError') {
                    console.log('Search request was aborted');
                    return;
                }
                console.error('Error:', error);
                container.innerHTML = '<div class="text-center py-12 text-red-600">An error occurred while searching. Please try again.</div>';
            })
            .finally(() => {
                abortController = null;
            });
        }

        // Attach listeners to pagination links
        function attachPaginationListeners() {
            document.querySelectorAll('.pagination a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = new URL(this.href);
                    const page = url.searchParams.get('page') || 1;
                    performSearch(page);
                });
            });
        }

        // Debounce function for search input
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

        // Setup search input with debounce
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', debounce(function(e) {
                    const searchTerm = e.target.value.trim();

                    // If search term hasn't changed, do nothing
                    if (searchTerm === currentSearchTerm) {
                        return;
                    }

                    // Update clear button visibility
                    updateClearButtonVisibility(searchTerm);

                    // Perform search from page 1
                    performSearch(1);
                }, 500));

                // Handle Enter key
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const searchTerm = this.value.trim();
                        if (searchTerm !== currentSearchTerm) {
                            performSearch(1);
                        }
                    }
                });

                // Focus search input if it has value
                if (searchInput.value) {
                    searchInput.focus();
                    searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
                }
            }

            // Initial clear button visibility
            updateClearButtonVisibility(currentSearchTerm);

            // Initial pagination listeners
            attachPaginationListeners();

            // Add escape key listener to close modal
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeAddServiceModal();
                }
            });
        });

        // Clear search function
        function clearSearch() {
            const searchInput = document.getElementById('searchInput');
            searchInput.value = '';
            searchInput.focus();

            // Update clear button
            updateClearButtonVisibility('');

            // Perform search with empty term
            if (currentSearchTerm !== '') {
                performSearch(1);
            }
        }

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
            performSearch(1);
        });

        // Modal functions
        function openAddServiceModal() {
            const form = document.getElementById('addServiceForm');
            if (form) form.reset();
            document.getElementById('addServiceModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeAddServiceModal() {
            document.getElementById('addServiceModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Close modal when clicking on backdrop
        document.getElementById('addServiceModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddServiceModal();
            }
        });
    </script>
@endpush