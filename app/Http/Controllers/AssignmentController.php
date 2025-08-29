<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignmentAssignRequest;
use App\Http\Requests\AssignmentReleaseRequest;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Services\AssignDriverToVehicle;
use Carbon\Carbon;

class AssignmentController extends Controller
{
    public function assign(AssignmentAssignRequest $request, Vehicle $vehicle, AssignDriverToVehicle $service)
    {
        $data = $request->validated();

        $driverId = isset($data['driver_id']) ? (int) $data['driver_id'] : 0;
        $driver   = Driver::findOrFail($driverId);

        $when  = (!empty($data['assigned_at'])) ? Carbon::parse($data['assigned_at']) : null;
        $notes = $data['notes'] ?? null;

        $service->handle($vehicle, $driver, $when, $notes);

        return redirect()->route('vehicles.index')->with('success', 'Driver assigned to vehicle.');
    }

    public function release(AssignmentReleaseRequest $request, Vehicle $vehicle)
    {
        $data = $request->validated();

        $when = (!empty($data['released_at'])) ? Carbon::parse($data['released_at']) : null;

        $vehicle->release($when);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle released from driver.');
    }
}
