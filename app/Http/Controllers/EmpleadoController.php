<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Empleado;
use App\Http\Requests;

class EmpleadoController extends Controller {

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
        DB::table('empleado')->insert(['nombre' => $request->nombre,
            'fechaNacimiento' => $request->fechaNacimiento,
            'genero' => $request->genero,
            'telefonoFijo' => $request->telefonoFijo,
            'celular' => $request->celular,
            'docIdentidad' => $request->docIdentidad,
            'correoElectronico' => $request->correoElectronico,
            'idCargo' => $request->idCargo,
            'idTurno' => $request->idTurno,
            'comision' => $request->comision
        ]);
        return response()->json($request->all());
    }

// 
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
        $dato = Empleado::find($id);
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
        $actua = DB::table('empleado')
                ->where('id', $id)
                ->update(['nombre' => $request->nombre,
            'fechaNacimiento' => $request->fechaNacimiento,
            'genero' => $request->genero,
            'telefonoFijo' => $request->telefonoFijo,
            'celular' => $request->celular,
            'docIdentidad' => $request->docIdentidad,
            'correoElectronico' => $request->correoElectronico,
            'idCargo' => $request->idCargo,
            'idTurno' => $request->idTurno,
            'comision' => $request->comision]);
        return response()->json(["actualizado" => $request->all()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $actua = DB::table('empleado')->where('id', $id)->update(['eliminado' => 1]);
        $unsuario = DB::table('usuario')->where('idEmpleado', $id)->update(['eliminado' => 1]);
        return response()->json(["mensaje" => "listo"]);
    }

    public function listarempleados() {
        $otro = DB::table('empleado')->select('empleado.comision', 'empleado.nombre', 'empleado.genero', 'empleado.docIdentidad', 'cargo.nombre as cargo', 'empleado.id')
                ->join('cargo', 'cargo.id', '=', 'empleado.idCargo')
                ->where('empleado.eliminado', 0)
                ->get('nombre', 'genero', 'docIdentidad', 'cargo', 'id', 'comision');
        return response()->json($otro);
    }

//    public function listarempleadosresporte() {
//        $otro = $results = DB::select("SELECT id,nombre from empleado WHERE empleado.eliminado=0 and empleado.nombre<> 'admin'
// ");
//        return response()->json($otro);
//    }

    public function listarempleadosresporte() {
        $otro = $results = DB::select("SELECT id,nombre from empleado,usuario WHERE empleado.eliminado=0
            AND empleado.id = usuario.idEmpleado
 ");
        return response()->json($otro);
    }

    

}
