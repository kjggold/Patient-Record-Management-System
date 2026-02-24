<div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-sky-50 to-indigo-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PATIENT</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DOCTOR</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SERVICE</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DATE</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ACTIONS</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($appointments as $appointment)
                    <tr id="row-{{ $appointment->id }}" class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $appointment->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $appointment->patient->full_name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $appointment->doctor->full_name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $appointment->service->service_name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="editAppointment({{ $appointment->id }})"
                                        class="text-amber-600 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 px-3 py-1 rounded-md text-xs font-medium transition">
                                    Edit
                                </button>
                                <button onclick="openDischargeModal(
                                    '{{ $appointment->id }}',
                                    '{{ addslashes($appointment->patient->full_name ?? '') }}',
                                    '{{ addslashes($appointment->doctor->full_name ?? '') }}',
                                    '{{ addslashes($appointment->service->service_name ?? '') }}',
                                    '{{ $appointment->appointment_date }}',
                                    '{{ $appointment->service->service_fee ?? 0 }}',
                                    '{{ $appointment->doctor->consultation_fee ?? 0 }}'
                                )"
                                        class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-md text-xs font-medium transition">
                                    Discharge
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <h3 class="text-lg font-medium text-gray-700 mb-2">No appointments found</h3>
                            <p class="text-gray-500 mb-4">
                                @if(!empty($search))
                                    No appointments match your search criteria.
                                @else
                                    No appointments are scheduled in the system.
                                @endif
                            </p>
                            @if(!empty($search))
                                <a href="{{ route('appointments.index') }}"
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

{{-- PAGINATION - Same style as doctors table --}}
@if($appointments->hasPages())
<div class="mt-6 bg-white rounded-xl shadow px-4 py-4 border-t">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <!-- Showing info -->
        <div class="text-sm text-gray-600">
            Showing {{ $appointments->firstItem() }} to {{ $appointments->lastItem() }} of {{ $appointments->total() }} {{ Str::plural('appointment', $appointments->total()) }}
        </div>

        <!-- Pagination Links -->
        <div class="flex items-center gap-1">
            <!-- Previous Page Link -->
            @if ($appointments->onFirstPage())
                <span class="px-3 py-1.5 rounded border text-gray-400 cursor-not-allowed text-sm">
                    <i class="fa-solid fa-chevron-left w-3 h-3"></i>
                </span>
            @else
                <a href="{{ $appointments->previousPageUrl() }}"
                   class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                    <i class="fa-solid fa-chevron-left w-3 h-3"></i>
                </a>
            @endif

            <!-- Dynamic Page Numbers -->
            @php
                $currentPage = $appointments->currentPage();
                $lastPage = $appointments->lastPage();
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
                <a href="{{ $appointments->url(1) }}"
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
                    <a href="{{ $appointments->url($page) }}"
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
                <a href="{{ $appointments->url($lastPage) }}"
                   class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                    {{ $lastPage }}
                </a>
            @endif

            <!-- Next Page Link -->
            @if ($appointments->hasMorePages())
                <a href="{{ $appointments->nextPageUrl() }}"
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