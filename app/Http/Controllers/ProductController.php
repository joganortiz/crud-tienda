<?php

namespace App\Http\Controllers;

use App\Http\Helpers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    private $helpers;

    function __construct()
    {
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
        $data =  DB::table('products')
        ->join('categories', 'category', '=', 'categories.id')
        ->select('products.id', 'products.name', 'products.reference', 'products.price', 'products.status', 'categories.name as category', 'products.stock', 'products.image')
        ->where('products.delete', '1')
        ->where('categories.delete', '1')
        ->get();

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
        $name               = ($request->name) ? $request->name : '';
        $name               = $this->helpers->strClean($name);
        $reference          = $request->reference;
        $price              = is_numeric($request->price) ? (($request->price > 0) ? $request->price : 0) : 0;
        $weight             = $request->weight;
        $category           = is_numeric($request->category) ? (($request->category > 0) ? $request->category : 0) :0;
        $stock              = is_numeric($request->stock) ? (($request->stock > 0) ? $request->stock : 0) : 0;
        $status             = is_numeric($request->status) ? (($request->status == 1) ? '1' : '0') : '0';
        $image              = $request->image;             
        $date_registered    = $this->helpers->dateCurrent();
        $continue           = true;

        if(empty($name) || empty($reference) || empty($price) || empty($weight) || empty($category) || empty($stock)){
            $arrResponse = array("mensaje" => "Los campos con * son requeridos");
            $status = 404;
            $continue = false;
        }

        if ($continue) {
            if (!empty($request->id)) { #validamos que el id no venga vacio para ser una actualizacion
                $arrResponse = array("mensaje" => "No se puedo crear el producto intenta más tarde");
                $status = 404;
                $continue = false;
            }
        }

        if ($continue) {
            $resul = DB::table('products')
            ->where('reference', '=', $reference)
            ->exists();

            if ($resul) { #validamos que no este repetido el nombre de la categoria
                $arrResponse = array("mensaje" => "El producto con esta referencia ya existe en la base");
                $status = 404;
                $continue = false;
            }
        }

        if($continue){
            if($image != ''){
                $image = $this->helpers->procesarImagen($image);
            }

            $arrDataInser = array(
                "name"          => $name,
                "reference"     => $reference,
                "price"         => $price,
                "weight"        => $weight,
                "category"      => $category,
                "stock"         => $stock,
                "status"        => $status,
                "image"         => $image,
                "created_at"    => $date_registered
            );

            $result = Product::insert($arrDataInser);

            if ($result) { #validamos que el registro se haya insertado de manera exitosa
                $arrResponse = array("mensaje" => "El producto se creo de manera correcta");
                $status = 200;
            } else {
                $arrResponse = array("mensaje" => "Se presento un error al crear el producto, intenta más tarde");
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
        $data =  DB::table('products')
        ->select('id', 'name', 'reference', 'price', 'weight', 'category', 'stock', 'image', 'status')
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
        $name               = ($request->name) ? $request->name : '';
        $name               = $this->helpers->strClean($name);
        $reference          = $request->reference;
        $price              = is_numeric($request->price) ? (($request->price > 0) ? $request->price : 0) : 0;
        $weight             = $request->weight;
        $category           = is_numeric($request->category) ? (($request->category > 0) ? $request->category : 0) : 0;
        $stock              = is_numeric($request->stock) ? (($request->stock > 0) ? $request->stock : 0) : 0;
        $status             = is_numeric($request->status) ? (($request->status == '1') ? '1' : '0') : '0';
        $image              = $request->image;
        $date_registered    = $this->helpers->dateCurrent();
        $continue           = true;

        if (empty($name) || empty($reference) || empty($price) || empty($weight) || empty($category) || empty($stock)) {
            $arrResponse = array("mensaje" => "Los campos con * son requeridos");
            $status = 404;
            $continue = false;
        }

        if ($continue) {
            if (empty($request->id)) { #validamos que el id no venga vacio para ser una actualizacion
                $arrResponse = array("mensaje" => "No se puedo crear el producto intenta más tarde");
                $status = 404;
                $continue = false;
            }
        }

        if ($continue) {
            $resul = DB::table('products')
            ->where('reference', '=', $reference)
            ->where('id', '!=', $id)
            ->exists();

            if ($resul) { #validamos que no este repetido el nombre de la categoria
                $arrResponse = array("mensaje" => "El producto con esta referencia ya existe en la base");
                $status = 404;
                $continue = false;
            }
        }

        if ($continue) {
            if ($image != '') {
                $data =  DB::table('products')
                ->select('image')
                ->Where('id', $id)
                ->get();

                $ruta = '../public/img/'. $data[0]->image;
                if(file_exists($ruta)){
                    unlink($ruta);
                }

                $image = $this->helpers->procesarImagen($image);
            }

            $arrDataUpdate = array(
                "name"          => $name,
                "reference"     => $reference,
                "price"         => $price,
                "weight"        => $weight,
                "category"      => $category,
                "stock"         => $stock,
                "status"        => $status,
                "image"         => $image,
                "updated_at"    => $date_registered
            );

            $result =  DB::table('products')
                ->where('id', '=', $id)
                ->update($arrDataUpdate);

            if ($result) { #validamos que el registro se haya insertado de manera exitosa
                $arrResponse = array("mensaje" => "El producto se actualizo de manera correcta");
                $status = 200;
            } else {
                $arrResponse = array("mensaje" => "Se presento un error al actualizar el producto, intenta más tarde");
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
        #eliminamos el producto por ID
        $arrDataDelete = array(
            "delete"          => '0'
        );
        $data =  DB::table('products')
        ->where('id', '=', $id)
        ->update($arrDataDelete);

        if ($data) {
            $arrResponse = array("mensaje" => "El producto fue eliminado");
            $status = 200;
        } else {
            $arrResponse = array("mensaje" => "Se presento un error eliminar el producto, intenta más tarde");
            $status = 404;
        }

        return $this->helpers->respuestaJson($arrResponse, $status);
    }
}
