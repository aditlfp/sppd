<?php

namespace App\Http\Controllers;

use App\Http\Requests\PocketMoneyRequest;
use App\Models\Eslon;
use App\Models\PocketMoney;
use App\Models\Region;
use Illuminate\Http\Request;

class PocketMoneyController extends Controller
{
    public function index()
    {
        $pocketMoneys = PocketMoney::paginate(25);
        return view('pocket_moneys.index', compact('pocketMoneys'));
    }

    public function create()
    {
        $eslons = Eslon::all();
        $regions = Region::all();
        return view('pocket_moneys.create', compact('eslons', 'regions'));
    }

    public function store(PocketMoneyRequest $request)
    {
        PocketMoney::create($request->validated());

        return redirect()->route('pocket_moneys.index');
    }

    public function show(PocketMoney $pocketMoney)
    {
        return view('pocket_moneys.show', compact('pocketMoney'));
    }

    public function edit(PocketMoney $pocketMoney)
    {
        $eslons = Eslon::all();
        $regions = Region::all();
        return view('pocket_moneys.edit', compact('pocketMoney', 'eslons', 'regions'));
    }

    public function update(PocketMoneyRequest $request, PocketMoney $pocketMoney)
    {
        $pocketMoney->update($request->validated());

        return redirect()->route('pocket_moneys.index');
    }

    public function destroy(PocketMoney $pocketMoney)
    {
        $pocketMoney->delete();

        return redirect()->route('pocket_moneys.index');
    }
}
