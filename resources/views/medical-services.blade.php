@extends('layouts.app')

@section('title', 'Medical Services')

@section('content')
    <div class="app flex min-h-screen">
        {{-- Sidebar --}}
        @include('layouts.sidebar')

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-slate-700">Medical Services</h1>
                <a href="{{ route('services.index') }}" class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700">
                    ← Back to Services
                </a>
            </div>

            <!-- MEDICAL SERVICES GRID -->
            <div class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="servicesGrid">
                    @if (isset($clinicServices) && count($clinicServices) > 0)
                        @foreach ($clinicServices as $index => $clinicService)
                            <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200 hover:shadow-lg transition-all duration-300 service-card">
                                <div class="p-6">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <div class="w-12 h-12
                                                    @if ($index % 5 == 0) bg-blue-50
                                                    @elseif($index % 5 == 1) bg-green-50
                                                    @elseif($index % 5 == 2) bg-red-50
                                                    @elseif($index % 5 == 3) bg-yellow-50
                                                    @else bg-purple-50 @endif
                                                    rounded-lg flex items-center justify-center">
                                                    @if ($index % 5 == 0)
                                                        <i class="fa-solid fa-user-md text-blue-500 text-xl"></i>
                                                    @elseif($index % 5 == 1)
                                                        <i class="fa-solid fa-child text-green-500 text-xl"></i>
                                                    @elseif($index % 5 == 2)
                                                        <i class="fa-solid fa-heart-pulse text-red-500 text-xl"></i>
                                                    @elseif($index % 5 == 3)
                                                        <i class="fa-solid fa-vial text-yellow-500 text-xl"></i>
                                                    @else
                                                        <i class="fa-solid fa-female text-purple-500 text-xl"></i>
                                                    @endif
                                                </div>
                                            </div>
                                            <h3 class="text-xl font-semibold text-gray-900">{{ $clinicService['name'] }}</h3>
                                            <p class="text-gray-600 mt-2 text-sm">{{ $clinicService['description'] }}</p>
                                            @if (isset($clinicService['doctors']) && count($clinicService['doctors']) > 0)
                                                <p class="text-sm text-gray-500 mt-1">
                                                    <i class="fa-solid fa-user-doctor mr-1"></i>
                                                    {{ count($clinicService['doctors']) }} doctor(s) available
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <button onclick="openServiceModal({{ $index }})"
                                        class="mt-4 w-full
                                        @if ($index % 5 == 0) bg-blue-500 hover:bg-blue-600
                                        @elseif($index % 5 == 1) bg-green-500 hover:bg-green-600
                                        @elseif($index % 5 == 2) bg-red-500 hover:bg-red-600
                                        @elseif($index % 5 == 3) bg-yellow-500 hover:bg-yellow-600
                                        @else bg-purple-500 hover:bg-purple-600 @endif
                                        text-white font-medium py-2.5 px-4 rounded-lg transition-colors duration-300 text-sm">
                                        View Services & Doctors
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-full text-center py-12">
                            <i class="fa-solid fa-stethoscope text-gray-300 text-5xl mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-600 mb-2">No Medical Services Available</h3>
                            <p class="text-gray-500">Medical services will be added soon.</p>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <!-- SERVICE DETAILS MODAL -->
    <div id="serviceModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
            <div class="flex justify-between items-center p-6 border-b">
                <div>
                    <h2 id="modalServiceName" class="text-2xl font-bold text-gray-800"></h2>
                    <p id="modalServiceDesc" class="text-gray-600 mt-1"></p>
                </div>
                <button onclick="closeServiceModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>

            <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 140px)">
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Illness Categories</h3>
                    <div id="modalCategories" class="grid grid-cols-3 gap-4"></div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Available Doctors</h3>
                    <div id="modalDoctors" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
                </div>
            </div>

            <div class="p-6 border-t bg-gray-50 flex justify-end">
                <button onclick="closeServiceModal()"
                    class="px-6 py-2.5 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors duration-300">
                    Close
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const clinicData = @json($clinicServices ?? []);

            function openServiceModal(index) {
                const data = clinicData[index];
                if (!data) return;

                document.getElementById('modalServiceName').textContent = data.name || 'Service';
                document.getElementById('modalServiceDesc').textContent = data.description || 'Medical service';

                const categoriesList = document.getElementById('modalCategories');
                if (data.illnesses && data.illnesses.length > 0) {
                    categoriesList.innerHTML = data.illnesses.map(illness => `
                        <div class="bg-gray-50 border border-gray-200 rounded-md p-1 text-center hover:bg-blue-50 hover:border-blue-200 transition-colors duration-200">
                            <i class="fa-solid fa-circle-info text-blue-500 text-xs mb-1"></i>
                            <p class="text-gray-700 text-xs font-medium">${illness}</p>
                        </div>
                    `).join('');
                } else {
                    categoriesList.innerHTML =
                        `<div class="col-span-full text-center py-4"><p class="text-gray-500">No specific illness categories listed</p></div>`;
                }

                const doctorsList = document.getElementById('modalDoctors');
                if (!data.doctors || data.doctors.length === 0) {
                    doctorsList.innerHTML = `<div class="col-span-full text-center py-8">
                        <i class="fa-solid fa-user-md text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">No doctors available for this service</p>
                        <p class="text-gray-400 text-sm mt-2">Please check back later or contact the clinic</p>
                    </div>`;
                } else {
                    doctorsList.innerHTML = data.doctors.map(doctor => `
                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
                            <div class="p-5">
                                <div class="flex items-center mb-4">
                                    <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                        <i class="fa-solid fa-user-md text-blue-600 text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-800 text-lg mb-1">${doctor.name || 'Doctor'}</h4>
                                        <p class="text-blue-600 font-medium">${doctor.speciality || 'Medical Specialist'}</p>
                                    </div>
                                </div>
                                <div class="space-y-3 mb-4">
                                    <div class="flex items-center text-gray-700"><i class="fa-solid fa-graduation-cap text-gray-400 mr-2 w-5"></i><span class="text-sm">${doctor.qualification || 'MD Specialist'}</span></div>
                                    <div class="flex items-center text-gray-700"><i class="fa-solid fa-clock text-gray-400 mr-2 w-5"></i><span class="text-sm">${doctor.availability || 'Mon-Fri: 9AM-5PM'}</span></div>
                                    <div class="flex items-center text-gray-700"><i class="fa-solid fa-star text-yellow-400 mr-2 w-5"></i><span class="text-sm">${doctor.rating || '4.5'}/5 Rating</span></div>
                                    <div class="flex items-center text-gray-700"><i class="fa-solid fa-briefcase text-gray-400 mr-2 w-5"></i><span class="text-sm">${doctor.experience || 'Experience not specified'}</span></div>
                                </div>
                                <div class="pt-4 border-t border-gray-100 flex justify-between items-center">
                                    <div>
                                        <p class="text-sm text-gray-500">Consultation Fee</p>
                                        <p class="text-green-600 font-bold text-lg">${doctor.fee || 'Contact for price'}</p>
                                    </div>
                                    <button onclick="bookAppointment(${doctor.id || 0}, '${doctor.name ? doctor.name.replace(/'/g,"\\'") : 'Doctor'}', '${data.name ? data.name.replace(/'/g,"\\'") : 'Service'}')" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-300 text-sm">
                                        Book Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    `).join('');
                }

                document.getElementById('serviceModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeServiceModal() {
                document.getElementById('serviceModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            function bookAppointment(doctorId, doctorName, serviceName) {
                const confirmation =
                    `Booking Details:\n\n👨‍⚕️ Doctor: ${doctorName}\n🏥 Service: ${serviceName}\n📅 Date: ${new Date().toLocaleDateString()}\n\nProceed to appointment form?`;
                if (confirm(confirmation)) {
                    alert(`✅ Appointment request sent to ${doctorName}\n\nYou will be contacted to confirm the appointment.`);
                    closeServiceModal();
                }
            }
        </script>
    @endpush
@endsection