<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Vehicle Dashboard</title>
</head>
<body class="flex h-full">

    {{-- Sidebar --}}
    <aside class="w-64 bg-slate-800 text-slate-100 p-6 space-y-6 fixed top-0 left-0 h-screen overflow-y-auto">
        <div class="text-2xl font-semibold text-white">Company Vehicles</div>
        <nav class="space-y-2">
            <a href="{{ route('vehicles.index') }}" class="flex items-center space-x-2 py-2.5 px-4 rounded-md transition duration-200 bg-slate-700 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 0h6m0 0v6m0-6L10 14"></path></svg>
                <span>Vehicles</span>
            </a>
            <a href="#" class="flex items-center space-x-2 py-2.5 px-4 rounded-md transition duration-200 hover:bg-slate-700 hover:text-white">
                <svg class="w-6 h-6 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826 3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span>Settings (Placeholder)</span>
            </a>
        </nav>
    </aside>

    {{-- Main Content Area --}}
    <main class="flex-grow p-6 md:p-10 ml-64 overflow-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-slate-800">Vehicle Dashboard</h1>
            {{-- "Add New Vehicle" Button to open the modal --}}
            <button id="openModalButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-150 ease-in-out">
                Add New Vehicle
            </button>
        </div>

        {{-- Success Messages --}}
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow" role="alert">
                <p class="font-bold">Success!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- Vehicle List Table Section (Card) --}}
        <div class="bg-white p-6 md:p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold text-slate-700 mb-6">Vehicle List</h2>
            <div class="overflow-x-auto">
                @include('vehicles.partials._vehicles-table') {{-- Assuming you renamed the partial with an underscore --}}
            </div>
            <div class="mt-6">
                {{ $vehicles->links() }}
            </div>
        </div>
    </main>

    {{-- Modal for Adding New Vehicle --}}
    <div id="addVehicleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center hidden"> {{-- hidden initially --}}
        <div class="relative mx-auto p-8 border w-full max-w-2xl shadow-lg rounded-xl bg-white space-y-6">
            {{-- Modal Header --}}
            <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                <h3 class="text-2xl font-semibold text-gray-800">Add New Vehicle</h3>
                <button id="closeModalButton" class="text-gray-400 hover:text-gray-600 transition duration-150 ease-in-out">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            {{-- Modal Body - Include the form partial here --}}
            <div class="mt-5">
                @include('vehicles.partials._create-form') {{-- Assuming you renamed the partial with an underscore --}}
            </div>
        </div>
    </div>

    {{-- Modal for Editing Vehicle --}}
    <div id="editVehicleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center hidden z-40"> {{-- hidden initially, z-index lower than delete modal if needed --}}
        <div class="relative mx-auto p-8 border w-full max-w-2xl shadow-lg rounded-xl bg-white space-y-6">
            {{-- Modal Header --}}
            <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                <h3 class="text-2xl font-semibold text-gray-800">Edit Vehicle</h3>
                <button id="closeEditModalButton" class="text-gray-400 hover:text-gray-600 transition duration-150 ease-in-out">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            {{-- Modal Body - Form will be included here --}}
            <div class="mt-5">
                <form id="editVehicleForm" method="POST" class="space-y-6">
                    {{-- The content from _edit-form-content.blade.php will be injected here by JS or included if static --}}
                    {{-- For simplicity now, let's assume we'll include the partial: --}}
                    @include('vehicles.partials._edit-form-content')
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteConfirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full flex items-center justify-center hidden z-50"> {{-- hidden and high z-index --}}
        <div class="relative mx-auto p-6 md:p-8 border w-full max-w-md shadow-xl rounded-xl bg-white space-y-4">
            {{-- Modal Header --}}
            <div class="flex flex-col items-center text-center">
                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10 mb-4">
                    {{-- Heroicon: exclamation-triangle --}}
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-800" id="deleteModalTitle">Confirm Deletion</h3>
            </div>

            {{-- Modal Body --}}
            <div class="mt-2 text-center">
                <p class="text-sm text-gray-600" id="deleteModalMessage">
                    Are you sure you want to delete this vehicle? This action cannot be undone.
                </p>
            </div>

            {{-- Modal Footer (Buttons) --}}
            <div class="mt-6 sm:mt-8 sm:flex sm:flex-row-reverse sm:gap-3">
                <button id="confirmDeleteButton" type="button"
                        class="inline-flex w-full justify-center rounded-md bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto">
                    Delete
                </button>
                <button id="cancelDeleteButton" type="button"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        // This part is fine outside, as Blade renders it before the script runs.
        const validationErrors = {!! json_encode($errors->getMessages()) !!};
        const oldInput = {!! json_encode(session()->getOldInput(null)) !!};
        const oldEditingVehicleId = '{{ old("editing_vehicle_id") }}';

        // --- Wait for the DOM to be fully loaded before trying to find or interact with elements ---
        document.addEventListener('DOMContentLoaded', function() {

            // --- GET ALL MODAL & FORM ELEMENTS ---
            // Now that the DOM is loaded, these will reliably find the elements.
            const addModal = document.getElementById('addVehicleModal');
            const openAddModalButton = document.getElementById('openModalButton');
            const closeAddModalButton = document.getElementById('closeModalButton');
            const editModal = document.getElementById('editVehicleModal');
            const closeEditModalButton = document.getElementById('closeEditModalButton');
            const cancelEditFormButton = document.getElementById('cancelEditButton');
            const editVehicleForm = document.getElementById('editVehicleForm');
            const deleteModal = document.getElementById('deleteConfirmModal');
            const confirmDeleteButton = document.getElementById('confirmDeleteButton');
            const cancelDeleteButton = document.getElementById('cancelDeleteButton');
            const deleteModalMessage = document.getElementById('deleteModalMessage');
            let formToSubmitForDelete = null;

            // --- MODAL CONTROL FUNCTIONS ---
            function showAddModal() { if (addModal) addModal.classList.remove('hidden'); }
            function hideAddModal() { if (addModal) addModal.classList.add('hidden'); }
            function showEditModal() { if (editModal) editModal.classList.remove('hidden'); }
            function hideEditModal() {
                if (editModal) {
                    editModal.classList.add('hidden');
                    document.querySelectorAll('#editVehicleForm [data-error-for]').forEach(el => el.textContent = '');
                }
            }
            function showDeleteModal(formId, vehicleInfo) {
                formToSubmitForDelete = document.getElementById(formId);
                if (vehicleInfo) {
                    deleteModalMessage.textContent = `Are you sure you want to delete vehicle: ${vehicleInfo}? This action cannot be undone.`;
                } else {
                    deleteModalMessage.textContent = 'Are you sure you want to delete this vehicle? This action cannot be undone.';
                }
                if (deleteModal) deleteModal.classList.remove('hidden');
            }
            function hideDeleteModal() {
                formToSubmitForDelete = null;
                if (deleteModal) deleteModal.classList.add('hidden');
            }

            // --- SETUP EVENT LISTENERS ---

            // -- Add Modal Listeners --
            if(openAddModalButton) openAddModalButton.addEventListener('click', showAddModal);
            if(closeAddModalButton) closeAddModalButton.addEventListener('click', hideAddModal);

            // -- Edit Modal Listeners --
            if(closeEditModalButton) closeEditModalButton.addEventListener('click', hideEditModal);
            if(cancelEditFormButton) cancelEditFormButton.addEventListener('click', hideEditModal);
            
            document.querySelectorAll('.open-edit-modal-button').forEach(button => {
                button.addEventListener('click', function() {
                    try {
                        document.querySelectorAll('#editVehicleForm [data-error-for]').forEach(el => el.textContent = '');
                        const vehicleData = JSON.parse(this.dataset.vehicle);
                        const updateUrl = this.dataset.updateUrl;
                        editVehicleForm.action = updateUrl;
                        document.getElementById('edit_vehicle_id').value = vehicleData.id;
                        const useOldInput = oldEditingVehicleId && oldEditingVehicleId == vehicleData.id;

                        // --- LOGIC using the new local_string attribute ---
                        let dateToUse = ''; let hourToUse = ''; let minuteToUse = '';

                        if (useOldInput) {
                            dateToUse = oldInput.shop_entry_date || '';
                            hourToUse = oldInput.shop_entry_hour || '';
                            minuteToUse = oldInput.shop_entry_minute || '';
                        } 
                        else if (vehicleData.shop_entry_time_local_string && typeof vehicleData.shop_entry_time_local_string === 'string') {
                            const parts = vehicleData.shop_entry_time_local_string.split(' ');
                            dateToUse = parts[0] || '';
                            const timePart = parts[1] || '';
                            if (timePart.includes(':')) {
                                const timeParts = timePart.split(':');
                                hourToUse = timeParts[0] || '';
                                minuteToUse = timeParts[1] || '';
                            }
                        }

                        document.getElementById('edit_license_number').value = useOldInput ? oldInput.license_number : vehicleData.license_number || '';
                        document.getElementById('edit_driver_name').value = useOldInput ? oldInput.driver_name : vehicleData.driver_name || '';
                        document.getElementById('edit_driver_phone_number').value = useOldInput ? oldInput.driver_phone_number : vehicleData.driver_phone_number || '';
                        document.getElementById('edit_shop_entry_date').value = dateToUse;
                        document.getElementById('edit_shop_entry_hour').value = hourToUse;
                        document.getElementById('edit_shop_entry_minute').value = minuteToUse;

                        if (useOldInput) {
                            for (const field in validationErrors) {
                                const errorElement = document.querySelector(`#editVehicleForm [data-error-for="${field}"]`);
                                if (errorElement) { errorElement.textContent = validationErrors[field][0]; }
                            }
                        }
                        showEditModal();
                    } catch (e) {
                        console.error('An error occurred inside the edit click handler:', e);
                    }
                });
            });

            // -- Delete Modal Listeners --
            document.querySelectorAll('.delete-vehicle-button').forEach(button => {
                button.addEventListener('click', function() {
                    showDeleteModal(this.dataset.formId, this.dataset.vehicleInfo);
                });
            });
            if(confirmDeleteButton) {
                confirmDeleteButton.addEventListener('click', function() {
                    if (formToSubmitForDelete) formToSubmitForDelete.submit();
                    hideDeleteModal();
                });
            }
            if(cancelDeleteButton) cancelDeleteButton.addEventListener('click', hideDeleteModal);

            // -- Global Listeners --
            window.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    if(addModal && !addModal.classList.contains('hidden')) hideAddModal();
                    if(editModal && !editModal.classList.contains('hidden')) hideEditModal();
                    if(deleteModal && !deleteModal.classList.contains('hidden')) hideDeleteModal();
                }
            });

            // --- LOGIC TO RE-OPEN MODALS ON VALIDATION ERROR ---
            if (oldInput && oldInput.form_submitted_from_modal) {
                if (oldEditingVehicleId) {
                    const failedEditButton = document.querySelector(`.open-edit-modal-button[data-update-url*='/${oldEditingVehicleId}']`);
                    if (failedEditButton) {
                        failedEditButton.click();
                    }
                } else {
                    document.querySelector('#addVehicleModal [name="license_number"]').value = oldInput.license_number || '';
                    document.querySelector('#addVehicleModal [name="driver_name"]').value = oldInput.driver_name || '';
                    document.querySelector('#addVehicleModal [name="driver_phone_number"]').value = oldInput.driver_phone_number || '';
                    document.querySelector('#addVehicleModal [name="shop_entry_date"]').value = oldInput.shop_entry_date || '';
                    document.querySelector('#addVehicleModal [name="shop_entry_hour"]').value = oldInput.shop_entry_hour || '';
                    document.querySelector('#addVehicleModal [name="shop_entry_minute"]').value = oldInput.shop_entry_minute || '';
                    showAddModal();
                }
            }
        });
    </script>

</body>
</html>