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
        $eslons = Eslon::all();
        // Pastikan setiap `jabatan_id` didecode dengan aman
        $jabatanIds = collect($eslons)->flatMap(function ($eslon) {
            return is_string($eslon->jabatan_id) 
                ? json_decode($eslon->jabatan_id, true) 
                : $eslon->jabatan_id; // Jika sudah array, langsung pakai
        })->unique()->filter();

        // Ambil semua jabatan terkait
        $jabatans = Jabatan::whereIn('id', $jabatanIds)->get()->keyBy('id');

        return view('eslons.index', compact('eslons', 'jabatans'));
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
            flash()->success('Data Has been Saved successfully.');
            return redirect()->route('eslons.index');
        } catch (\Exception $e) {
            // Log the error message for debugging
            \Log::error('Error creating Eslon: ' . $e->getMessage());
            flash()->error("Data Can't Be Saved.");

            // Redirect back with an error message
            return redirect()->back();
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
        flash()->success('Data has update. Saved !.');
        return redirect()->route('eslons.index');
    }

    public function destroy(Eslon $eslon)
    {
        $eslon->delete();
        flash()->success('Data has deleted. Saved !.');

        return redirect()->route('eslons.index');
    }
}
