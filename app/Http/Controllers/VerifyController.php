<?php

namespace App\Http\Controllers;

use App\Models\Eslon;
use App\Models\MainSPPD;
use App\Models\PocketMoney;
use App\Models\Region;
use App\Models\Transportation;
use App\Models\User;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    // VIEW VERIFY SPPD
    public function viewVerify(MainSPPD $mainSppd)
    {
        $user = User::with('jabatan')->where('kerjasama_id', 1)->get();
        $budget = PocketMoney::all();
        $eslon = Eslon::get();
        $transportations = Transportation::all();
        $regions = Region::all();
        // dd($mainSppd);
        return view('verify_page.show', compact('mainSppd', 'user', 'eslon', 'budget', 'transportations', 'regions'));
    }
    // VERIFY SPPD
    public function verify(Request $request, MainSPPD $mainSppd)
    {
        try {
            if($request->name_verify == 'verify_departure'){
                $mainSppd->update(['verify' => 2]);
                flash()->success('Data Telah Di Update Ke Perjalanan.');
            }elseif($request->name_verify == 'verify_arrive'){
                $mainSppd->update(['verify' => 1]);
                flash()->success('Data berhasil diverifikasi.');
            }
            return redirect()->back();
        } catch (\Throwable $th) {
            dd($th);
            // abort(403);
            return redirect()->back();
        }
    }
}
