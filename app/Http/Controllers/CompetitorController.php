<?php

namespace App\Http\Controllers;

use App\Models\Competitor;
use App\Http\Requests\StoreCompetitorRequest;
use App\Http\Requests\UpdateCompetitorRequest;
use App\Models\CompetitorDivisonled;
use Illuminate\Support\Facades\DB;

class CompetitorController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gfc.competitors.create');
    }
    
    public function createDivisonled()
    {
        return view('divisonled.competitors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompetitorRequest $request)
    {
        try {
            DB::beginTransaction();

            $competitor = new Competitor();
            $competitor->nombre = $request->nombre;
            $competitor->filtro = $request->filtro;
            $competitor->save();

            DB::commit();

            return redirect()->route('gfc.monprice');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return $th->getMessage();
            /* return redirect()->route('gfc.monprice'); */
        }
    }
    
    public function storeDivisonled(StoreCompetitorRequest $request)
    {
        try {
            DB::beginTransaction();

            $competitor = new CompetitorDivisonled();
            $competitor->nombre = $request->nombre;
            $competitor->filtro = $request->filtro;
            $competitor->save();

            DB::commit();

            return redirect()->route('divisonled.dashboard');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return $th->getMessage();
            /* return redirect()->route('gfc.monprice'); */
        }
    }
}
