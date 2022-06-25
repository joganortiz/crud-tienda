<?php

namespace App\Http\Controllers;

use App\Http\Helpers;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    private $helpers;

    function __construct(){
        $this->helpers = new Helpers();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        #Listamos todas las categorias
        $data =  DB::table('categories')
        ->select('id','name', 'description', 'status')
        ->where('delete', '1')->get();

        return $this->helpers->respuestaJson($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name           = ($request->name) ? $request->name : '';
        $name           = $this->helpers->strClean($name);
        $description    = $request->description;
        $status         = is_numeric($request->status)?(($request->status ==1)?'1':'0'):'0';
        $continue       = true;
        
        if(empty($name)){ #validamos que el nombre no venga vacio
            $arrResponse = array("mensaje" => "El nombre es requerido");
            $status = 404;
            $continue = false;
        }

        if($continue){
            if(!empty($request->id)){ #validamos que el id venga vacio para ser un registro nuevo
                $arrResponse = array("mensaje" => "No se puedo insertar la categoria intenta más tarde");
                $status = 404;
                $continue = false;
            }
        }

        if($continue){ 
            $resul = DB::table('categories')
            ->select('name')
            ->where('name', '=', $name)->exists();

            if($resul){ #validamos que no este repetido el nombre de la categoria
                $arrResponse = array("mensaje" => "La categoria ya existe en la base");
                $status = 404;
                $continue = false;
            }
        }

        if($continue){ #si todo esta bien procedemos a insertar
            $arrDataInser = array(
                "name"          => $name,
                "description"   => $description,
                "status"        => $status,
                "created_at"    => $this->helpers->fechaActual()
            );

            $resul = Category::insert($arrDataInser);

            if($resul){ #validamos que el registro se haya insertado de manera exitosa
                $arrResponse = array("mensaje" => "La categoria se creo de manera correcta");
                $status = 200;
            }else{
                $arrResponse = array("mensaje" => "Se presento un error al crear la categoria, intenta más tarde");
                $status = 404;
            }
        }

        return $this->helpers->respuestaJson($arrResponse, $status);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        #consulta una categoria por ID
        $data =  DB::table('categories')
        ->select('id', 'name', 'description', 'status')
        ->Where('id', $id)
        ->where('delete', '1')
        ->get();

        return $this->helpers->respuestaJson($data[0]);
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
        $name           = ($request->name) ? $request->name : '';
        $name           = $this->helpers->strClean($name);
        $description    = $request->description;
        $status         = is_numeric($request->status) ? (($request->status == 1) ? '1' : '0') : '0';
        $continue       = true;

        if (empty($name)) { #validamos que el nombre no venga vacio
            $arrResponse = array("mensaje" => "El nombre es requerido");
            $status = 404;
            $continue = false;
        }

        if ($continue) {
            if (empty($request->id)) { #validamos que el id no venga vacio para ser una actualizacion
                $arrResponse = array("mensaje" => "No se puedo actualizar la categoria intenta más tarde");
                $status = 404;
                $continue = false;
            }
        }

        if ($continue) {
            $resul = DB::table('categories')
                ->where('name', '=', $name)
                ->where('id', '!=', $id)
                ->exists();

            if ($resul) { #validamos que no este repetido el nombre de la categoria
                $arrResponse = array("mensaje" => "La categoria ya existe en la base");
                $status = 404;
                $continue = false;
            }
        }

        if ($continue) {
            $arrDataUpdate = array(
                "name"          => $name,
                "description"   => $description,
                "status"        => $status,
                "updated_at"    => $this->helpers->fechaActual()
            );

            $data =  DB::table('categories')
                ->where('id', '=', $id)
                ->update($arrDataUpdate);

            if ($data) { #validamos que la actualizacion se haya realizado de manera exitosa
                $arrResponse = array("mensaje" => "La categoria se actualizo de manera correcta");
                $status = 200;
            } else {
                $arrResponse = array("mensaje" => "Se presento un error al actualizar la categoria, intenta más tarde");
                $status = 404;
            }
        }

        return $this->helpers->respuestaJson($arrResponse, $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        #eliminamos la Categoria por ID
        $arrDataDelete = array(
            "delete"          => '0'
        );
        $data =  DB::table('categories')
        ->where('id', '=', $id)
        ->update($arrDataDelete);

        if ($data) {
            $arrResponse = array("mensaje" => "La categoria fue eliminada");
            $status = 200;
        } else {
            $arrResponse = array("mensaje" => "Se presento un error al crear la categoria, intenta más tarde");
            $status = 404;
        }

        return $this->helpers->respuestaJson($arrResponse, $status);
    }
}
