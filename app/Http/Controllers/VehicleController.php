<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Driver;
use App\Http\Requests\VehicleStoreRequest;
use App\Http\Requests\VehicleUpdateRequest;

class VehicleController extends Controller
{
    protected function authUser(): User
    {
        /** @var User $u */
        $u = Auth::user();
        return $u;
    }

    public function index()
    {
        $this->authorize('viewAny', \App\Models\Vehicle::class);

        $user = $this->authUser();

        $q = \App\Models\Vehicle::query()->with(['city','currentAssignment.driver'])->latest('id');

        if (!$user->isHQ()) {
            $q->where('city_id', $user->city_id);
        }

        $vehicles = $q->paginate(20);

        $driversQ = Driver::query()->orderBy('name');
        if (!$user->isHQ()) {
            $driversQ->where('city_id', $user->city_id);
        }
        $drivers = $driversQ->get(['id','name','license_number']);

        return view('vehicles.index', compact('vehicles', 'drivers'));
    }

    public function store(VehicleStoreRequest $request)
    {
        $this->authorize('create', \App\Models\Vehicle::class);

        $user = $this->authUser();

        $data = $request->toVehicleData();
        if (!$user->isHQ()) {
            $data['city_id'] = $user->city_id;
        } else {
            $data['city_id'] = $data['city_id'] ?? null; // HQ may leave null or we can add a select later
        }

        Vehicle::create($data);
        return redirect()->route('vehicles.index')->with('success', 'Vehicle created.');
    }

    public function update(VehicleUpdateRequest $request, Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        $user = $this->authUser();

        if (!$user->isHQ() && $vehicle->city_id !== $user->city_id) {
            abort(403, 'You cannot modify vehicles from another city.');
        }

        $vehicle->update($request->toVehicleData());
        return redirect()->route('vehicles.index')->with('success', 'Vehicle updated.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $this->authorize('delete', $vehicle);

        $user = $this->authUser();

        if (!$user->isHQ() && $vehicle->city_id !== $user->city_id) {
            abort(403, 'You cannot delete vehicles from another city.');
        }

        $vehicle->delete();
        return redirect()->route('vehicles.index')->with('success', 'Vehicle deleted.');
    }
}
