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
use App\Models\SPPDBellow;
use Illuminate\Http\Request;
use Symfony\Component\Mailer\Transport;
use Illuminate\Support\Str;


class MainSPPDController extends Controller
{

    public function index()
    {
        $auth = auth()->user();
        $bellow = SPPDBellow::orderByDesc('updated_at')->get(); // Ambil semua data yang sudah terurut dari database
        $latestBellow = $bellow->groupBy('code_sppd')->map(function ($items) {
            return $items->first(); // Karena sudah diurutkan di query, cukup ambil yang pertama
        });
        if($auth->role_id == 2 || in_array($auth->name, ['SULASNI', 'PARNO', 'DIREKTUR', 'DIREKTUR UTAMA', 'admin'])){
            $mainSppds = MainSPPD::latest()->with(['User', 'transportation'])->get();
            return view('verify_page.index', compact('mainSppds', 'latestBellow'));
        }else{
            $mainSppds = MainSPPD::where('user_id', $auth->id)->latest()->paginate(15);
            return view('main_sppds.index', compact('mainSppds', 'latestBellow'));
        }
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
        $randomString = Str::random(10);
        // dd($request->all(), $randomString, $randomString);
        try {
            $validatedData = $request->validated();
            $validatedData['code_sppd'] = $randomString;
            SPPDBellow::create(['code_sppd' => $randomString]);
            MainSPPD::create($validatedData);

            // Redirect ke halaman sukses dengan notifikasi
            flash()->success('Data berhasil disimpan.');
            return redirect()->route('main_sppds.index');
        } catch (\Exception $e) {
            // Redirect ke halaman sebelumnya jika terjadi kesalahan lain
            dd($e);
            flash()->error('Terjadi kesalahan saat menyimpan data. Mohon coba lagi.');

            return redirect()->back();
        }
    }

    public function storeBottom(Request $request, MainSPPD $mainSppd)
    {
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
                    return view('main_sppds.next_page', compact('mainSppd', 'bellow'));
                }else{
                    return redirect()->route('main_sppds.index');
                }
            }
        }else{
            $bellow = $bellow->first();
            if ($bellow->continue == 1) {
                $request->session()->put('key', $bellow->code_sppd);
                return view('main_sppds.next_page', compact('mainSppd', 'bellow'));
            }else{
                return redirect()->route('main_sppds.index');
            }
        }
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



    public function update(Request $request)
    {
        $code_sppd = $request->session()->get('key', 'Default Value');
        try {
            $mainSppddata = [
                'date_time_arrive' => $request->date_time_arrive,
                'arrive_at' => $request->arrive_at,
                'foto_arrive' => $request->foto_arrive,
                'continue' => $request->continue,
                'date_time_destination' => $request->date_time_destination,
                'foto_destination' => $request->foto_destination,
                'date_time' => $request->date_time,
                'verify' => $request->verify,
                'note' => $request->note,
                'maps_tiba' => $request->maps_tiba,
                'maps_tujuan' => $request->maps_tujuan
            ];
            if ($request->hasFile('foto_arrive')) {
                $mainSppddata['foto_arrive'] = UploadImage($request, 'foto_arrive');
            }

            if ($request->hasFile('foto_destination')) {
                $mainSppddata['foto_destination'] = UploadImage($request, 'foto_destination');
            }

            $sPPDBellow = SPPDBellow::where('code_sppd', $code_sppd);
            $sPPDBellow->update($mainSppddata);
            // Redirect ke halaman sukses dengan notifikasi
            flash()->success('Data berhasil diubah.');
            return redirect()->route('main_sppds.index');
        } catch (\Exception $e) {
            dd($e);
            // Redirect ke halaman sebelumnya jika terjadi kesalahan lain
            flash()->error('Terjadi kesalahan saat mengubah data. Mohon coba lagi.');
            return redirect()->back();
        }
    }

    public function destroy(MainSPPD $mainSppd)
    {
        $mainSppd->delete();
        flash()->warning('Data berhasil dihapus.');
        return redirect()->route('main_sppds.index');
    }
}
