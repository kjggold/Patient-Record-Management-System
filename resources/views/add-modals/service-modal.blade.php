 <!-- ADD SERVICE MODAL -->
    <div id="addServiceModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-blue-50 rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-hidden">
            <div class="flex justify-between items-center p-6 border-b">
                <h2 class="text-2xl font-bold text-gray-800">Add Service</h2>
                <button onclick="openAddServiceModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>

            <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 140px)">
                <form id="addServiceForm" method="POST" action="{{ route('services.store') }}">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="serviceName">Service Name</label>
                        <input type="text" id="serviceName" name="service_name" placeholder="Enter service name"
                            class="w-full border border-blue-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            value="{{ old('service_name') }}" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="serviceFee">Service Fee</label>
                        <input type="text" id="serviceFee" name="service_fee" placeholder="Enter fee amount"
                            class="w-full border border-blue-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            value="{{ old('service_fee') }}" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="serviceDescription">Description
                            <span class="text-gray-500 font-normal text-sm">(Optional)</span></label>
                        <textarea id="serviceDescription" name="description" placeholder="Optional description" rows="3"
                            class="w-full border border-blue-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description') }}</textarea>
                    </div>
                </form>
            </div>

            <div class="p-6 border-t bg-blue-50 flex justify-end gap-3">
                <button onclick="closeAddServiceModal()"
                    class="px-6 py-2.5 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors duration-300">Cancel</button>
                <button type="submit" form="addServiceForm"
                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-300">Save
                    Service</button>
            </div>
        </div>
    </div>
    <style>
        /* Service card button colors */
        .bg-blue-500 {
            background-color: #719ed1;
        }

        .hover\:bg-blue-600:hover {
            background-color: #2563eb;
        }

        .bg-green-500 {
            background-color: #719ed1;
        }

        .hover\:bg-green-600:hover {
            background-color: #2563eb;
        }

        .bg-red-500 {
            background-color: #719ed1;
        }

        .hover\:bg-red-600:hover {
            background-color: #2563eb;
        }

        .bg-yellow-500 {
            background-color: #719ed1;
        }

        .hover\:bg-yellow-600:hover {
            background-color: #2563eb;
        }

        .bg-purple-500 {
            background-color: #719ed1;
        }

        .hover\:bg-purple-600:hover {
            background-color: #2563eb;
        }

        /* Icon background colors */
        .bg-blue-50 {
            background-color: #eff6ff;
        }

        .bg-green-50 {
            background-color: #f0fdf4;
        }

        .bg-red-50 {
            background-color: #fef2f2;
        }

        .bg-yellow-50 {
            background-color: #fefce8;
        }

        .bg-purple-50 {
            background-color: #faf5ff;
        }

        /* Icon colors */
        .text-blue-500 {
            color: #3b82f6;
        }

        .text-green-500 {
            color: #10b981;
        }

        .text-red-500 {
            color: #ef4444;
        }

        .text-yellow-500 {
            color: #eab308;
        }

        .text-purple-500 {
            color: #8b5cf6;
        }

        /* Modal animations */
        #serviceModal,
        #addServiceModal {
            animation: fadeIn 0.3s ease-out;
        }

        #serviceModal>div,
        #addServiceModal>div {
            animation: slideUp 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
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

            #serviceModal>div,
            #addServiceModal>div {
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

            #serviceModal,
            #addServiceModal {
                padding: 10px;
            }

            #serviceModal>div,
            #addServiceModal>div {
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