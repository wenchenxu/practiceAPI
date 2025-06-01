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
        const modal = document.getElementById('addVehicleModal');
        const openModalButton = document.getElementById('openModalButton');
        const closeModalButton = document.getElementById('closeModalButton');

        function showModal() {
            modal.classList.remove('hidden');
            // Optional: focus the first input field in the modal
            // const firstInput = modal.querySelector('input, select');
            // if (firstInput) {
            //    firstInput.focus();
            // }
        }

        function hideModal() {
            modal.classList.add('hidden');
        }

        openModalButton.addEventListener('click', showModal);
        closeModalButton.addEventListener('click', hideModal);

        // Close modal if escape key is pressed
        window.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                hideModal();
            }
        });

        // Close modal if overlay is clicked (optional, a bit more complex to get right with just the overlay)
        // modal.addEventListener('click', function(event) {
        //    if (event.target === modal) { // Ensures click is on overlay itself, not children
        //        hideModal();
        //    }
        // });

        // Logic to re-open modal if there are validation errors from Laravel
        // $errors->any() checks if there are any errors in the session.
        // $errors->hasBag('default') is more specific to general form errors.
        @if($errors->any() && old('form_submitted_from_modal')) // We'll need to add a hidden field to the form
            showModal();
        @endif

        // --- New Script for Delete Confirmation Modal ---
        const deleteModal = document.getElementById('deleteConfirmModal');
        const confirmDeleteButton = document.getElementById('confirmDeleteButton');
        const cancelDeleteButton = document.getElementById('cancelDeleteButton');
        const deleteModalMessage = document.getElementById('deleteModalMessage');
        let formToSubmit = null; // Variable to store the form that should be submitted

        function showDeleteModal(formId, vehicleInfo) {
            formToSubmit = document.getElementById(formId);
            if (vehicleInfo) {
                deleteModalMessage.textContent = `Are you sure you want to delete vehicle: ${vehicleInfo}? This action cannot be undone.`;
            } else {
                deleteModalMessage.textContent = 'Are you sure you want to delete this vehicle? This action cannot be undone.';
            }
            if(deleteModal) deleteModal.classList.remove('hidden');
        }

        function hideDeleteModal() {
            formToSubmit = null; // Clear the form reference
            if(deleteModal) deleteModal.classList.add('hidden');
        }

        // Add event listeners to all delete buttons
        document.querySelectorAll('.delete-vehicle-button').forEach(button => {
            button.addEventListener('click', function() {
                const formId = this.dataset.formId;
                const vehicleInfo = this.dataset.vehicleInfo;
                showDeleteModal(formId, vehicleInfo);
            });
        });

        if(confirmDeleteButton) {
            confirmDeleteButton.addEventListener('click', function() {
                if (formToSubmit) {
                    formToSubmit.submit();
                }
                hideDeleteModal();
            });
        }

        if(cancelDeleteButton) {
            cancelDeleteButton.addEventListener('click', hideDeleteModal);
        }

        // Optional: Close delete modal if clicking outside of it (on the overlay)
        if(deleteModal) {
            deleteModal.addEventListener('click', function(event) {
                if (event.target === deleteModal) { // Check if the click is on the overlay itself
                    hideDeleteModal();
                }
            });
        }
    </script>

</body>
</html>