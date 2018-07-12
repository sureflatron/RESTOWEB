<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Redirect;
use App\Http\Requests;

class CuantaaCobrarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fecha = Carbon::now();
        $fecha->toDateString();
        DB::table('cuentaacobrar')->insert([
            'fecha' => $fecha,
            'importe' => $request->importe,
            'idPuntoVenta' => $request->idPuntoVenta,
            'idCuentaCobrar' => $request->idCuentaCobrar,
            'eliminado' => 0]); 
        DB::table('cuentaacobrar')->where('id',$request->idCuentaCobrar)->update(['glosa'=>$request->glosa]);
        $ultimo = DB::table('cobroacuota')->select('id')->orderby('id', 'DESC')->take(1)->get('id');
        return response()->json([$ultimo]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
