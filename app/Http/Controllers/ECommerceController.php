<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use DB;

class ECommerceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $page = null)
    {
        try {
            $products = Product::paginate($page);
            if($request->s == 'true'){
                $categories = Product::join('category', 'product.category', '=', 'category.id')
                                        ->groupBy(['category.id','category.name'])
                                        ->select(
                                            'category.id',
                                            'category.name',
                                            DB::raw('count(*) as count')
                                        )
                                        ->get();
                return response()->json(['status' => 1, 'message' => 'success', 'data' => $products, 'data2' => $categories]);
            }else{
                return response()->json(['status' => 1, 'message' => 'success', 'data' => $products]);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => 2, 'message' => 'Ocurrió un error en la solicitud, por favor, intente más tarde.'.$th->getMessage()]);
        }
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProducts(Request $request, $page = null)
    {
        try {
            $products = Product::paginate($page);
            return response()->json(['status' => 1, 'message' => 'success', 'data' => $products]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 2, 'message' => 'Ocurrió un error en la solicitud, por favor, intente más tarde.']);
        }
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
     * Filter result by Search.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, $page = null)
    {
        try {
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
                                    ->paginate($page);

            return response()->json(['status' => 1, 'message' => 'success', 'data' => $products]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 2, 'message' => 'Ocurrió un error en la solicitud, por favor, intente más tarde.']);
        }
    }
}
