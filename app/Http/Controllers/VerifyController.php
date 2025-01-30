<?php

namespace App\Http\Controllers;

use App\Models\MainSPPD;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    // VIEW VERIFY SPPD
    public function viewVerify(MainSPPD $mainSppd)
    {
        return view('verify_page.index', compact('mainSppd'));
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
