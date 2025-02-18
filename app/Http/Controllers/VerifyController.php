<?php

namespace App\Http\Controllers;

use App\Models\Eslon;
use App\Models\MainSPPD;
use App\Models\PocketMoney;
use App\Models\Region;
use App\Models\SPPDBellow;
use App\Models\Transportation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class VerifyController extends Controller
{
    // VIEW VERIFY SPPD
    public function viewVerify(Request $request, MainSPPD $mainSppd)
    {
        $user = User::with('jabatan')->where('kerjasama_id', 1)->get();
        $budget = PocketMoney::all();
        $eslon = Eslon::get();
        $transportations = Transportation::all();
        $regions = Region::all();


        $beforeLastValue = null;
        $bellow = SPPDBellow::where('code_sppd', $mainSppd->code_sppd)->get();
        if($bellow->count() > 1)
        {
            foreach ($bellow as $key => $value) {
                if ($key === count($bellow) - 2) {
                    $beforeLastValue = $value;
                }


                if ($beforeLastValue->continue == 1) {
                    $request->session()->put('key', $mainSppd->code_sppd);
                     $nextSppd = view('partials.bellow_part_2_partials', compact('mainSppd', 'bellow'))->render();;
                }else{
                    return redirect()->route('main_sppds.index');
                }
            }
        }else{
            $bellow = $bellow->first();
            if ($bellow->continue == 1) {
                $request->session()->put('key', $bellow->code_sppd);
                 $nextSppd = view('partials.below_partials', compact('mainSppd', 'bellow'))->render();;
            }else{
                return redirect()->route('main_sppds.index');
            }
        }

        // dd($mainSppd);
        // dd(Route::is('verify_page.*'));
        return view('verify_page.show', compact('mainSppd', 'user', 'eslon', 'budget', 'transportations', 'regions', 'nextSppd'));
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
            }elseif($request->name_verify == 'reject'){
                $mainSppd->update(['verify' => 3]);
                flash()->success('Data berhasil direject.');

            }
            return to_route('main_sppds.index');
        } catch (\Throwable $th) {
            dd($th);
            // abort(403);
            return redirect()->back();
        }
    }
}
