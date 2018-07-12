<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;
use DB;
use Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Requests;

class LoginController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        
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
        $imagen;
        $nombre;
        $Cargo;
        $idpuntoventa;
        $idempleado;
        $idPerfil;
        $nombreperfil;
        $sucur;
        $idsucursal;
        $users = DB::table('usuario')
                ->select('usuario.urlFoto as imagen', 'empleado.nombre', 'cargo.nombre as Cargo', 'puntoventa.id as idpuntoventa', 'sucursal.nombre as sucur', 'sucursal.id as idsucursal', 'empleado.id as idempleado', 'perfil.id as idPerfil', 'perfil.nombre as nombreperfil')
                ->where('usuario.nombreUsuario', $request->username)
                ->where('usuario.contraseña', $request->contra)
                ->where('usuario.eliminado', 0)
                ->join('empleado', 'empleado.id', '=', 'usuario.idEmpleado')
                ->join('cargo', 'cargo.id', '=', 'empleado.idCargo')
                ->join('puntoventa', 'puntoventa.idEmpleado', '=', 'empleado.id')
                ->join('sucursal', 'sucursal.id', '=', 'puntoventa.idSucursal')
                ->join('perfil', 'perfil.id', '=', 'usuario.idPerfil')
                ->get();
        foreach ($users as $user) {
            $imagen = $user->imagen;
            $nombre = $user->nombre;
            $Cargo = $user->Cargo;
            $idpuntoventa = $user->idpuntoventa;
            $idempleado = $user->idempleado;
            $idPerfil = $user->idPerfil;
            $nombreperfil = $user->nombreperfil;
            $sucur = $user->sucur;
            $idsucursal = $user->idsucursal;
        }
        if ($users == null) {
            Session::flash('message', 'Datos Incorrectos');
            return redirect('/');
        } else {
            Session::put('imagen', $imagen);
            Session::put('nombre', $nombre);
            Session::put('Cargo', $Cargo);
            Session::put('idpuntoventa', $idpuntoventa);
            Session::put('idempleado', $idempleado);
            Session::put('idPerfil', $idPerfil);
            Session::put('nombreperfil', $nombreperfil);
            Session::put('sucursal', $sucur);
            Session::put('idsucursal', $idsucursal);
            return redirect('/index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    public function login() {
        $sescionactivo = Session::get('idPerfil');
        if ($sescionactivo == '') {
            return view("inicio");
            //return response()->json($sescionactivo);
        } else {
            return redirect('/index');
            // return response()->json('asda');
        }
    }

    public function logout() {
        Session::flush();
        return redirect('/');
    }

}
