<?php

namespace App\Http\Controllers;

use App\Http\Requests\MainSppdRequest;
use App\Models\Budget;
use App\Models\Eslon;
use App\Models\Jabatan;
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

    protected $beforeLastValue;
    public function __construct()
    {
        $this->beforeLastValue = null;
    }

    public function index()
    {
        $auth = auth()->user();
        $bellow = SPPDBellow::orderByDesc('updated_at')->get(); // Ambil semua data yang sudah terurut dari database
        $latestBellow = $bellow->groupBy('code_sppd');
        if($auth->role_id == 2 || in_array($auth->name, ['SULASNI', 'PARNO', 'DIREKTUR', 'DIREKTUR UTAMA', 'admin'])){
            $mainSppds = MainSPPD::orderBy('created_at', 'desc')->with(['User', 'transportation'])->paginate(10);
            return view('verify_page.index', compact('mainSppds', 'latestBellow'));
        }else{
            $counts = [];
            $count_null = [];
            foreach ($bellow->groupBy('code_sppd') as $code_sppd => $collection) {
                $counts[$code_sppd] = $collection->count();
                $count_null[$code_sppd] = $collection->filter(function ($item) {
                    return empty($item->date_time_arrive); // Cek apakah kosong atau null
                })->count();
            }
            $mainSppds = MainSPPD::where('user_id', $auth->id)->orderBy('created_at', 'asc')->paginate(15);

           // Cek apakah ada SPPD yang belum selesai
            $foundUnfinished = false;

            $mainSppds->transform(function ($sppd) use ($count_null, &$foundUnfinished) {
                $code_sppd = $sppd->code_sppd;

                // Cek apakah SPPD ini belum selesai (count_null > 0)
                $isUnfinished = ($count_null[$code_sppd] ?? 0) > 0;

                // Jika sudah menemukan SPPD yang belum selesai, semua berikutnya harus disabled
                if ($foundUnfinished) {
                    $sppd->is_disabled = true;
                } else {
                    $sppd->is_disabled = !$isUnfinished; // Hanya aktifkan yang pertama yang belum selesai
                }

                // Tandai bahwa kita sudah menemukan satu SPPD yang belum selesai
                if ($isUnfinished) {
                    $foundUnfinished = true;
                }

                return $sppd;
            });
            // dd($mainSppds, $foundUnfinished);
            return view('main_sppds.index', compact('mainSppds', 'latestBellow', 'counts', 'count_null'));
        }
    }

    public function create()
    {
        $user = User::with('jabatan')->where('kerjasama_id', 1)->get();
        $budget = PocketMoney::all();
        $eslon = Eslon::get();
        $transportations = Transportation::all();
        $regions = Region::all();
        $jabatanData = Jabatan::whereIn('id', $eslon->pluck('jabatan_id')->flatten())->get()->keyBy('id');
        return view('main_sppds.create', compact('user', 'budget', 'eslon', 'transportations', 'regions', 'jabatanData'));
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

    public function continueSection(Request $request, $code_sppd)
    {
        $bellow = SPPDBellow::where('code_sppd', $code_sppd)->latest()->get();
        $mainSppd = MainSPPD::where('code_sppd', $code_sppd)->first();
        $datas = $request->continue;

        // Assign the latest entry to beforeLastValue
        if ($bellow->count() > 1) {
            $this->beforeLastValue = $bellow->first(); // Assign first/latest record
        }

        if($datas == 'VERIFIKASI'){
            $request->session()->put('key', $code_sppd);
            $page_html = view('partials.continue_partials', compact('mainSppd', 'bellow', 'datas'))->render();
        } else

        if ($datas == 'true') {
            if ($bellow->count() > 1) {
                if ($this->beforeLastValue && $this->beforeLastValue->continue == 1) {
                    $request->session()->put('key', $code_sppd);
                    $page_html = view('partials.continue_partials', compact('mainSppd', 'bellow', 'datas'))->render();
                } else {
                    return redirect()->back();
                }
            } else {
                // Handle First Data
                $bellow = $bellow->first();
                if ($bellow->continue == 1) {
                    $request->session()->put('key', $bellow->code_sppd);
                    $page_html = view('partials.continue_partials', compact('mainSppd', 'bellow', 'datas'))->render();
                }
            }
        } else if ($datas == 'false') {
            if ($bellow->count() > 1) {
                if ($this->beforeLastValue && $this->beforeLastValue->continue == 1) {
                    $request->session()->put('key', $code_sppd);
                    $page_html = view('partials.continue_partials', compact('mainSppd', 'bellow', 'datas'))->render();
                } else {
                    return redirect()->back();
                }
            } else {
                // Handle First Data
                $bellow = $bellow->first();
                if ($bellow->continue == 1) {
                    $request->session()->put('key', $bellow->code_sppd);
                    $page_html = view('partials.continue_partials', compact('mainSppd', 'bellow', 'datas'))->render();
                }
            }
        }

        return view('sspd_bellow.sppd_bellow', compact('page_html', 'datas'));
    }

    public function storeBottom(Request $request, MainSPPD $mainSppd)
    {
        $beforeLastValue = null;
        $bellow = SPPDBellow::where('code_sppd', $mainSppd->code_sppd)->whereNull('date_time_arrive')->get();
        if($bellow->count() > 1)
        {
            foreach ($bellow as $key => $value) {
                if ($key === count($bellow) - 2) {
                    $beforeLastValue = $value;
                }

                // dd($beforeLastValue, $key, count($bellow));
                if ($beforeLastValue->continue == 1) {
                    $request->session()->put('key', $mainSppd->code_sppd);
                    $sppd_bellow = view('partials.bellow_part_2_partials', compact('mainSppd','bellow'))->render();
                    return view('main_sppds.next_page', compact('sppd_bellow'));
                }else{
                    return redirect()->route('main_sppds.index');
                }
            }
        }else{
            $bellow = $bellow->first();
            if ($bellow->continue == 1) {
                $request->session()->put('key', $bellow->code_sppd);
                $sppd_bellow = view('partials.below_partials', compact('mainSppd','bellow'))->render();
                return view('main_sppds.next_page', compact('sppd_bellow'));
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
        $code_sppd = $request->code_sppd;
        // dd($request->all());
        try {
            $mainSppddata = [
                'date_time_arrive' => $request->date_time_arrive,
                'arrive_at' => $request->arrive_at,
                'departed_at' => $request->departed_at,
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
                try {
                    $mainSppddata['foto_arrive'] = UploadImage($request, 'foto_arrive');
                } catch (\Throwable $th) {
                    dd($th);
                }
            }

            if ($request->hasFile('foto_destination')) {
                $mainSppddata['foto_destination'] = UploadImage($request, 'foto_destination');
            }

            $sPPDBellow = SPPDBellow::where('code_sppd', $code_sppd);
            if($sPPDBellow->count() == 1 && $request->continue == 1)
            {
                $sPPDBellow->latest()->update($mainSppddata);
                SPPDBellow::create(['code_sppd' => $code_sppd]);
                // Redirect ke halaman sukses dengan notifikasi
                // dd($sPPDBellow);
                flash()->success('Data berhasil diubah.');
                return redirect()->route('main_sppds.index');
            } else if ($sPPDBellow->count() > 1 && $request->continue == 1) {
                $sPPDBellow->latest()->first()->update($mainSppddata);
                SPPDBellow::create(['code_sppd' => $code_sppd]);
                // Redirect ke halaman sukses dengan notifikasi
                flash()->success('Data berhasil diubah.');
                return redirect()->route('main_sppds.index');
            }else if ($request->continue == 0) {
                $sPPDBellow->latest()->first()->update($mainSppddata);
                // Redirect ke halaman sukses dengan notifikasi
                flash()->success('Data berhasil diubah.');
                return redirect()->route('main_sppds.index');
            }
        } catch (\Exception $e) {
            dd($e);
            // Redirect ke halaman sebelumnya jika terjadi kesalahan lain
            flash()->error('Terjadi kesalahan saat mengubah data. Mohon coba lagi.');
            return redirect()->back();
        }
    }

    public function details(MainSPPD $mainSppd){
        $bellow = SPPDBellow::where('code_sppd', $mainSppd->code_sppd)->whereNotNull('date_time_arrive')->get();
        $user = User::with('jabatan')->where('kerjasama_id', 1)->get();
        $budget = PocketMoney::all();
        $eslon = Eslon::get();
        $transportations = Transportation::all();
        $regions = Region::all();
        $jabatanData = Jabatan::whereIn('id', $eslon->pluck('jabatan_id')->flatten())->get()->keyBy('id');
        $beforeLastValue = null;

        // dd($bellow);

        if($bellow->count() > 1)
        {
            foreach ($bellow as $key => $value) {
                // if ($key === count($bellow) - 2) {
                    $beforeLastValue = $value;
                //     echo $beforeLastValue;
                // }

                // dd($beforeLastValue, $key, count($bellow));
                if ($beforeLastValue->continue == 1) {
                    // $request->session()->put('key', $mainSppd->code_sppd);
                    $sppd_bellow = view('partials.bellow_part_2_partials', compact('mainSppd','bellow'))->render();
                    return view('main_sppds.next_page', compact('sppd_bellow'));
                }else{
                    return redirect()->route('main_sppds.index');
                }
            }
        }else{
            $bellow = $bellow->first();
            if ($bellow->continue == 1) {
                // $request->session()->put('key', $bellow->code_sppd);
                $sppd_bellow = view('partials.below_partials', compact('mainSppd','bellow'))->render();
                return view('main_sppds.next_page', compact('sppd_bellow'));
            }else{
                return redirect()->route('main_sppds.index');
            }
        }
        return view('sspd_bellow.show', compact('mainSppd', 'bellow', 'user', 'budget', 'eslon', 'transportations', 'regions', 'jabatanData'));
    }

    public function change(MainSPPD $mainSppd)
    {
        $user = User::with('jabatan')->where('kerjasama_id', 1)->get();
        $budget = PocketMoney::all();
        $eslon = Eslon::get();
        $transportations = Transportation::all();
        $regions = Region::all();
        $jabatanData = Jabatan::whereIn('id', $eslon->pluck('jabatan_id')->flatten())->get()->keyBy('id');
        return view('main_sppds.change', compact('mainSppd', 'user', 'budget', 'eslon', 'transportations', 'regions', 'jabatanData'));
    }

    public function changeUpdate(Request $request, MainSPPD $mainSppd)
    {
        $mainSppd->update($request->all());
        flash()->success('Data berhasil diubah.');
        return redirect()->route('main_sppds.index');
    }

    public function destroy(MainSPPD $mainSppd)
    {
        $mainSppd->delete();
        flash()->warning('Data berhasil dihapus.');
        return redirect()->route('main_sppds.index');
    }
}
