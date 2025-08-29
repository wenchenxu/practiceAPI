<?php

namespace App\Http\Controllers;

use App\Http\Requests\DriverStoreRequest;
use App\Http\Requests\DriverUpdateRequest;
use App\Models\Driver;
use App\Models\User;

class DriverController extends Controller
{

    protected function authUser(): User
    {
        return User::findOrFail(session('user_id'));
    }

    public function index()
    {
        $user = $this->authUser();

        $q = Driver::query()->latest('id');
        if (!$user->isHQ()) {
            $q->where('city_id', $user->city_id);
        }

        $drivers = $q->paginate(20);
        return view('drivers.index', compact('drivers'));
    }

    public function store(DriverStoreRequest $request)
    {
        $user = $this->authUser();

        $data = $request->validated();
        $data['city_id'] = $user->isHQ() ? ($data['city_id'] ?? null) : $user->city_id;

        Driver::create($data);
        return redirect()->route('drivers.index')->with('success', 'Driver created.');
    }

    public function update(DriverUpdateRequest $request, Driver $driver)
    {
        $user = $this->authUser();

        if (!$user->isHQ() && $driver->city_id !== $user->city_id) {
            abort(403, 'You cannot modify drivers from another city.');
        }

        $driver->update($request->validated());
        return redirect()->route('drivers.index')->with('success', 'Driver updated.');
    }

    public function destroy(Driver $driver)
    {
        $user = $this->authUser();

        if (!$user->isHQ() && $driver->city_id !== $user->city_id) {
            abort(403, 'You cannot delete drivers from another city.');
        }

        $driver->delete();
        return redirect()->route('drivers.index')->with('success', 'Driver deleted.');
    }
}
