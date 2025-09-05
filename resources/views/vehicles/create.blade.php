@extends('layouts.app')

@section('title', 'Add Vehicle')

@section('content')

<form method="POST" action="{{ route('vehicles.store') }}" class="space-y-4">
  @csrf

  <div>
    <label class="block text-sm font-medium">License Number *</label>
    <input name="license_number" value="{{ old('license_number') }}" class="border rounded w-full p-2" required>
    @error('license_number') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-medium">Driver Name (optional)</label>
      <input name="driver_name" value="{{ old('driver_name') }}" class="border rounded w-full p-2">
      @error('driver_name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
    <div>
      <label class="block text-sm font-medium">Driver Phone (optional)</label>
      <input name="driver_phone_number" value="{{ old('driver_phone_number') }}" class="border rounded w-full p-2">
      @error('driver_phone_number') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
  </div>

  <div>
    <label class="block text-sm font-medium">Shop Entry (optional)</label>
    <div class="grid grid-cols-3 gap-2">
      <input type="date" name="shop_entry_date" value="{{ old('shop_entry_date') }}" class="border rounded p-2">
      <input type="number" name="shop_entry_hour" min="0" max="23" value="{{ old('shop_entry_hour') }}" placeholder="HH" class="border rounded p-2">
      <input type="number" name="shop_entry_minute" min="0" max="59" value="{{ old('shop_entry_minute') }}" placeholder="MM" class="border rounded p-2">
    </div>
    @error('shop_entry_date') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    @error('shop_entry_hour') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    @error('shop_entry_minute') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
  </div>

  <div class="mt-4">
    <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
    <a href="{{ route('vehicles.index') }}" class="ml-3 text-gray-600">Cancel</a>
  </div>
</form>
@endsection
