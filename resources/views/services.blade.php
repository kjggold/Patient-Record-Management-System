@extends('layouts.app')

@section('title', 'Services')

@section('content')
    <div class="app flex min-h-screen">
        {{-- Sidebar --}}
        @include('layouts.sidebar')

        <!-- MAIN CONTENT -->
        <div class="flex-1 p-6 bg-gray-50 ml-60">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Services</h1>

            <!-- Search + Add Service Button aligned right -->
            <div class="flex justify-end items-center mb-6 gap-3">
                <!-- Search Form -->
                <form id="searchForm" action="{{ route('services.index') }}" method="GET" class="flex gap-2">
                    <input type="text" name="search" id="searchInput" placeholder="Search by name..."
                        class="w-64 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ request('search') ?? '' }}" autocomplete="off">
                </form>

                <!-- Add Service Button -->
                <button onclick="openAddServiceModal()"
                    class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    Add Service
                </button>
            </div>

            <!-- SERVICE TABLE CONTAINER -->
            <div id="servicesTableContainer">
                @if (isset($services) && $services->count())
                    <div class="mb-8 bg-white rounded-xl shadow overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-sky-50 text-slate-600">
                                    <tr>
                                        <th class="px-6 py-3">Service Name</th>
                                        <th class="px-6 py-3">Fee</th>
                                        <th class="px-6 py-3">Description</th>
                                        <th class="px-6 py-3">Created At</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @foreach ($services as $service)
                                        <tr class="hover:bg-slate-50">
                                            <td class="px-6 py-4 font-medium text-gray-900">{{ $service->service_name }}
                                            </td>
                                            <td class="px-6 py-4 text-gray-700">{{ $service->service_fee }}</td>
                                            <td class="px-6 py-4 text-gray-600">{{ $service->description }}</td>
                                            <td class="px-6 py-4 text-gray-500 text-sm">
                                                {{ $service->created_at?->format('Y-m-d H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- PAGINATION -->
                        @if ($services->hasPages())
                            <div class="px-6 py-4 border-t bg-white">
                                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                    <div class="text-sm text-gray-600">
                                        Showing {{ $services->firstItem() }} to {{ $services->lastItem() }} of
                                        {{ $services->total() }} results
                                        @if (request('search'))
                                            <span class="text-blue-600 ml-2">(Searching all {{ $services->total() }}
                                                matches)</span>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-1">
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

                                        @foreach ($services->getUrlRange(1, $services->lastPage()) as $page => $url)
                                            @if ($page == $services->currentPage())
                                                <span
                                                    class="px-4 py-2 rounded border bg-sky-600 text-white font-medium border-sky-600">{{ $page }}</span>
                                            @else
                                                <a href="{{ $url . (request('search') ? '&search=' . urlencode(request('search')) : '') }}"
                                                    class="px-4 py-2 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300">{{ $page }}</a>
                                            @endif
                                        @endforeach

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
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 text-center">
                        <i class="fa-solid fa-list-check text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-700 mb-2">
                            @if (request('search'))
                                No services found for "{{ request('search') }}"
                            @else
                                No services found
                            @endif
                        </h3>
                        <p class="text-gray-500 mb-4">Try adding your first service</p>
                        <button onclick="openAddServiceModal()"
                            class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700">
                            + Add Service
                        </button>
                    </div>
                @endif
            </div>

            <!-- ADD SERVICE MODAL -->
            <div id="addServiceModal"
                class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4 ">
                <div class="bg-sky-50 rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-hidden ml-60">
                    <div class="flex justify-between items-center p-6 border-b">
                        <h2 class="text-xl font-bold text-gray-800">Add Service</h2>
                        <button onclick="closeAddServiceModal()"
                            class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                    </div>
                    <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 140px)">
                        <form id="addServiceForm" method="POST" action="{{ route('services.store') }}">
                            @csrf
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Service Name</label>
                                <input type="text" name="service_name" placeholder="Enter service name"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                            </div>
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Service Fee</label>
                                <input type="text" name="service_fee" placeholder="Enter fee amount"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                            </div>
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Description <span
                                        class="text-gray-500 font-normal text-sm">(Optional)</span></label>
                                <textarea name="description" placeholder="Optional description" rows="3"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="p-6 border-t bg-sky-50 flex justify-end gap-3">
                        <button onclick="closeAddServiceModal()"
                            class="px-6 py-2.5 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg">Cancel</button>
                        <button type="submit" form="addServiceForm"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">Save
                            Service</button>
                    </div>
                </div>
            </div>

        </div> <!-- End of main content -->
    </div> <!-- End of app container -->

    @push('scripts')
        <script>
            // AJAX search as you type
            let searchTimeout, currentSearchTerm = "{{ request('search', '') }}",
                abortController = null;

            function performAjaxSearch(searchTerm) {
                if (abortController) abortController.abort();
                abortController = new AbortController();
                const url = new URL("{{ route('services.index') }}", window.location.origin);
                if (searchTerm) url.searchParams.set('search', searchTerm);
                else {
                    url.searchParams.delete('search');
                    url.searchParams.delete('page');
                }
                url.searchParams.set('ajax', '1');

                fetch(url.toString(), {
                        signal: abortController.signal,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.text()).then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newTableContainer = doc.querySelector('#servicesTableContainer');
                        if (newTableContainer) document.getElementById('servicesTableContainer').innerHTML =
                            newTableContainer.innerHTML;
                        const cleanUrl = url.toString().replace('&ajax=1', '').replace('?ajax=1', '');
                        window.history.replaceState({}, '', cleanUrl);
                        currentSearchTerm = searchTerm;
                    }).catch(e => {
                        if (e.name === 'AbortError') return;
                        window.location.href = url.toString().replace('&ajax=1', '').replace('?ajax=1', '');
                    })
                    .finally(() => {
                        abortController = null;
                    });
            }

            document.getElementById('searchInput')?.addEventListener('input', e => {
                const term = e.target.value.trim();
                if (term === currentSearchTerm) return;
                performAjaxSearch(term);
            });

            function openAddServiceModal() {
                document.getElementById('addServiceModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeAddServiceModal() {
                document.getElementById('addServiceModal').classList.add('hidden');
                document.body.style.overflow = '';
            }
            document.getElementById('addServiceModal')?.addEventListener('click', e => {
                if (e.target === this) closeAddServiceModal();
            });
        </script>
    @endpush
@endsection
