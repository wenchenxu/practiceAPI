<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AssignmentAssignRequest;
use App\Http\Requests\AssignmentReleaseRequest;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Assignment;
use App\Services\AssignDriverToVehicle;
use Carbon\Carbon;

class AssignmentController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', \App\Models\Assignment::class);

        $user = \Illuminate\Support\Facades\Auth::user();

        $q = \App\Models\Assignment::query()
            ->with(['vehicle.city','driver.city'])
            ->orderByRaw('released_at IS NULL DESC') // active first
            ->latest('assigned_at');

        if (!$user->isHQ()) {
            $q->where('city_id', $user->city_id);
        }

        $assignments = $q->paginate(20);

        return view('assignments.index', compact('assignments'));
    }
    
    public function assign(\App\Http\Requests\AssignmentAssignRequest $request, \App\Models\Vehicle $vehicle, \App\Services\AssignDriverToVehicle $service)
    {
        $this->authorize('create', \App\Models\Assignment::class);

        $user = \App\Models\User::findOrFail(session('user_id'));

        $data = $request->validated();
        $driver = \App\Models\Driver::findOrFail((int) $data['driver_id']);

        // City scoping rule:
        if (!$user->isHQ()) {
            if ($vehicle->city_id !== $user->city_id || $driver->city_id !== $user->city_id) {
                abort(403, 'You can only assign drivers/vehicles from your city.');
            }
        }

        $when  = (!empty($data['assigned_at'])) ? \Carbon\Carbon::parse($data['assigned_at']) : null;
        $notes = $data['notes'] ?? null;

        $service->handle($vehicle, $driver, $when, $notes);

        return redirect()->route('vehicles.index')->with('success', 'Driver assigned to vehicle.');
    }


    public function release(AssignmentReleaseRequest $request, Vehicle $vehicle)
    {
        $this->authorize('update', \App\Models\Assignment::class);

        $data = $request->validated();

        $when = (!empty($data['released_at'])) ? Carbon::parse($data['released_at']) : null;

        $vehicle->release($when);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle released from driver.');
    }
}
