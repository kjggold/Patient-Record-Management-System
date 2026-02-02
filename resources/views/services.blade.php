@extends('layouts.app')

@section('title', 'Services')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <h2 class="logo">MediCore</h2>
        <nav>
            <a href="{{ route('dashboard') }}"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
            <a href="{{ route('patients.index') }}"><i class="fa-solid fa-user"></i> Patients</a>
            <a href="{{ route('doctors.index') }}"><i class="fa-solid fa-user-doctor"></i> Doctors</a>
            <a><i class="fa-solid fa-calendar-check"></i> Appointments</a>
            <a href="{{ route('services.index') }}" class="{{ request()->is('services') ? 'active' : '' }}">
                <i class="fa-solid fa-stethoscope"></i> Services
            </a>
            <a><i class="fa-solid fa-credit-card"></i> Payments</a>
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <a href="{{ route('logout') }}" class="logout"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </form>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-8">
        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-700">Services</h1>
            <div class="flex gap-3">
                {{-- <input type="text"
                       placeholder="Search by service..."
                       class="border rounded px-3 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       id="searchServices"> --}}
                <button onclick="openAddServiceModal()" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">
                    + Add Services
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(isset($services) && $services->count())
            <div class="mb-8 bg-white rounded-lg shadow border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Service List (from database)</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="border-b bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-3 py-2">Service Name</th>
                                <th class="px-3 py-2">Fee</th>
                                <th class="px-3 py-2">Description</th>
                                <th class="px-3 py-2 w-32">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($services as $service)
                                <tr class="border-b last:border-0">
                                    <td class="px-3 py-2 font-medium text-gray-900">{{ $service->service_name }}</td>
                                    <td class="px-3 py-2 text-gray-700">{{ $service->service_fee }}</td>
                                    <td class="px-3 py-2 text-gray-600">{{ $service->description }}</td>
                                    <td class="px-3 py-2 text-gray-500 text-xs">{{ $service->created_at?->format('Y-m-d H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- MEDICAL SERVICES GRID -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Medical Services</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="servicesGrid">
                @if(isset($clinicServices) && count($clinicServices) > 0)
                    @foreach($clinicServices as $index => $clinicService)
                        <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200 hover:shadow-lg transition-all duration-300 service-card">
                            <div class="p-6">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-12 h-12 @if($index % 5 == 0) bg-blue-50 @elseif($index % 5 == 1) bg-green-50 @elseif($index % 5 == 2) bg-red-50 @elseif($index % 5 == 3) bg-yellow-50 @else bg-purple-50 @endif rounded-lg flex items-center justify-center">
                                                @if($index % 5 == 0)
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
                                        <!-- Show number of doctors available -->
                                        @if(isset($clinicService['doctors']) && count($clinicService['doctors']) > 0)
                                            <p class="text-sm text-gray-500 mt-1">
                                                <i class="fa-solid fa-user-doctor mr-1"></i>
                                                {{ count($clinicService['doctors']) }} doctor(s) available
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <button onclick="openServiceModal({{ $index }})" 
                                        class="mt-4 w-full @if($index % 5 == 0) bg-blue-500 hover:bg-blue-600 @elseif($index % 5 == 1) bg-green-500 hover:bg-green-600 @elseif($index % 5 == 2) bg-red-500 hover:bg-red-600 @elseif($index % 5 == 3) bg-yellow-500 hover:bg-yellow-600 @else bg-purple-500 hover:bg-purple-600 @endif text-white font-medium py-2.5 px-4 rounded-lg transition-colors duration-300 text-sm">
                                    View Services & Doctors
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </main>
</div>

<!-- MODAL FOR SERVICE DETAILS -->
<div id="serviceModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div>
                <h2 id="modalServiceName" class="text-2xl font-bold text-gray-800"></h2>
                <p id="modalServiceDesc" class="text-gray-600 mt-1"></p>
            </div>
            <button onclick="closeServiceModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                &times;
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 140px)">
            <!-- Categories Section - Side by Side -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Illness Categories</h3>
                <div id="modalCategories" class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2">
                    <!-- Categories will be populated here -->
                </div>
            </div>

            <!-- Available Doctors Section -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Available Doctors</h3>
                <div id="modalDoctors" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Doctors will be populated here -->
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-6 border-t bg-gray-50 flex justify-end">
            <button onclick="closeServiceModal()"
                    class="px-6 py-2.5 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors duration-300">
                Close
            </button>
        </div>
    </div>
</div>


<!-- MODAL FOR ADDING NEW SERVICE -->
<div id="addServiceModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-blue-50 rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-hidden">

        <div class="flex justify-between items-center p-6 border-b">
            <h2 class="text-2xl font-bold text-gray-800">Add Service</h2>
            <button onclick="closeAddServiceModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                &times;
            </button>
        </div>


        <!-- Modal Body - Add Service Form -->
        <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 140px)">
            <form id="addServiceForm" method="POST" action="{{ route('services.store') }}">
                @csrf
                <!-- Service Name -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="serviceName">
                        Service Name
                    </label>
                    <input
                        type="text"
                        id="serviceName"
                        name="service_name"
                        placeholder="Enter service name"
                        class="w-full border border-blue-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        value="{{ old('service_name') }}"
                        required>
                </div>

                <!-- Service Fee -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="serviceFee">
                        Service Fee
                    </label>
                    <input
                        type="text"
                        id="serviceFee"
                        name="service_fee"
                        placeholder="Enter fee amount"
                        class="w-full border border-blue-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        value="{{ old('service_fee') }}"
                        required>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="serviceDescription">
                        Description
                        <span class="text-gray-500 font-normal text-sm">(Optional)</span>
                    </label>
                    <textarea
                        id="serviceDescription"
                        name="description"
                        placeholder="Optional description"
                        rows="3"
                        class="w-full border border-blue-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description') }}</textarea>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="p-6 border-t bg-blue-50 flex justify-end gap-3">
            <button onclick="closeAddServiceModal()"
                    class="px-6 py-2.5 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors duration-300">
                Cancel
            </button>
            <button type="submit" form="addServiceForm"
                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-300">
                Save Service
            </button>
        </div>
    </div>
</div>

<style>

    /* Service card button colors */
    .bg-blue-500 { background-color: #719ed1; }
    .hover\:bg-blue-600:hover { background-color: #2563eb; }

    .bg-green-500 { background-color: #719ed1; }
    .hover\:bg-green-600:hover { background-color: #2563eb; }

    .bg-red-500 { background-color: #719ed1; }
    .hover\:bg-red-600:hover { background-color: #2563eb; }

    .bg-yellow-500 { background-color: #719ed1; }
    .hover\:bg-yellow-600:hover { background-color: #2563eb; }

    .bg-purple-500 { background-color: #719ed1; }
    .hover\:bg-purple-600:hover { background-color: #2563eb; }

    /* Icon background colors */
    .bg-blue-50 { background-color: #eff6ff; }
    .bg-green-50 { background-color: #f0fdf4; }
    .bg-red-50 { background-color: #fef2f2; }
    .bg-yellow-50 { background-color: #fefce8; }
    .bg-purple-50 { background-color: #faf5ff; }

    /* Icon colors */
    .text-blue-500 { color: #3b82f6; }
    .text-green-500 { color: #10b981; }
    .text-red-500 { color: #ef4444; }
    .text-yellow-500 { color: #eab308; }
    .text-purple-500 { color: #8b5cf6; }

    /* Modal animations */
    #serviceModal, #addServiceModal {
        animation: fadeIn 0.3s ease-out;
    }

    #serviceModal > div, #addServiceModal > div {
        animation: slideUp 0.3s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Category card styling - Compact 8 per line */
    #modalCategories {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 6px;
    }

    @media (min-width: 768px) {
        #modalCategories {
            grid-template-columns: repeat(6, 1fr);
        }
    }

    @media (min-width: 1024px) {
        #modalCategories {
            grid-template-columns: repeat(8, 1fr);
        }
    }

    #modalCategories div {
        transition: all 0.3s ease;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 6px 3px;
        text-align: center;
        min-height: 60px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    #modalCategories div:hover {
        transform: translateY(-1px);
        background: #f1f5f9;
        border-color: #3b82f6;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.1);
    }

    #modalCategories div i {
        font-size: 12px;
        margin-bottom: 4px;
    }

    #modalCategories div p {
        font-size: 10px;
        line-height: 1.1;
        margin: 0;
        padding: 0 1px;
        font-weight: 500;
    }

    /* Doctor card enhancements */
    #modalDoctors .bg-white {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
    }

    #modalDoctors .bg-white:hover {
        border-color: #3b82f6;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(59, 130, 246, 0.15);
    }

    /* Form input styling */
    #addServiceForm input,
    #addServiceForm textarea {
        font-size: 16px;
    }

    #addServiceForm input:focus,
    #addServiceForm textarea:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .sidebar {
            width: 220px;
        }

        main {
            padding: 16px;
        }

        .grid {
            gap: 16px;
        }

        .p-6 {
            padding: 16px;
        }

        .flex.gap-3 {
            flex-direction: column;
            width: 100%;
        }

        input[type="text"] {
            width: 100%;
        }

        button.bg-blue-600 {
            width: 100%;
        }

        #serviceModal > div,
        #addServiceModal > div {
            width: 95%;
            margin: 0 10px;
        }

        #modalCategories {
            grid-template-columns: repeat(4, 1fr);
            gap: 4px;
        }

        #modalCategories div {
            padding: 5px 2px;
            min-height: 55px;
        }

        #modalCategories div i {
            font-size: 11px;
            margin-bottom: 3px;
        }

        #modalCategories div p {
            font-size: 9px;
        }

        #modalDoctors {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .sidebar {
            width: 100%;
            min-height: auto;
            height: 60px;
            padding: 0;
            position: fixed;
            bottom: 0;
            z-index: 50;
        }

        .logo {
            display: none;
        }

        .sidebar nav {
            display: flex;
            height: 100%;
            align-items: center;
            justify-content: space-around;
            padding: 0 8px;
        }

        .sidebar nav a {
            flex-direction: column;
            padding: 8px;
            margin: 0;
            font-size: 11px;
            gap: 4px;
            border-radius: 6px;
            flex: 1;
            max-width: 70px;
        }

        .sidebar nav a i {
            font-size: 14px;
        }

        .logout-form {
            display: none;
        }

        main {
            padding-bottom: 70px;
        }

        .grid {
            grid-template-columns: 1fr;
        }

        #serviceModal,
        #addServiceModal {
            padding: 10px;
        }

        #serviceModal > div,
        #addServiceModal > div {
            width: 100%;
            max-height: 85vh;
        }

        #modalCategories {
            grid-template-columns: repeat(3, 1fr);
        }

        #modalCategories div {
            min-height: 50px;
        }

        #modalCategories div p {
            font-size: 8px;
        }

        .max-w-4xl {
            max-width: 95%;
        }
    }
</style>

@push('scripts')
@push('scripts')
<script>
    /* ===== Clinic Data from Laravel Backend ===== */
    // Convert PHP data to JavaScript object
    const clinicData = @json($clinicServices ?? []);
    
    console.log('Clinic data loaded:', clinicData); // Debug log

    /* ===== Open Service Modal ===== */
    function openServiceModal(index) {
        console.log('Opening modal for index:', index, 'Data:', clinicData[index]);
        
        const data = clinicData[index];
        if (!data) {
            console.error('Service not found at index:', index);
            alert('Service data not found. Please try again.');
            return;
        }

        // Populate modal header data
        document.getElementById('modalServiceName').textContent = data.name || 'Service';
        document.getElementById('modalServiceDesc').textContent = data.description || 'Medical service';

        // Populate categories
        const categoriesList = document.getElementById('modalCategories');
        if (categoriesList) {
            if (data.illnesses && data.illnesses.length > 0) {
                categoriesList.innerHTML = data.illnesses.map(illness => `
                    <div class="bg-gray-50 border border-gray-200 rounded-md p-1 text-center hover:bg-blue-50 hover:border-blue-200 transition-colors duration-200">
                        <i class="fa-solid fa-circle-info text-blue-500 text-xs mb-1"></i>
                        <p class="text-gray-700 text-xs font-medium">${illness}</p>
                    </div>
                `).join('');
            } else {
                categoriesList.innerHTML = `
                    <div class="col-span-full text-center py-4">
                        <p class="text-gray-500">No specific illness categories listed</p>
                    </div>
                `;
            }
        }

        // Populate doctors
        const doctorsList = document.getElementById('modalDoctors');
        if (doctorsList) {
            if (!data.doctors || data.doctors.length === 0) {
                doctorsList.innerHTML = `
                    <div class="col-span-full text-center py-8">
                        <i class="fa-solid fa-user-md text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">No doctors available for this service</p>
                        <p class="text-gray-400 text-sm mt-2">Please check back later or contact the clinic</p>
                    </div>
                `;
            } else {
                doctorsList.innerHTML = data.doctors.map(doctor => `
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
                        <div class="p-5">
                            <!-- Doctor Avatar and Basic Info -->
                            <div class="flex items-center mb-4">
                                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                    <i class="fa-solid fa-user-md text-blue-600 text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-800 text-lg mb-1">${doctor.name || 'Doctor'}</h4>
                                    <p class="text-blue-600 font-medium">${doctor.speciality || 'Medical Specialist'}</p>
                                </div>
                            </div>

                            <!-- Doctor Details -->
                            <div class="space-y-3 mb-4">
                                <div class="flex items-center text-gray-700">
                                    <i class="fa-solid fa-graduation-cap text-gray-400 mr-2 w-5"></i>
                                    <span class="text-sm">${doctor.qualification || 'MD Specialist'}</span>
                                </div>
                                <div class="flex items-center text-gray-700">
                                    <i class="fa-solid fa-clock text-gray-400 mr-2 w-5"></i>
                                    <span class="text-sm">${doctor.availability || 'Mon-Fri: 9AM-5PM'}</span>
                                </div>
                                <div class="flex items-center text-gray-700">
                                    <i class="fa-solid fa-star text-yellow-400 mr-2 w-5"></i>
                                    <span class="text-sm">${doctor.rating || '4.5'}/5 Rating</span>
                                </div>
                                <div class="flex items-center text-gray-700">
                                    <i class="fa-solid fa-briefcase text-gray-400 mr-2 w-5"></i>
                                    <span class="text-sm">${doctor.experience || 'Experience not specified'}</span>
                                </div>
                            </div>

                            <!-- Fee and Action -->
                            <div class="pt-4 border-t border-gray-100">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-sm text-gray-500">Consultation Fee</p>
                                        <p class="text-green-600 font-bold text-lg">${doctor.fee || 'Contact for price'}</p>
                                    </div>
                                    <button onclick="bookAppointment(${doctor.id || 0}, '${doctor.name ? doctor.name.replace(/'/g, "\\'") : 'Doctor'}', '${data.name ? data.name.replace(/'/g, "\\'") : 'Service'}')"
                                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-300 text-sm">
                                        Book Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
            }
        }

        // Show modal
        document.getElementById('serviceModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    /* ===== Close Service Modal ===== */
    function closeServiceModal() {
        document.getElementById('serviceModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    /* ===== Book Appointment ===== */
    function bookAppointment(doctorId, doctorName, serviceName) {
        // Create a confirmation message
        const confirmation = `Booking Details:\n\n` +
                           `👨‍⚕️ Doctor: ${doctorName}\n` +
                           `🏥 Service: ${serviceName}\n` +
                           `📅 Date: ${new Date().toLocaleDateString()}\n\n` +
                           `Proceed to appointment form?`;
        
        if (confirm(confirmation)) {
            // In real application, redirect to appointment booking page
            // window.location.href = `/appointments/book?doctor_id=${doctorId}&service=${encodeURIComponent(serviceName)}`;
            
            // For demo, show success message
            alert(`✅ Appointment request sent to ${doctorName}\n\nYou will be contacted to confirm the appointment.`);
            
            // Close modal after booking
            closeServiceModal();
        }
    }

    /* ===== Open Add Service Modal ===== */
    function openAddServiceModal() {
        // Reset form
        const form = document.getElementById('addServiceForm');
        if (form) form.reset();
        
        // Show modal
        document.getElementById('addServiceModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    /* ===== Close Add Service Modal ===== */
    function closeAddServiceModal() {
        document.getElementById('addServiceModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Event listeners for modal close on outside click and ESC key
    document.addEventListener('DOMContentLoaded', function() {
        // Close modals when clicking outside
        const serviceModal = document.getElementById('serviceModal');
        const addServiceModal = document.getElementById('addServiceModal');
        
        if (serviceModal) {
            serviceModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeServiceModal();
                }
            });
        }
        
        if (addServiceModal) {
            addServiceModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeAddServiceModal();
                }
            });
        }

        // Close modals with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeServiceModal();
                closeAddServiceModal();
            }
        });
    });
</script>
@endpush
@endsection