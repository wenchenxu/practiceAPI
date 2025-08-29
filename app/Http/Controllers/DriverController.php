<?php

namespace App\Http\Controllers;

use App\Http\Requests\DriverStoreRequest;
use App\Http\Requests\DriverUpdateRequest;
use App\Models\Driver;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::query()->latest('id')->paginate(25);
        return response()->json($drivers); // for now, pure backend
    }

    public function store(DriverStoreRequest $request)
    {
        $driver = Driver::create($request->validated());
        return redirect()->back()->with('success', "Driver #{$driver->id} created.");
    }

    public function update(DriverUpdateRequest $request, Driver $driver)
    {
        $driver->update($request->validated());
        return redirect()->back()->with('success', "Driver #{$driver->id} updated.");
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();
        return redirect()->back()->with('success', "Driver #{$driver->id} deleted.");
    }
}
