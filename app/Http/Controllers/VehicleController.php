<?php

namespace App\Http\Controllers;

use App\Models\Vehicle; // Import the Vehicle model
use Illuminate\Http\Request; // Import the Request class

class VehicleController extends Controller
{
    /**
     * Display a listing of the vehicles and the form to add a new vehicle.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch all vehicles from the database, order them by newest first
        $vehicles = Vehicle::orderBy('created_at', 'desc')->paginate(30); // Show 30 vehicles per page

        // Pass the $vehicles data to a view named 'vehicles.index'
        // We will create this view in a later step.
        return view('vehicles.index', ['vehicles' => $vehicles]);
    }

    /**
     * Store a newly created vehicle in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'license_number' => 'required|string|unique:vehicles,license_number|max:100',
            'driver_name' => 'required|string|max:255',
            'driver_phone_number' => 'nullable|string|max:50',
            // New validation rules for shop entry time components
            'shop_entry_date' => 'required|date_format:Y-m-d',
            'shop_entry_hour' => 'required|numeric|between:0,23', // 24-hour format
            'shop_entry_minute' => 'required|numeric|in:0,15,30,45',
        ]);

        // Prepare data for vehicle creation
        $vehicleData = [
            'license_number' => $validatedData['license_number'],
            'driver_name' => $validatedData['driver_name'],
            'driver_phone_number' => $validatedData['driver_phone_number'],
        ];

        // Construct the shop_entry_time timestamp
        // Ensure date, hour, and minute are present before attempting to construct
        if (isset($validatedData['shop_entry_date'], $validatedData['shop_entry_hour'], $validatedData['shop_entry_minute'])) {
            $vehicleData['shop_entry_time'] = $validatedData['shop_entry_date'] . ' ' .
                                              str_pad($validatedData['shop_entry_hour'], 2, '0', STR_PAD_LEFT) . ':' .
                                              str_pad($validatedData['shop_entry_minute'], 2, '0', STR_PAD_LEFT) . ':00';
        } else {
            // Handle case where shop entry time is not fully provided, if it can be optional overall
            // If always required (as per 'required' validation), this else might not be strictly needed
            // but good for robustness if validation was conditional.
            // For now, validation makes them required, so this path is less likely.
            $vehicleData['shop_entry_time'] = null;
        }

        // Create a new vehicle record in the database using the prepared data
        Vehicle::create($vehicleData);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle added successfully!');
    }

    /**
     * Show the form for editing the specified vehicle.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\View\View
     */
    public function edit(Vehicle $vehicle) // Laravel automatically finds the Vehicle by its ID from the route
    {
        // We'll create this view in the next frontend step
        return view('vehicles.edit', ['vehicle' => $vehicle]);
    }

    /**
     * Update the specified vehicle in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'license_number' => 'required|string|unique:vehicles,license_number,' . $vehicle->id . '|max:100',
            'driver_name' => 'required|string|max:255',
            'driver_phone_number' => 'nullable|string|max:50',
            // New validation rules for shop entry time components
            'shop_entry_date' => 'required|date_format:Y-m-d',
            'shop_entry_hour' => 'required|integer|between:0,23',
            'shop_entry_minute' => 'required|integer|in:0,15,30,45',
        ]);

        // Prepare data for vehicle update
        $vehicleDataToUpdate = [
            'license_number' => $validatedData['license_number'],
            'driver_name' => $validatedData['driver_name'],
            'driver_phone_number' => $validatedData['driver_phone_number'],
        ];

        // Construct the shop_entry_time timestamp
        if (isset($validatedData['shop_entry_date'], $validatedData['shop_entry_hour'], $validatedData['shop_entry_minute'])) {
            $vehicleDataToUpdate['shop_entry_time'] = $validatedData['shop_entry_date'] . ' ' .
                                                     str_pad($validatedData['shop_entry_hour'], 2, '0', STR_PAD_LEFT) . ':' .
                                                     str_pad($validatedData['shop_entry_minute'], 2, '0', STR_PAD_LEFT) . ':00';
        } else {
             // If shop_entry_time was optional and user clears it, you might want to set it to null
             // For now, assuming it's always required by validation when editing.
            $vehicleDataToUpdate['shop_entry_time'] = null;
        }

        // Update the vehicle's attributes
        $vehicle->update($vehicleDataToUpdate);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle updated successfully!');
    }

    /**
     * Remove the specified vehicle from storage.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Vehicle $vehicle) // Laravel automatically finds the Vehicle by its ID
    {
        // Delete the vehicle
        $vehicle->delete();

        // Redirect back to the vehicles index page with a success message
        return redirect()->route('vehicles.index')->with('success', 'Vehicle deleted successfully!');
    }
}