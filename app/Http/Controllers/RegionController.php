<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegionRequest;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::all();
        return view('regions.index', compact('regions'));
    }

    public function create()
    {
        return view('regions.create');
    }

    public function store(RegionRequest $request)
    {
        Region::create($request->validated());

        return redirect()->route('regions.index');
    }

    public function show(Region $region)
    {
        return view('regions.show', compact('region'));
    }

    public function edit(Region $region)
    {
        return view('regions.edit', compact('region'));
    }

    public function update(RegionRequest $request, Region $region)
    {
        $region->update($request->validated());

        return redirect()->route('regions.index');
    }

    public function destroy(Region $region)
    {
        $region->delete();

        return redirect()->route('regions.index');
    }
}
