<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use DB;

class ECommerceController extends Controller
{
    /**
     * Retorna los productos y si (s = start) es true entonces se retornan tambien las categorias con las respectivas cantidades de productos.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $page = null)
    {
        try {
            $ordering = 'product.name ASC';
            if($request->o != null){
                switch ($request->o) {
                    case '1':
                        $ordering = 'product.name ASC';
                        break;

                    case '2':
                        $ordering = 'product.name DESC';
                        break;

                    case '3':
                        $ordering = '(price - ((price*discount)/100)) ASC';
                        break;

                    case '4':
                        $ordering = '(price - ((price*discount)/100)) DESC';
                        break;
                    
                    default:
                        $ordering = 'product.name ASC';
                        break;
                }
            }

            $products = Product::orderByRaw($ordering)
                                ->paginate($page);
            if($request->s == 'true'){
                $categories = Product::join('category', 'product.category', '=', 'category.id')
                                        ->groupBy(['category.id','category.name'])
                                        ->select(
                                            'category.id',
                                            'category.name',
                                            DB::raw('count(*) as count')
                                        )
                                        ->get();
                $pricesRange = Product::select(
                                            DB::raw('MIN(product.price - ((product.price * product.discount) / 100)) as min'),
                                            DB::raw('MAX(product.price - ((product.price * product.discount) / 100)) as max')
                                        )->get();
                return response()->json(['status' => 1, 'message' => 'success', 'data' => $products, 'data2' => $categories, 'data3' => $pricesRange]);
            }else{
                return response()->json(['status' => 1, 'message' => 'success', 'data' => $products]);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => 2, 'message' => 'Ocurrió un error en la solicitud, por favor, intente más tarde.']);
        }
        
    }

    /**
     * Retorna los productos filtrados por categoria y rango de precios.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProductsFilter(Request $request, $page = null)
    {
        try {
            $ordering = 'product.name ASC';
            if(array_key_exists('order', $request->options) && $request->options["order"] != null){
                switch ($request->options["order"]) {
                    case '1':
                        $ordering = 'product.name ASC';
                        break;

                    case '2':
                        $ordering = 'product.name DESC';
                        break;

                    case '3':
                        $ordering = '(price - ((price*discount)/100)) ASC';
                        break;

                    case '4':
                        $ordering = '(price - ((price*discount)/100)) DESC';
                        break;
                    
                    default:
                        $ordering = 'product.name ASC';
                        break;
                }
            }

            $where = '1 = 1';
            if(array_key_exists('categories', $request->options) && $request->options["categories"] != null){
                $where = 'category in ('.implode(",", $request->options["categories"]).')';
            }
            $products = Product::whereBetween(DB::raw('price - ((price*discount)/100)'), [$request->options["price"]["min"], $request->options["price"]["max"]])
                                ->whereRaw($where)
                                ->orderByRaw($ordering)
                                ->paginate($page);
            
            return response()->json(['status' => 1, 'message' => 'success', 'data' => $products]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 2, 'message' => 'Ocurrió un error en la solicitud, por favor, intente más tarde.']);
        }
    }

    /**
     * Filter result by Search.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, $page = null)
    {
        try {
            $ordering = 'product.name ASC';
            if($request->o != null){
                switch ($request->o) {
                    case '1':
                        $ordering = 'product.name ASC';
                        break;

                    case '2':
                        $ordering = 'product.name DESC';
                        break;

                    case '3':
                        $ordering = '(product.price - ((product.price*product.discount)/100)) ASC';
                        break;

                    case '4':
                        $ordering = '(product.price - ((product.price*product.discount)/100)) DESC';
                        break;
                    
                    default:
                        $ordering = 'product.name ASC';
                        break;
                }
            }

            $search = "".$request->q;
            $where = '1 = 1';
            if($search){
                $where = "product.name like '%$search%' or ";
                $where .= "category.name like '%$search%'";
            }
            $products = Product::join('category', 'product.category', '=', 'category.id')
                                    ->whereRaw($where)
                                    ->select(
                                        'product.*',
                                        'category.name as category_name'
                                    )
                                    ->orderByRaw($ordering)
                                    ->paginate($page);

            if($request->s == 'true'){
                $categories = Product::join('category', 'product.category', '=', 'category.id')
                                        ->groupBy(['category.id','category.name'])
                                        ->select(
                                            'category.id',
                                            'category.name',
                                            DB::raw('count(*) as count')
                                        )
                                        ->get();
                $pricesRange = Product::select(
                                            DB::raw('MIN(product.price - ((product.price * product.discount) / 100)) as min'),
                                            DB::raw('MAX(product.price - ((product.price * product.discount) / 100)) as max')
                                        )->get();
                return response()->json(['status' => 1, 'message' => 'success', 'data' => $products, 'data2' => $categories, 'data3' => $pricesRange]);
            }else{
                return response()->json(['status' => 1, 'message' => 'success', 'data' => $products]);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => 2, 'message' => 'Ocurrió un error en la solicitud, por favor, intente más tarde.']);
        }
    }

    public function getSomeProducts(Request $request)
    {
        try {
            $products = Product::orderByRaw('product.discount DESC')
                                ->orderByRaw('product.price DESC')
                                ->limit($request->quantity)
                                ->get();
            
            return response()->json(['status' => 1, 'message' => 'success', 'data' => $products]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 2, 'message' => 'Ocurrió un error en la solicitud, por favor, intente más tarde.']);
        }
    }
}
