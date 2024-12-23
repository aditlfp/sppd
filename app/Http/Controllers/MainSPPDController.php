<?php

namespace App\Http\Controllers;

use App\Http\Requests\MainSppdRequest;
use App\Models\Budget;
use App\Models\Eslon;
use App\Models\MainSPPD;
use App\Models\User;

class MainSPPDController extends Controller
{
    public function index()
    {
        $mainSppds = MainSPPD::all();
        return view('main_sppds.index', compact('mainSppds'));
    }

    public function create()
    {
        $user = User::with('jabatan')->where('kerjasama_id', 1)->get();
        $budget = Budget::all();
        $eslon = Eslon::get();
        // foreach ($eslon as $key => $value) {
        //     $jabatanIds = json_decode($value->jabatan_id, true); // Decode JSON array
        //     if (is_array($jabatanIds)) {
        //         foreach ($jabatanIds as $jabatanId) {
        //             // Process each jabatanId
        //             // For example, you can log it or perform some action
        //             dd($jabatanId); // Uncomment this line to debug
        //         }
        //     }
        // }
        return view('main_sppds.create', compact('user', 'budget', 'eslon'));
    }

    public function store(MainSppdRequest $request)
    {
        MainSPPD::create($request->validated());

        return redirect()->route('main_sppds.index');
    }

    public function show(MainSPPD $mainSppd)
    {
        return view('main_sppds.show', compact('mainSppd'));
    }

    public function edit(MainSPPD $mainSppd)
    {
        return view('main_sppds.edit', compact('mainSppd'));
    }

    public function update(MainSppdRequest $request, MainSPPD $mainSppd)
    {
        $mainSppd->update($request->validated());

        return redirect()->route('main_sppds.index');
    }

    public function destroy(MainSPPD $mainSppd)
    {
        $mainSppd->delete();

        return redirect()->route('main_sppds.index');
    }
}
