<div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-sky-50 to-indigo-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Specialty</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($doctors as $doctor)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $doctor->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $doctor->full_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $doctor->speciality }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $doctor->phone_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $doctor->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('doctors.edit', $doctor->id) }}"
                                   class="text-amber-600 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 px-3 py-1 rounded-md text-xs font-medium transition">
                                    Edit
                                </a>
                                <button onclick="deleteDoctor({{ $doctor->id }})"
                                        class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-md text-xs font-medium transition">
                                    Delete
                                </button>
                            </div>

                            {{-- Delete Form (Hidden) --}}
                            <form id="delete-form-{{ $doctor->id }}" action="{{ route('doctors.destroy', $doctor->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <h3 class="text-lg font-medium text-gray-700 mb-2">No doctors found</h3>
                            <p class="text-gray-500 mb-4">
                                @if(!empty($search))
                                    No doctors match your search criteria.
                                @else
                                    No doctors are registered in the system.
                                @endif
                            </p>
                            @if(!empty($search))
                                <a href="{{ route('doctors.index') }}"
                                   class="inline-block px-4 py-2 border border-sky-600 text-sky-600 rounded-lg hover:bg-sky-50 transition">
                                    Clear Search
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- PAGINATION --}}
@if($doctors->hasPages())
<div class="mt-6 bg-white rounded-xl shadow px-4 py-4 border-t">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <!-- Showing info -->
        <div class="text-sm text-gray-600">
            Showing {{ $doctors->firstItem() }} to {{ $doctors->lastItem() }} of {{ $doctors->total() }} {{ Str::plural('doctor', $doctors->total()) }}
        </div>

        <!-- Pagination Links -->
        <div class="flex items-center gap-1">
            <!-- Previous Page Link -->
            @if ($doctors->onFirstPage())
                <span class="px-3 py-1.5 rounded border text-gray-400 cursor-not-allowed text-sm">
                    <i class="fa-solid fa-chevron-left w-3 h-3"></i>
                </span>
            @else
                <a href="{{ $doctors->previousPageUrl() }}"
                   class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                    <i class="fa-solid fa-chevron-left w-3 h-3"></i>
                </a>
            @endif

            <!-- Dynamic Page Numbers -->
            @php
                $currentPage = $doctors->currentPage();
                $lastPage = $doctors->lastPage();
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
                <a href="{{ $doctors->url(1) }}"
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
                    <a href="{{ $doctors->url($page) }}"
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
                <a href="{{ $doctors->url($lastPage) }}"
                   class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                    {{ $lastPage }}
                </a>
            @endif

            <!-- Next Page Link -->
            @if ($doctors->hasMorePages())
                <a href="{{ $doctors->nextPageUrl() }}"
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