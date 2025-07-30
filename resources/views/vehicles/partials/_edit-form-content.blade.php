{{-- resources/views/vehicles/partials/_edit-form-content.blade.php --}}
{{-- The form tag itself will be part of the modal in index.blade.php --}}
@csrf
@method('PUT') {{-- Important for updates --}}
<input type="hidden" name="form_submitted_from_modal" value="1"> {{-- For re-opening on error --}}
<input type="hidden" name="editing_vehicle_id" id="edit_vehicle_id" value="">  {{-- Add this line --}}

{{-- License Number --}}
<div>
    <label for="edit_license_number" class="block text-sm font-medium text-slate-700 mb-1">License Number:</label>
    <input type="text" name="license_number" id="edit_license_number"
           class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400
                  focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
    {{-- Error display will be handled by JS or if modal re-opens with errors --}}
    <div data-error-for="license_number" class="mt-1 text-xs text-red-600"></div>
</div>

{{-- Driver Name --}}
<div>
    <label for="edit_driver_name" class="block text-sm font-medium text-slate-700 mb-1">Driver Name:</label>
    <input type="text" name="driver_name" id="edit_driver_name"
           class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400
                  focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
    <div data-error-for="driver_name" class="mt-1 text-xs text-red-600"></div>
</div>

{{-- Driver Phone Number --}}
<div>
    <label for="edit_driver_phone_number" class="block text-sm font-medium text-slate-700 mb-1">Driver Phone Number:</label>
    <input type="text" name="driver_phone_number" id="edit_driver_phone_number"
           class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400
                  focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
    <div data-error-for="driver_phone_number" class="mt-1 text-xs text-red-600"></div>
</div>

{{-- Shop Entry Time Section --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
    <div>
        <label for="edit_shop_entry_date" class="block text-sm font-medium text-slate-700 mb-1">Shop Entry Date:</label>
        <input type="date" name="shop_entry_date" id="edit_shop_entry_date"
               class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm
                      focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
        <div data-error-for="shop_entry_date" class="mt-1 text-xs text-red-600"></div>
    </div>
    <div>
        <label for="edit_shop_entry_hour" class="block text-sm font-medium text-slate-700 mb-1">Hour (24h):</label>
        <select name="shop_entry_hour" id="edit_shop_entry_hour"
                class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
            <option value="">-- Select Hour --</option>
            @for ($i = 0; $i < 24; $i++)
                @php $hourValue = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                <option value="{{ $hourValue }}">{{ $hourValue }}</option>
            @endfor
        </select>
        <div data-error-for="shop_entry_hour" class="mt-1 text-xs text-red-600"></div>
    </div>
    <div>
        <label for="edit_shop_entry_minute" class="block text-sm font-medium text-slate-700 mb-1">Minute:</label>
        <select name="shop_entry_minute" id="edit_shop_entry_minute"
                class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
            <option value="">-- Select Minute --</option>
            <option value="00">:00</option>
            <option value="15">:15</option>
            <option value="30">:30</option>
            <option value="45">:45</option>
        </select>
        <div data-error-for="shop_entry_minute" class="mt-1 text-xs text-red-600"></div>
    </div>
</div>

{{-- Form Actions (Submit Button) --}}
<div class="pt-6 flex justify-end">
    <button type="button" id="cancelEditButton" class="mr-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2 px-6 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition duration-150 ease-in-out">
        Cancel
    </button>
    <button type="submit"
            class="bg-sky-600 hover:bg-sky-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md
                   focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition duration-150 ease-in-out">
        Update Vehicle
    </button>
</div>