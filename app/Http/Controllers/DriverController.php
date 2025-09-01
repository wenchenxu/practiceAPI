<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DriverStoreRequest;
use App\Http\Requests\DriverUpdateRequest;
use App\Models\Driver;
use App\Models\User;

class DriverController extends Controller
{

    protected function authUser(): User
    {
        /** @var User $u */
        $u = Auth::user();
        return $u;
    }

    public function index()
    {
        $this->authorize('viewAny', \App\Models\Driver::class);

        $user = $this->authUser();

        $q = \App\Models\Driver::query()->with('city')->latest('id');
        if (!$user->isHQ()) {
            $q->where('city_id', $user->city_id);
        }

        $drivers = $q->paginate(20);
        return view('drivers.index', compact('drivers'));
    }

    public function store(DriverStoreRequest $request)
    {
        $this->authorize('create', \App\Models\Driver::class);

        $user = $this->authUser();

        $data = $request->validated();
        $data['city_id'] = $user->isHQ() ? ($data['city_id'] ?? null) : $user->city_id;

        Driver::create($data);
        return redirect()->route('drivers.index')->with('success', 'Driver created.');
    }

    public function update(DriverUpdateRequest $request, Driver $driver)
    {
        $this->authorize('update', $driver);

        $user = $this->authUser();

        if (!$user->isHQ() && $driver->city_id !== $user->city_id) {
            abort(403, 'You cannot modify drivers from another city.');
        }

        $driver->update($request->validated());
        return redirect()->route('drivers.index')->with('success', 'Driver updated.');
    }

    public function destroy(Driver $driver)
    {
        $this->authorize('delete', $driver);

        $user = $this->authUser();

        if (!$user->isHQ() && $driver->city_id !== $user->city_id) {
            abort(403, 'You cannot delete drivers from another city.');
        }

        $driver->delete();
        return redirect()->route('drivers.index')->with('success', 'Driver deleted.');
    }
}
