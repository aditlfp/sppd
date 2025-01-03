<?php

namespace App\Http\Controllers;

use App\Http\Requests\MainSppdRequest;
use App\Models\Budget;
use App\Models\Eslon;
use App\Models\MainSPPD;
use App\Models\PocketMoney;
use App\Models\Transportation;
use App\Models\User;
use App\Models\Region;
use Symfony\Component\Mailer\Transport;

class MainSPPDController extends Controller
{
    public function index()
    {
        $mainSppds = MainSPPD::paginate(15);
        return view('main_sppds.index', compact('mainSppds'));
    }

    public function create()
    {
        $user = User::with('jabatan')->where('kerjasama_id', 1)->get();
        $budget = PocketMoney::all();
        $eslon = Eslon::get();
        $transportations = Transportation::all();
        $regions = Region::all();
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
        return view('main_sppds.create', compact('user', 'budget', 'eslon', 'transportations', 'regions'));
    }

    public function store(MainSppdRequest $request)
    {
        try {
            MainSPPD::create($request->validated());

            // Redirect ke halaman sukses dengan notifikasi
            return redirect()->route('main_sppds.index')
                ->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            // Redirect ke halaman sebelumnya jika terjadi kesalahan lain
            dd($e);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Mohon coba lagi.');
        }
    }

    public function storeBottom(MainSPPD $mainSppd)
    {
        return view('main_sppds.next_page', compact('mainSppd'));
    }

    public function show(MainSPPD $mainSppd)
    {
        return view('main_sppds.show', compact('mainSppd'));
    }

    public function edit(MainSPPD $mainSppd)
    {
        $user = User::with('jabatan')->where('kerjasama_id', 1)->get();
        $budget = Budget::all();
        $eslon = Eslon::get();
        return view('main_sppds.edit', compact('mainSppd', 'user', 'budget', 'eslon'));
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
