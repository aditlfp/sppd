<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransportationRequest;
use App\Models\Transportation;
use Illuminate\Http\Request;


class TransportationController extends Controller
{
    public function index()
    {
        $transportations = Transportation::all();
        return view('transportations.index', compact('transportations'));
    }

    public function create()
    {
        return view('transportations.create');
    }

    public function store(TransportationRequest $request)
    {
        Transportation::create($request->validated());

        return redirect()->route('transportations.index');
    }

    public function show(Transportation $transportation)
    {
        return view('transportations.show', compact('transportation'));
    }

    public function edit(Transportation $transportation)
    {
        return view('transportations.edit', compact('transportation'));
    }

    public function update(TransportationRequest $request, Transportation $transportation)
    {
        $transportation->update($request->validated());

        return redirect()->route('transportations.index');
    }

    public function destroy(Transportation $transportation)
    {
        $transportation->delete();

        return redirect()->route('transportations.index');
    }
}
