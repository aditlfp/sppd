<?php

namespace App\Http\Controllers;

use App\Http\Requests\EslonRequest;
use App\Models\Eslon;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class EslonController extends Controller
{
    public function index()
    {
        $eslons = Eslon::paginate(25);
        $jabatan = Jabatan::all();
        return view('eslons.index', compact('eslons', 'jabatan'));
    }

    public function create()
    {
        $jabatan = Jabatan::all();
        return view('eslons.create', compact('jabatan'));
    }

    public function store(EslonRequest $request)
    {
        // dd($request->all());
        try {
            // Attempt to create a new record
            Eslon::create($request->validated());
            // Redirect to the index page with a success message
            return redirect()->route('eslons.index')->with('success', 'Eslon created successfully.');
        } catch (\Exception $e) {
            // Log the error message for debugging
            \Log::error('Error creating Eslon: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->with('error', 'An error occurred while creating the Eslon.');
        }
    }


    public function show(Eslon $eslon)
    {
        return view('eslons.show', compact('eslon'));
    }

    public function edit(Eslon $eslon)
    {
        $jabatan = Jabatan::all();
        return view('eslons.edit', compact('eslon', 'jabatan'));
    }

    public function update(EslonRequest $request, Eslon $eslon)
    {
        $eslon->update($request->validated());

        return redirect()->route('eslons.index');
    }

    public function destroy(Eslon $eslon)
    {
        $eslon->delete();

        return redirect()->route('eslons.index');
    }
}
