<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class ECommerceController extends Controller
{
    /**
     * Retorna los productos y si (s = start) es true entonces se retornan tambien las categorias con las respectivas cantidades de productos.
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $page
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $page = null)
    {
        try {
            /** Se guarda el parametro o en la variable order */
            $order = $request->o;
            /** Se guarda el parametro s en la variable start */
            $start = $request->s;

            /** 
             * Se crea un objeto de la clase Product y se llama a la funcion getProducts dentro del objeto
             * que retornara los productos paginados y ordenados segun los parametros
             */
            $products = (new Product())->getProducts($page, $order);

            /** Si start es true, entonces se llama tambien a las categorias y rangos de precios */
            if($start == 'true'){
                /** Se obtienen todas las categorias de la BD con la cantidad de productos que existen por categoria */
                $categories = (new Category())->getCategories();
                /** Obtiene los rangos de precio minimo y maximo teniendo en cuenta todos los productos que existen */
                $pricesRange = (new Product())->getPricesRange();
                /** retorna el response con los productos, categorias y rangos de precios */
                return response()->json(['status' => 1, 'message' => 'success', 'data' => $products, 'data2' => $categories, 'data3' => $pricesRange]);
            }else{
                /** retorna solo los productos */
                return response()->json(['status' => 1, 'message' => 'success', 'data' => $products]);
            }
        } catch (\Throwable $th) {
            /** En caso de haber ocurrido un error entonces se devuelve un mensaje */
            return response()->json(['status' => 2, 'message' => 'Ocurrió un error en la solicitud, por favor, intente más tarde.']);
        }
        
    }

    /**
     * Retorna los productos filtrados por categoria y rango de precios.
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $page
     * @return \Illuminate\Http\Response
     */
    public function getProductsFilter(Request $request, $page = null)
    {
        try {
            /** Se guarda el parametro o en la variable order */
            $order = $request->options["order"];
            /** Se guarda el parametro categories */
            $categories = $request->options["categories"];
            /** Se crea un objeto y se guardan los precios minimo y maximo en ese objeto */
            $pricesRange = new \stdClass();
            $pricesRange->min = $request->options["price"]["min"];
            $pricesRange->max = $request->options["price"]["max"];

            /** 
             * Se crea un objeto de la clase Product y se llama a la funcion getProductsByFilters dentro del objeto
             * que retornara los productos paginados, ordenados y filtrados segun los parametros
             */
            $products = (new Product())->getProductsByFilters($page, $order, $categories, $pricesRange);
            
            /** retorna los productos filtrados por categorias y rangos de precios, ademas de paginados y ordenados */
            return response()->json(['status' => 1, 'message' => 'success', 'data' => $products]);
        } catch (\Throwable $th) {
            /** En caso de haber ocurrido un error entonces se devuelve un mensaje */
            return response()->json(['status' => 2, 'message' => 'Ocurrió un error en la solicitud, por favor, intente más tarde.']);
        }
    }

    /**
     * Filter result by Search.
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $page
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, $page = null)
    {
        try {
            /** 
             * Se guardan los parametros para ser enviados a la funcion que retorna los productos
             * el parametro o en la variable order 
             */
            $order = $request->o;
            /** Se guarda el parametro q en la variable search */
            $search = $request->q;
            /** Se guarda el parametro q en la variable start */
            $start = $request->s;

            /** 
             * Se crea un objeto de la clase Product y se llama a la funcion getProductsBySearch dentro del objeto
             * que retornara los productos paginados y ordenados segun los parametros, ademas de filtrados segun el texto a buscar
             */
            $products = (new Product())->getProductsBySearch($page, $order, $search);

            /** Si start es true, entonces se llama tambien a las categorias y rangos de precios */
            if($start == 'true'){
                /** Se obtienen todas las categorias de la BD con la cantidad de productos que existen por categoria */
                $categories = (new Category())->getCategories();
                /** Obtiene los rangos de precio minimo y maximo teniendo en cuenta todos los productos que existen */
                $pricesRange = (new Product())->getPricesRange();
                /** Retorna el response con los productos, categorias y rangos de precios */
                return response()->json(['status' => 1, 'message' => 'success', 'data' => $products, 'data2' => $categories, 'data3' => $pricesRange]);
            }else{
                /** retorna solo los productos */
                return response()->json(['status' => 1, 'message' => 'success', 'data' => $products]);
            }
        } catch (\Throwable $th) {
            /** En caso de haber ocurrido un error entonces se devuelve un mensaje */
            return response()->json(['status' => 2, 'message' => 'Ocurrió un error en la solicitud, por favor, intente más tarde.']);
        }
    }

    /**
     * Retorna los productos ordenados segun descuento prioritariamente y con limite de cantidad
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getSomeProducts(Request $request)
    {
        try {
            /** Obtiene y guarda el valor de la cantidad */
            $quantity = $request->quantity;
            /** 
             * Se crea un objeto de la clase Product y se llama a la funcion getSomeProducts dentro del objeto
             * que retornara los productos ordenados descendentemente por descuento principalmente y limitados por la cantidad
             */
            $products = (new Product())->getSomeProducts($quantity);
            
            /** retorna los productos ordenados y segun la cantidad ingresada en la variable quantity */
            return response()->json(['status' => 1, 'message' => 'success', 'data' => $products]);
        } catch (\Throwable $th) {
            /** En caso de haber ocurrido un error entonces se devuelve un mensaje */
            return response()->json(['status' => 2, 'message' => 'Ocurrió un error en la solicitud, por favor, intente más tarde.']);
        }
    }
}
