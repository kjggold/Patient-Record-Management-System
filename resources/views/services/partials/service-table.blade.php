<div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-sky-50 to-indigo-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($services as $service)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $service->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $service->service_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ number_format($service->service_fee) }} Ks</td>
                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">{{ $service->description ?? 'No description' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $service->created_at ? $service->created_at->format('Y-m-d H:i') : 'N/A' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <h3 class="text-lg font-medium text-gray-700 mb-2">No services found</h3>
                            <p class="text-gray-500 mb-4">
                                @if(!empty($search))
                                    No services match your search criteria.
                                @else
                                    No services are registered in the system.
                                @endif
                            </p>
                            @if(!empty($search))
                                <a href="{{ route('services.index') }}"
                                   class="inline-block px-4 py-2 border border-sky-600 text-sky-600 rounded-lg hover:bg-sky-50 transition">
                                    Clear Search
                                </a>
                            @else
                                <button onclick="openAddServiceModal()"
                                    class="inline-block px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition">
                                    + Add Service
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- PAGINATION --}}
@if($services->hasPages())
<div class="mt-6 bg-white rounded-xl shadow px-4 py-4 border-t">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <!-- Showing info -->
        <div class="text-sm text-gray-600">
            Showing {{ $services->firstItem() }} to {{ $services->lastItem() }} of {{ $services->total() }} {{ Str::plural('service', $services->total()) }}
        </div>

        <!-- Pagination Links -->
        <div class="flex items-center gap-1">
            <!-- Previous Page Link -->
            @if ($services->onFirstPage())
                <span class="px-3 py-1.5 rounded border text-gray-400 cursor-not-allowed text-sm">
                    <i class="fa-solid fa-chevron-left w-3 h-3"></i>
                </span>
            @else
                <a href="{{ $services->previousPageUrl() }}"
                   class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                    <i class="fa-solid fa-chevron-left w-3 h-3"></i>
                </a>
            @endif

            <!-- Dynamic Page Numbers -->
            @php
                $currentPage = $services->currentPage();
                $lastPage = $services->lastPage();
                $startPage = max(1, $currentPage - 2);
                $endPage = min($lastPage, $currentPage + 2);

                if ($startPage > 1) {
                    $endPage = min($lastPage, $startPage + 4);
                }
                if ($endPage < $lastPage) {
                    $startPage = max(1, $endPage - 4);
                }
            @endphp

            <!-- First page -->
            @if ($startPage > 1)
                <a href="{{ $services->url(1) }}"
                   class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                    1
                </a>
                @if ($startPage > 2)
                    <span class="px-2 text-gray-400">...</span>
                @endif
            @endif

            <!-- Page Numbers -->
            @for ($page = $startPage; $page <= $endPage; $page++)
                @if ($page == $currentPage)
                    <span class="px-3 py-1.5 rounded border bg-sky-600 text-white font-medium border-sky-600 text-sm">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $services->url($page) }}"
                       class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                        {{ $page }}
                    </a>
                @endif
            @endfor

            <!-- Last page -->
            @if ($endPage < $lastPage)
                @if ($endPage < $lastPage - 1)
                    <span class="px-2 text-gray-400">...</span>
                @endif
                <a href="{{ $services->url($lastPage) }}"
                   class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                    {{ $lastPage }}
                </a>
            @endif

            <!-- Next Page Link -->
            @if ($services->hasMorePages())
                <a href="{{ $services->nextPageUrl() }}"
                   class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                    <i class="fa-solid fa-chevron-right w-3 h-3"></i>
                </a>
            @else
                <span class="px-3 py-1.5 rounded border text-gray-400 cursor-not-allowed text-sm">
                    <i class="fa-solid fa-chevron-right w-3 h-3"></i>
                </span>
            @endif
        </div>
    </div>
</div>
@endif