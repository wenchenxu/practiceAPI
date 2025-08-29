<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Http\Requests\VehicleStoreRequest;
use App\Http\Requests\VehicleUpdateRequest;

class VehicleController extends Controller
{
    /**
     * GET /vehicles
     */
    public function index()
    {
        $vehicles = \App\Models\Vehicle::query()
        ->with(['currentAssignment.driver'])   // eager-load current driver
        ->latest('id')
        ->paginate(20);

        $drivers = \App\Models\Driver::query()
            ->orderBy('name')
            ->get(['id','name','license_number']);

        return view('vehicles.index', compact('vehicles', 'drivers'));
    }

    /**
     * POST /vehicles
     */
    public function store(VehicleStoreRequest $request)
    {
        Vehicle::create($request->toVehicleData());

        // If you submit from a modal on the index page, redirect back there
        return redirect()->route('vehicles.index')->with('success', 'Vehicle created.');
    }

    /**
     * PUT/PATCH /vehicles/{vehicle}
     */
    public function update(VehicleUpdateRequest $request, Vehicle $vehicle)
    {
        $vehicle->update($request->toVehicleData());

        return redirect()->route('vehicles.index')->with('success', 'Vehicle updated.');
    }

    /**
     * DELETE /vehicles/{vehicle}
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route('vehicles.index')->with('success', 'Vehicle deleted.');
    }
}
