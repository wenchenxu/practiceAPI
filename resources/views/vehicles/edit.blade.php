<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js']) 
    <title>Edit Vehicle - {{ $vehicle->license_number }}</title>
</head>
<body>
    <div class="container">
        <h1>Edit Vehicle: {{ $vehicle->license_number }}</h1>

        <form action="{{ route('vehicles.update', $vehicle->id) }}" method="POST">
            @csrf @method('PUT') <div>
                <label for="license_number">License Number:</label>
                <input type="text" name="license_number" id="license_number" value="{{ old('license_number', $vehicle->license_number) }}">
                @error('license_number')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="driver_name">Driver Name:</label>
                <input type="text" name="driver_name" id="driver_name" value="{{ old('driver_name', $vehicle->driver_name) }}">
                @error('driver_name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="driver_phone_number">Driver Phone Number:</label>
                <input type="text" name="driver_phone_number" id="driver_phone_number" value="{{ old('driver_phone_number', $vehicle->driver_phone_number) }}">
                @error('driver_phone_number')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            @php
                // Prepare default/existing values for the edit form
                $entry_date = old('shop_entry_date', $vehicle->shop_entry_time ? $vehicle->shop_entry_time->format('Y-m-d') : date('Y-m-d'));
                // Get current hour, default to empty string if no shop_entry_time (so placeholder is selected)
                $currentHour = $vehicle->shop_entry_time ? $vehicle->shop_entry_time->format('H') : '';
                $selectedHour = old('shop_entry_hour', $currentHour);

                // Get current minute, default to empty string if no shop_entry_time
                $currentMinute = $vehicle->shop_entry_time ? $vehicle->shop_entry_time->format('i') : '';
                $selectedMinute = old('shop_entry_minute', $currentMinute);
            @endphp

            <div>
                <label for="shop_entry_date">Shop Entry Date:</label>
                <input type="date" name="shop_entry_date" id="shop_entry_date" value="{{ $entry_date }}" class="form-input">
                @error('shop_entry_date')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="shop_entry_hour">Shop Entry Hour (24h):</label>
                <select name="shop_entry_hour" id="shop_entry_hour" class="form-select">
                    <option value="">-- Select Hour --</option> {{-- Placeholder --}}
                    @for ($i = 0; $i <= 24; $i++)
                        @php $hourValue = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                        <option value="{{ $hourValue }}" @selected($selectedHour == $hourValue)>
                            {{ $hourValue }}
                        </option>
                    @endfor
                </select>
                @error('shop_entry_hour')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="shop_entry_minute">Shop Entry Minute:</label>
                <select name="shop_entry_minute" id="shop_entry_minute" class="form-select">
                    <option value="">-- Select Minute --</option> {{-- Placeholder --}}
                    <option value="00" @selected($selectedMinute == '00')>:00</option>
                    <option value="15" @selected($selectedMinute == '15')>:15</option>
                    <option value="30" @selected($selectedMinute == '30')>:30</option>
                    <option value="45" @selected($selectedMinute == '45')>:45</option>
                </select>
                @error('shop_entry_minute')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <button type="submit" class="button button-success">Update Vehicle</button>
            </div>
        </form>

        <a href="{{ route('vehicles.index') }}" class="back-link">Back to Vehicle List</a>
    </div>
</body>
</html>