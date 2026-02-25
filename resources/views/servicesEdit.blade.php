@extends('layouts.app')

@section('title', 'Services')

@section('content')
    <div class="app flex min-h-screen">
        {{-- Sidebar --}}
        @include('layouts.sidebar')

        <!-- MAIN CONTENT -->
        <div class="flex-1 p-6 bg-gray-50 ml-60">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Edit Services</h1>

            <!-- ADD SERVICE MODAL -->
            <div id="addServiceModal"
                class="fixed inset-0 flex items-center justify-center p-4 ">
                <div class="bg-sky-50 rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-hidden ml-60">
                    <div class="flex justify-between items-center p-6 border-b">
                        <h2 class="text-xl font-bold text-gray-800">Edit Service</h2>
                    </div>
                    <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 140px)">
                        <form id="addServiceForm" method="POST" action="{{ route('services.update', $service->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label>Service ID</label>
                                <div class="service-id">{{ $service->id }}</div>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Service Name</label>
                                <input type="text" name="service_name" placeholder="Enter service name" value="{{ old('service_name', $service->service_name) }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                            </div>
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Service Fee</label>
                                <input type="text" name="service_fee" placeholder="Enter fee amount" value="{{ old('service_fee', $service->service_fee) }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                            </div>
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Description <span
                                        class="text-gray-500 font-normal text-sm">(Optional)</span></label>
                                <textarea name="description" placeholder="Optional description" rows="3"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $service->description) }}</textarea>
                            </div>
                        </form>
                    </div>
                    <div class="p-6 border-t bg-sky-50 flex justify-end gap-3">
                        <a href="{{ route('services.index') }}" class="cancel-btn text-center">
                            Cancel
                        </a>
                        <button type="submit" form="addServiceForm"
                            class="submit-btn">Update
                            Service</button>
                    </div>
                </div>
            </div>

        </div> <!-- End of main content -->
    </div> <!-- End of app container -->

    @push('scripts')
        <!-- <script>
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
        </script> -->

        <style>

        /* Footer Buttons */
        .footer-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding-top: 20px;
            border-top: 1px solid #e0f2fe;
        }

        .submit-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(to right, #10b981, #059669);
            color: white;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            min-width: 100px;
        }

        .submit-btn:hover {
            background: linear-gradient(to right, #059669, #047857);
            transform: translateY(-1px);
        }

        .cancel-btn {
            padding: 12px 24px;
            border: 1px solid #94a3b8;
            border-radius: 8px;
            background: transparent;
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            min-width: 100px;
        }

        .cancel-btn:hover {
            background-color: #f8fafc;
            border-color: #ef4444;
            color: #ef4444;
            text-decoration: none;
        }

        /* Input boxes */
        .form-group input,
        .form-group select,
        .service-id {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 2px solid #c8e1f3;
            font-size: 14px;
            outline: none;
            background-color: #ffffff;
            transition: all 0.2s ease;
        }

        .service-id {
            background-color: #eaf4fb;
            font-weight: 600;
            color: #355f8c;
            grid-column: 1 / -1;
            margin-bottom: 10px;
        }
    </style>
    @endpush
@endsection
