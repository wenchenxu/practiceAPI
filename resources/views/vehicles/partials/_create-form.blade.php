{{-- resources/views/vehicles/partials/_create-form.blade.php --}}
<form action="{{ route('vehicles.store') }}" method="POST" class="space-y-6">
    @csrf
    <input type="hidden" name="form_submitted_from_modal" value="1">

    {{-- License Number --}}
    <div>
        <label for="license_number" class="block text-sm font-medium text-slate-700 mb-1">License Number:</label>
        <input type="text" name="license_number" id="license_number" value="{{ old('license_number') }}"
               class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400
                      focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
               placeholder="e.g., ABC 123">
        @error('license_number')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Driver Name --}}
    <div>
        <label for="driver_name" class="block text-sm font-medium text-slate-700 mb-1">Driver Name:</label>
        <input type="text" name="driver_name" id="driver_name" value="{{ old('driver_name') }}"
               class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400
                      focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
               placeholder="e.g., John Doe">
        @error('driver_name')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Driver Phone Number --}}
    <div>
        <label for="driver_phone_number" class="block text-sm font-medium text-slate-700 mb-1">Driver Phone Number:</label>
        <input type="text" name="driver_phone_number" id="driver_phone_number" value="{{ old('driver_phone_number') }}"
               class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400
                      focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
               placeholder="e.g., (555) 123-4567">
        @error('driver_phone_number')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Shop Entry Time Section --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
        <div>
            <label for="shop_entry_date" class="block text-sm font-medium text-slate-700 mb-1">Shop Entry Date:</label>
            <input type="date" name="shop_entry_date" id="shop_entry_date" value="{{ old('shop_entry_date', date('Y-m-d')) }}"
                   class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm
                          focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
            @error('shop_entry_date')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="shop_entry_hour" class="block text-sm font-medium text-slate-700 mb-1">Hour (24h):</label>
            <select name="shop_entry_hour" id="shop_entry_hour"
                    class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm
                           focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                <option value="">-- Select Hour --</option>
                @for ($i = 0; $i < 24; $i++)
                    @php $hourValue = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                    <option value="{{ $hourValue }}" @selected(old('shop_entry_hour') == $hourValue)>
                        {{ $hourValue }}
                    </option>
                @endfor
            </select>
            @error('shop_entry_hour')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="shop_entry_minute" class="block text-sm font-medium text-slate-700 mb-1">Minute:</label>
            <select name="shop_entry_minute" id="shop_entry_minute"
                    class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm
                           focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                <option value="">-- Select Minute --</option>
                <option value="00" @selected(old('shop_entry_minute') == '00')>:00</option>
                <option value="15" @selected(old('shop_entry_minute') == '15')>:15</option>
                <option value="30" @selected(old('shop_entry_minute') == '30')>:30</option>
                <option value="45" @selected(old('shop_entry_minute') == '45')>:45</option>
            </select>
            @error('shop_entry_minute')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Form Actions (Submit Button) --}}
    <div class="pt-4 flex justify-end"> {{-- Aligns button to the right --}}
        <button type="submit"
                class="bg-sky-600 hover:bg-sky-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md
                       focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition duration-150 ease-in-out">
            Add Vehicle
        </button>
    </div>
</form>