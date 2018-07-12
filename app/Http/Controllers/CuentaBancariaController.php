<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;

class CuentaBancariaController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        if ($request->ajax()) {
            $cuenta = DB::select("SELECT cuentabancaria.id FROM cuentaBancaria WHERE cuentaBancaria.nroCuenta = ?", [$request->cuenta]);
            $bool = true;
            foreach ($cuenta as $key => $value) {
                $bool = false;
            }
            if ($bool) {
                DB::table('cuentaBancaria')
                        ->insert(['banco' => $request->banco,
                            'nroCuenta' => $request->cuenta,
                            'tipoCuenta' => $request->tipocuenta,
                            'moneda' => $request->moneda,
                            'eliminado' => 0,
                            'razonSocial' => $request->razonSocial]);
                return response()->json(["mensaje" => "Guardado exitoso"]);
            } else {
                return response()->json(["mensaje" => "El numero de Cuenta ya existe"]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $dato = DB::table('cuentaBancaria')
                ->select('banco', 'nroCuenta', 'tipoCuenta', 'moneda', 'id', 'razonSocial')
                ->where('id', $id)
                ->get('banco', 'nroCuenta', 'tipoCuenta', 'moneda', 'id', 'razonSocial');
        return response()->json($dato);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $Actalizado = DB::table('cuentaBancaria')
                ->where('id', $id)
                ->update(['banco' => $request->banco,
            'nroCuenta' => $request->cuenta,
            'tipoCuenta' => $request->tipocuenta,
            'moneda' => $request->moneda,
            'razonSocial' => $request->razonSocial]);
        return response()->json(["mensaje" => $Actalizado]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $eliminado = DB::table('cuentaBancaria')->where('id', $id)->update(['eliminado' => 1]);
        return response()->json(["mensaje" => "listo"]);
    }

    public function listaCuentaBancaria() {
        $results = DB::select("SELECT
                 cuentabancaria.id,
                 banco.nombre as banco,
                 cuentabancaria.nroCuenta,
                 cuentabancaria.tipoCuenta,
                 cuentabancaria.moneda,
                 cuentabancaria.razonSocial
            FROM cuentabancaria,banco 
            WHERE cuentabancaria.banco = banco.id AND cuentabancaria.eliminado = 0");
        return response()->json($results);
    }

    public function listaCuenta($id) {
        $datos = DB::select("select cuentabancaria.nroCuenta from cuentabancaria WHERE cuentabancaria.id = ?", [$id]);
        return response()->json($datos);
    }

}
