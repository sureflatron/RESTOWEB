<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Usuario;
use DB;
use Session;
use Redirect;
use Crypt;
use App\Http\Requests;

class UsuarioController extends Controller {

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
     * usuario: usuario,password:password,fechainicio:fechainicio,fechafin:fechafin,perfils:perfils,sucursal:sucursal
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $contra = Crypt::encrypt($request->password);
        DB::table('usuario')->insert([
            'nombreUsuario' => $request->usuario,
            'contraseña' => $request->password,
            'urlFoto' => $request->imagen,
            'idPerfil' => $request->perfils,
            'idEmpleado' => $request->id
        ]);
        DB::table('puntoventa')->insert([
            'idSucursal' => $request->sucursal,
            'fechainicio' => $request->fechainicio,
            'fechafin' => $request->fechafin,
            'idEmpleado' => $request->id,
            'ventamultialmacen' => $request->ventamultialmacen,
            'almacenpordefecto' => $request->almacen,
            'puedevender' => $request->cansell
        ]);
        return response()->json($request->all());
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
        $contra = Crypt::encrypt($request->password);
        $actua = DB::table('usuario')
                ->where('idEmpleado', $id)
                ->update(['nombreUsuario' => $request->usuario,
            'contraseña' => $request->password,
            'urlFoto' => $request->imagen,
            'idPerfil' => $request->perfils,]);
        $actua = DB::table('puntoventa')
                ->where('idEmpleado', $id)
                ->update(['idSucursal' => $request->sucursal,
            'fechainicio' => $request->fechainicio,
            'fechafin' => $request->fechafin,
            'ventamultialmacen' => $request->ventamultialmacen,
            'almacenpordefecto' => $request->almacen,
            'puedevender' => $request->cansell]);
        return response()->json(["mensaje" => "listo"]);
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

    //select usuario.nombreUsuario,usuario.contraseña,usuario.urlFoto,puntoventa.fechainicio,puntoventa.fechafin 
//from usuario   
//INNER  JOIN puntoventa  
//INNER JOIN empleado
//WHERE puntoventa.idEmpleado=empleado.id and usuario.idEmpleado=empleado.id and usuario.idEmpleado=1
    public function listarusuario($id) {
        $productos = DB::table('usuario')->select( 'puntoventa.puedevender',
                                'usuario.nombreUsuario', 'usuario.contraseña as password', 'usuario.urlFoto', 'puntoventa.fechainicio', 'puntoventa.fechafin', 'perfil.id as perfil', 'sucursal.id as sucursal', 'puntoventa.ventamultialmacen', 'puntoventa.almacenpordefecto', 'empleado.nombre as empleado', 'cargo.nombre as cargo', 'empleado.correoElectronico as email')
                        ->join('empleado', 'empleado.id', '=', 'usuario.idEmpleado')
                        ->join('puntoventa', 'puntoventa.idEmpleado', '=', 'empleado.id')
                        ->join('perfil', 'perfil.id', '=', 'usuario.idPerfil')
                        ->join('sucursal', 'sucursal.id', '=', 'puntoventa.idSucursal')
                        ->join('cargo', 'empleado.idCargo', '=', 'cargo.id')
                        ->where('usuario.idEmpleado', $id)->first("email", "nombreUsuario", "password", "urlFoto", "fechainicio", "fechafin", "sucursal", "perfil", "empleado", "cargo");
        return response()->json($productos);
    }

//

    public function actualizarpassword(Request $request) {
        $otro = DB::select("select contraseña FROM usuario where idEmpleado = $request->id and eliminado = 0");
        $contrasenia = $otro[0]->contraseña;
        if ($contrasenia == $request->passwordold) {
            $actua = DB::table('usuario')
                    ->where('idEmpleado', $request->id)
                    ->update([
                'contraseña' => $request->password]);
            return response()->json(["mensaje" => "Contraseña Actualizada"]);
        } else {
            return response()->json(["mensaje" => "Inserte correctamente su contraseña actual"]);
        }
    }

    public function cambiarimagen(Request $request) {
        $actua = DB::table('usuario')
                ->where('idEmpleado', $request->id)
                ->update([
            'urlFoto' => $request->imagen]);
        Session::put('imagen', $request->imagen);
        return response()->json($request->all());
    }

    public function actualizarcontrasenia(Request $request) {
        $otro = DB::select("select contraseña FROM usuario where idEmpleado = $request->id and eliminado = 0");

        $contrasenia = $otro[0]->contraseña;
        $validator = "Error";
        if ($contrasenia == $request->passwordAntigua) {
            $actua = DB::table('usuario')
                    ->where('idEmpleado', $request->id)
                    ->update([
                'contraseña' => $request->password]);
            return response()->json(['success' => true,
                        'message' => 'ACTUALIZACION COMPLETADA!!']);
        } else {
            return response()->json([ 'success' => false,
                        'errors' => $validator]);
        }
    }

}
