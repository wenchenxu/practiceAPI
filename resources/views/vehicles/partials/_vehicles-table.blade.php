{{-- resources/views/vehicles/partials/_vehicles-table.blade.php --}}
<table class="min-w-full divide-y divide-slate-300">
    <thead class="bg-slate-50">
        <tr>
            <th scope="col" class="py-3.5 pl-4 pr-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider sm:pl-6">Edit</th>
            <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">ID</th>
            <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">License Plate</th>
            <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Driver Name</th>
            <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Driver Phone</th>
            <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Shop Entry</th>
            <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Created At</th>
            <th scope="col" class="py-3.5 pl-3 pr-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider sm:pr-6">Delete</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-slate-200 bg-white">
        @forelse ($vehicles as $vehicle)
            <tr class="hover:bg-slate-50 transition-colors duration-150 ease-in-out">
                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-center font-medium text-slate-900 sm:pl-6">
                    <a href="{{ route('vehicles.edit', $vehicle->id) }}" title="Edit Vehicle" class="text-sky-600 hover:text-sky-800">
                        {{-- Heroicon: pencil-square --}}
                        <svg class="w-5 h-5 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                          <path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                          <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                        </svg>
                    </a>
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600">{{ $vehicle->id }}</td>
                <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-slate-800">{{ $vehicle->license_number }}</td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600">{{ $vehicle->driver_name }}</td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600">{{ $vehicle->driver_phone_number ?? 'N/A' }}</td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600">{{ $vehicle->shop_entry_time ? $vehicle->shop_entry_time->format('Y-m-d H:i') : 'N/A' }}</td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">{{ $vehicle->created_at->format('Y-m-d H:i') }}</td>
                <td class="whitespace-nowrap py-4 pl-3 pr-4 text-sm text-center font-medium sm:pr-6">
                    {{-- Give the form a unique ID --}}
                    <form id="deleteForm-{{ $vehicle->id }}" action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        {{-- Add class and data attribute to the button --}}
                        <button type="button" {{-- Change type to "button" to prevent default submission --}}
                                data-form-id="deleteForm-{{ $vehicle->id }}"
                                data-vehicle-info="{{ $vehicle->license_number }} (ID: {{ $vehicle->id }})" {{-- Optional: for a more specific message --}}
                                title="Delete Vehicle"
                                class="text-red-600 hover:text-red-800 delete-vehicle-button">
                            {{-- Heroicon: trash --}}
                            <svg class="w-5 h-5 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.58.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193v-.443A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </form>
                </td>   
            </tr>
        @empty
            <tr>
                <td colspan="8" class="px-6 py-12 text-center text-sm text-slate-500"> {{-- Adjusted colspan --}}
                    No vehicles found. Add one using the button above!
                </td>
            </tr>
        @endforelse
    </tbody>
</table>