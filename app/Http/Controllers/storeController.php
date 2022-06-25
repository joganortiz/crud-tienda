<?php

namespace App\Http\Controllers;

use App\Http\Helpers;
use App\Models\SalesProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class storeController extends Controller
{
    private $helpers;

    function __construct()
    {
        $this->helpers = new Helpers();
        session_start();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $array = isset($_SESSION["carrito"])? $_SESSION["carrito"]: array();
        return $this->helpers->respuestaJson($array, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id         = $request->idProduct;
        $amount     = $request->amount;
        $existe    = false;
        $continue   = true;

        
        if(!is_numeric($amount)){
            $arrResponse = array("mensaje" => "Debe tener uan antidad mayo a 0");
            $status = 404;
            $continue = false;
        }

        if($continue){
            $indiceArray = $amount_car = 0;
            if(isset($_SESSION["carrito"])){
                for ($i=0; $i<count(@$_SESSION["carrito"]); $i++) {
                    if($_SESSION["carrito"][$i]["id"] == $id){
                        $amount_car = $_SESSION["carrito"][$i]["amount"];
                        $existe = true;
                        $indiceArray = $i;
                        break;
                    }
                }
            }

            #consulta una producto por ID
            $data =  DB::table('products')
                ->select('id', 'name', 'reference', 'price', 'weight', 'category', 'stock', 'image', 'status')
                ->Where('id', $id)
                ->where('delete', '1')
                ->get();

            if($existe){
                $amount = $amount_car + $amount;
                if ($amount > $data[0]->stock) {
                    $arrResponse = array("mensaje" => "La cantidad supera el Stock del producto");
                    $status = 400;
                }else{
                    $_SESSION["carrito"][$indiceArray]["amount"] = $amount;
                    $arrResponse = array("mensaje" => "se agrego al carrito el producto");
                    $status = 200;
                }

            }else{
                $produCar = array(
                    "id"        => $id,
                    "amount"    => $amount,
                    "name"      => $data[0]->name,
                    "price"     => $data[0]->price,
                    "weight"    => $data[0]->weight,
                    "image"     => $data[0]->image
                );

                if($amount > $data[0]->stock){
                    $arrResponse = array("mensaje" => "La cantidad supera el Stock del producto");
                    $status = 400;
                }else{
                    $_SESSION["carrito"][] = $produCar;
                    $arrResponse = array("mensaje" => "se agrego al carrito el producto");
                    $status = 200;
                }

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
        $amount     = $request->cantidad;
        $amount_car = $indiceArray= 0;
        for ($i = 0; $i < count(@$_SESSION["carrito"]); $i++) {
            if ($_SESSION["carrito"][$i]["id"] == $id) {
                $amount_car = $_SESSION["carrito"][$i]["amount"];
                $indiceArray = $i;
                break;
            }
        }

        $amount = $amount_car + $amount;

        #consulta una producto por ID
        $data =  DB::table('products')
        ->select('id', 'stock')
            ->Where('id', $id)
            ->where('delete', '1')
            ->get();
        
            if($amount <= $data[0]->stock){
                $_SESSION["carrito"][$indiceArray]["amount"] = $amount;
                $arrResponse = array("mensaje" => "se actualizo la cantidad del producto");
                $status = 200;
            }else{
                $arrResponse = array("mensaje" => "La cantidad supera el Stock del producto");
                $status = 400;

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
        $indiceArray = 0;
        for ($i = 0; $i < count(@$_SESSION["carrito"]); $i++) {
            if ($_SESSION["carrito"][$i]["id"] == $id) {
                $indiceArray = $i;
                break;
            }
        }

        array_splice($_SESSION["carrito"], $indiceArray, 1);
        $arrResponse = array("mensaje" => "El producto fue elimnado del carrito");
        $status = 200;

        return $this->helpers->respuestaJson($arrResponse, $status);
    }

    public function sellProducts(){
        $sales     = uniqid();
        for ($i = 0; $i < count(@$_SESSION["carrito"]); $i++) {
            $id         = $_SESSION["carrito"][$i]["id"];
            $name       = $_SESSION["carrito"][$i]["name"];
            $amount     = $_SESSION["carrito"][$i]["amount"];

            $arrDataInser = array(
                "compra"        => $sales,
                "idProduct"     => $id,
                "nameProduct"   => $name,
                "amount"        => $amount,
                "filled"        => '1',
                "created_at"    => $this->helpers->dateCurrent()
            );

            $resul = SalesProducts::insert($arrDataInser);

            if($resul){
                $data =  DB::table('products')
                ->select('id', 'stock')
                ->Where('id', $id)
                ->where('delete', '1')
                ->get();

                $new_amount = $data[0]->stock - $amount;

                $arrDataUpdate = array(
                    "stock"          => $new_amount
                );

                $result =  DB::table('products')
                ->where('id', '=', $id)
                ->update($arrDataUpdate);
            }
        }

        unset($_SESSION["carrito"]);

        $arrResponse = array("mensaje" => "La venta se realizo exitosa");
        $status = 200;

        return $this->helpers->respuestaJson($arrResponse, $status);
    }


    public function countProductCar()
    {
        $array = isset($_SESSION["carrito"]) ? count($_SESSION["carrito"]) : 0;;
        return $this->helpers->respuestaJson($array, 200);
    }
}
