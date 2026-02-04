@extends('layouts.app')

@section('title', 'Discharge | MediCore')

@section('content')
    <div class="app flex min-h-screen">
        @include('layouts.sidebar')

        <main class="flex-1 p-6 bg-gray-50">
            <div class="mb-2">
                <h1 class="text-2xl font-semibold text-slate-700">Discharge</h1>
            </div>
            <div class="flex justify-end mb-6">
                <input type="text" id="searchDischarge" placeholder="Search by patient or ID..."
                    class="border rounded-lg px-4 py-2 w-80 focus:ring-2 focus:ring-[#22d3ee] focus:outline-none">
            </div>
            <div class="overflow-x-auto bg-white rounded-xl shadow-md">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-[#22d3ee]/20">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">ID</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Patient</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Doctor</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Services</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Total</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Paid</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Balance</th>
                            <th class="px-4 py-2 text-center font-medium text-gray-700">Status</th>
                            <th class="px-4 py-2 text-center font-medium text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody id="dischargeBody" class="divide-y divide-gray-100">
                        @foreach ($discharges as $d)
                            <tr class="hover:bg-[#f0f8ff]">
                                <td class="px-4 py-2">{{ $d->appointment_id }}</td>
                                <td class="px-4 py-2">{{ $d->patient_name }}</td>
                                <td class="px-4 py-2">{{ $d->doctor_name }}</td>
                                <td class="px-4 py-2">
                                    @foreach ($d->services as $s)
                                        <span
                                            class="inline-block bg-[#22d3ee]/20 text-[#0d6efd] px-2 py-1 rounded-full text-xs mr-1">{{ $s['name'] }}</span>
                                    @endforeach
                                </td>
                                <td class="px-4 py-2">{{ $d->total }}</td>
                                <td class="px-4 py-2">{{ $d->paid }}</td>
                                <td class="px-4 py-2">{{ $d->balance }}</td>
                                <td class="px-4 py-2 text-center">
                                    @if ($d->balance <= 0)
                                        <span class="text-green-600 font-semibold">Completed</span>
                                    @else
                                        <span class="text-red-600 font-semibold">Pending</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <button onclick="viewDischarge('{{ $d->id }}')"
                                        class="bg-[#0d6efd] text-white px-3 py-1 rounded-lg hover:bg-[#084298]">View</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    {{-- Optional modal for viewing detailed discharge info --}}
    <div id="dischargeViewModal" class="fixed inset-0 bg-black/40 hidden justify-center items-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-3xl w-full p-6 overflow-auto">
            <h2 class="text-xl font-bold text-[#0d6efd] mb-4">Discharge Details</h2>
            <div id="dischargeDetails">
                {{-- Dynamic content loaded via JS --}}
            </div>
            <div class="flex justify-end mt-4 gap-2">
                <button onclick="closeDischargeModal()"
                    class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Close</button>
            </div>
        </div>
    </div>

    <script>
        function viewDischarge(id) {
            const modal = document.getElementById('dischargeViewModal');
            const detailsDiv = document.getElementById('dischargeDetails');

            // Example dynamic content - replace with AJAX fetch if needed
            detailsDiv.innerHTML = `<p>Loading details for ID ${id}...</p>`;
            modal.classList.remove('hidden');
        }

        function closeDischargeModal() {
            document.getElementById('dischargeViewModal').classList.add('hidden');
        }

        // Optional: Add search filter
        document.getElementById('searchDischarge').addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            document.querySelectorAll('#dischargeBody tr').forEach(tr => {
                const text = tr.innerText.toLowerCase();
                tr.style.display = text.includes(query) ? '' : 'none';
            });
        });
    </script>
@endsection