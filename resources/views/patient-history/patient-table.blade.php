<div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-sky-50 to-indigo-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PATIENT NAME</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BLOOD TYPE</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AGE/GENDER</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">LAST VISIT</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TOTAL VISITS</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ACTIONS</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($patients as $patient)
                    @php
                        // Get appointments for this patient based on selected date
                        $appointmentsQuery = App\Models\Appointment::where('patient_id', $patient->id);

                        if ($dateType != 'all') {
                            $appointmentsQuery->where(function($query) use ($dateType, $selectedDate) {
                                if ($dateType == 'today') {
                                    $query->whereDate('appointment_date', today());
                                } elseif ($dateType == 'yesterday') {
                                    $query->whereDate('appointment_date', today()->subDay());
                                } elseif ($dateType == 'week') {
                                    $query->whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()]);
                                } elseif ($dateType == 'month') {
                                    $query->whereMonth('appointment_date', now()->month);
                                } elseif ($dateType == 'custom') {
                                    $query->whereDate('appointment_date', $selectedDate);
                                }
                            });
                        }

                        $appointmentsCount = $appointmentsQuery->count();

                        // Get discharges count
                        $dischargesQuery = DB::table('discharges')
                            ->where('patient_name', $patient->full_name);

                        if ($dateType != 'all') {
                            if ($dateType == 'today') {
                                $dischargesQuery->whereDate('created_at', today());
                            } elseif ($dateType == 'yesterday') {
                                $dischargesQuery->whereDate('created_at', today()->subDay());
                            } elseif ($dateType == 'week') {
                                $dischargesQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                            } elseif ($dateType == 'month') {
                                $dischargesQuery->whereMonth('created_at', now()->month);
                            } elseif ($dateType == 'custom') {
                                $dischargesQuery->whereDate('created_at', $selectedDate);
                            }
                        }

                        $dischargesCount = $dischargesQuery->count();

                        // Total visits = appointments + discharges
                        $totalVisits = $appointmentsCount + $dischargesCount;

                        // Get last activity (either appointment or discharge)
                        $lastAppointment = $appointmentsQuery->latest('appointment_date')->first();
                        $lastDischarge = DB::table('discharges')
                            ->where('patient_name', $patient->full_name)
                            ->latest('created_at')
                            ->first();

                        $lastVisitDate = null;
                        if ($lastAppointment && $lastDischarge) {
                            $lastVisitDate = max(
                                \Carbon\Carbon::parse($lastAppointment->appointment_date),
                                \Carbon\Carbon::parse($lastDischarge->created_at)
                            );
                        } elseif ($lastAppointment) {
                            $lastVisitDate = \Carbon\Carbon::parse($lastAppointment->appointment_date);
                        } elseif ($lastDischarge) {
                            $lastVisitDate = \Carbon\Carbon::parse($lastDischarge->created_at);
                        }
                    @endphp

                    {{-- Only show patient row if they have appointments or discharges on selected date OR dateType is 'all' --}}
                    @if($dateType == 'all' || $appointmentsCount > 0 || $dischargesCount > 0)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $patient->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $patient->full_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($patient->blood_type)
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">{{ $patient->blood_type }}</span>
                            @else
                                <span class="text-gray-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            @if($patient->age && $patient->sex_gender)
                                {{ $patient->age }} | {{ ucfirst($patient->sex_gender) }}
                            @elseif($patient->age)
                                {{ $patient->age }}y
                            @elseif($patient->sex_gender)
                                {{ ucfirst($patient->sex_gender) }}
                            @else
                                <span class="text-gray-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            @if($lastVisitDate)
                                {{ $lastVisitDate->format('M d, Y') }}
                            @else
                                <span class="text-gray-400">No visits</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 bg-sky-100 text-sky-700 rounded-full text-xs font-medium">
                                @if($dateType == 'all')
                                    {{ App\Models\Appointment::where('patient_id', $patient->id)->count() + DB::table('discharges')->where('patient_name', $patient->full_name)->count() }}
                                @else
                                    {{ $totalVisits }}
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('patient-history.show', $patient->id) }}?date={{ $selectedDate }}"
                                   class="text-sky-600 hover:text-sky-900 bg-sky-50 hover:bg-sky-100 px-3 py-1 rounded-md text-xs font-medium transition">
                                    Details
                                </a>
                                <a href="{{ route('patient-history.edit', $patient->id) }}?date={{ $selectedDate }}"
                                   class="text-amber-600 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 px-3 py-1 rounded-md text-xs font-medium transition">
                                    Edit
                                </a>
                                <button type="button"
                                        onclick="confirmDelete({{ $patient->id }})"
                                        class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-md text-xs font-medium transition">
                                    Delete
                                </button>
                            </div>

                            {{-- Delete Form (Hidden) --}}
                            <form id="delete-form-{{ $patient->id }}" action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @endif
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <h3 class="text-lg font-medium text-gray-700 mb-2">
                            @if($dateType == 'all')
                                No patients found
                            @else
                                No appointments or discharges found for
                                @if($dateType == 'today')
                                    today
                                @elseif($dateType == 'yesterday')
                                    yesterday
                                @elseif($dateType == 'week')
                                    this week
                                @elseif($dateType == 'month')
                                    this month
                                @elseif($dateType == 'custom')
                                    {{ \Carbon\Carbon::parse($selectedDate)->format('F j, Y') }}
                                @endif
                            @endif
                        </h3>
                        <p class="text-gray-500 mb-4">
                            @if($dateType == 'all')
                                No patients are registered in the system.
                            @else
                                No patients have appointments or discharges scheduled for this date.
                            @endif
                        </p>
                        @if($dateType != 'all' || request('search'))
                        <div class="flex justify-center space-x-3">
                            @if($dateType != 'all')
                                <a href="{{ route('patient-history.index', array_merge(request()->except(['date_type', 'selected_date', 'page']))) }}"
                                   class="inline-block px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition shadow-sm">
                                    Show All Patients
                                </a>
                            @endif
                            @if(request('search'))
                                <a href="{{ route('patient-history.index', array_merge(request()->except(['search', 'page']))) }}"
                                   class="inline-block px-4 py-2 border border-sky-600 text-sky-600 rounded-lg hover:bg-sky-50 transition">
                                    Clear Search
                                </a>
                            @endif
                        </div>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- DOCTOR-STYLE PAGINATION with buttons at bottom right --}}
@if($patients->hasPages())
<div class="mt-6 bg-white rounded-xl shadow px-4 py-4 border-t">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <!-- Showing info -->
        <div class="text-sm text-gray-600">
            Showing {{ $patients->firstItem() }} to {{ $patients->lastItem() }} of {{ $patients->total() }} {{ Str::plural('patient', $patients->total()) }}
        </div>

        <!-- Pagination Links -->
        <div class="flex items-center gap-1">
            <!-- Previous Page Link -->
            @if ($patients->onFirstPage())
                <span class="px-3 py-1.5 rounded border text-gray-400 cursor-not-allowed text-sm">
                    <i class="fa-solid fa-chevron-left w-3 h-3"></i>
                </span>
            @else
                <a href="{{ $patients->previousPageUrl() }}"
                   class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                    <i class="fa-solid fa-chevron-left w-3 h-3"></i>
                </a>
            @endif

            <!-- Dynamic Page Numbers -->
            @php
                $currentPage = $patients->currentPage();
                $lastPage = $patients->lastPage();
                $startPage = max(1, $currentPage - 2);
                $endPage = min($lastPage, $currentPage + 2);

                // Adjust to show more pages if needed
                if ($startPage > 1) {
                    $endPage = min($lastPage, $startPage + 4);
                }
                if ($endPage < $lastPage) {
                    $startPage = max(1, $endPage - 4);
                }
            @endphp

            <!-- First page -->
            @if ($startPage > 1)
                <a href="{{ $patients->url(1) }}"
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
                    <a href="{{ $patients->url($page) }}"
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
                <a href="{{ $patients->url($lastPage) }}"
                   class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                    {{ $lastPage }}
                </a>
            @endif

            <!-- Next Page Link -->
            @if ($patients->hasMorePages())
                <a href="{{ $patients->nextPageUrl() }}"
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