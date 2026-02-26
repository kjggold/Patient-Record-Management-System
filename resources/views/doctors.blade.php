@extends('layouts.app')

@section('title', 'Doctors')

@section('content')
<div class="app flex min-h-screen">
    {{-- Side bar --}}
    @include('layouts.sidebar')

    <main class="flex-1 p-6 ml-60">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-slate-700">Doctor Lists</h1>
        </div>

        <div class="flex justify-between items-center mb-6 gap-3">
            <!-- Search Input -->
            <div class="flex gap-2">
                <input type="text" id="searchInput" placeholder="Search by name, specialty, phone or email"
                    class="border rounded px-3 py-2 w-80 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ request('search') ?? '' }}"
                    autocomplete="off">
                    <button onclick="openAddModal()" class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700">
                        + Add Doctor
                    </button>
            </div>


        </div>

        <!-- DOCTOR TABLE CONTAINER -->
        <div id="doctorTableContainer">
            @include('doctors.partials.doctor-table', ['doctors' => $doctors, 'search' => request('search')])
        </div>
    </main>
</div>

<!-- Include the add doctor modal -->
{{-- @include('add-modals.doctor-modal') --}}
@include('doctors.partials.add-modal')

@push('scripts')
<script>
    let searchTimeout = null;
    const csrfToken = '{{ csrf_token() }}';

    // Wait for DOM to load
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure modal is hidden on page load
        const modal = document.getElementById('addModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Setup search input with debounce
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    performSearch();
                }, 500); // Wait 500ms after user stops typing
            });

            // Also search on Enter key
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    performSearch();
                }
            });
        }
    });

    // Perform search via AJAX
    function performSearch(page = 1) {
        const searchTerm = document.getElementById('searchInput').value;

        // Show loading state
        const container = document.getElementById('doctorTableContainer');
        container.innerHTML = '<div class="text-center py-8"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-sky-600"></div><p class="mt-2 text-gray-600">Searching...</p></div>';

        // Build URL with search term and page
        let url = `{{ route('doctors.index') }}?page=${page}`;
        if (searchTerm.trim() !== '') {
            url += `&search=${encodeURIComponent(searchTerm)}`;
        }

        // Fetch search results
        fetch(url, {
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

                // Update browser URL without reloading
                const newUrl = window.location.pathname + (searchTerm ? '?search=' + encodeURIComponent(searchTerm) : '');
                window.history.pushState({ path: newUrl }, '', newUrl);

                // Re-attach event listeners to pagination links
                attachPaginationListeners();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = '<div class="text-center py-8 text-red-600">An error occurred while searching. Please try again.</div>';
        });
    }

    // Attach click handlers to pagination links
    function attachPaginationLinks() {
        document.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(this.href);
                const page = url.searchParams.get('page') || 1;
                performSearch(page);
            });
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

    // Modal functions
    function openAddModal() {
        const modal = document.getElementById('addModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }
    }

    function closeAddModal() {
        const modal = document.getElementById('addModal');
        if (modal) {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            // Reset form
            const form = document.getElementById('dbDoctorForm');
            if (form) form.reset();
        }
    }

    // Delete doctor function
    function deleteDoctor(doctorId) {
        if (confirm('Are you sure you want to delete this doctor?')) {
            document.getElementById(`delete-form-${doctorId}`).submit();
        }
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('addModal');
        if (modal && e.target.id === 'addModal') {
            closeAddModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddModal();
        }
    });

    // Handle browser back/forward buttons
    window.addEventListener('popstate', function() {
        location.reload();
    });
</script>
@endpush
@endsection