<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    /** Se especifica el nombre de la tabla en la BD */
    protected $table = 'product';
    /** Cantidad de productos por paginas por default */
    protected $perPage = 12;


    /**
     * Obtiene los productos de la BD ordenados y paginados segun los parametros
     *
     * @param  string  $page
     * @param  string  $order
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProducts($page, $order){
        /** Obtiene el orden que se va a implementar en base al numero que venga en el parametro order */
        $ordering = $this->getOrder($order);
        /** Consulta Eloquent con el orden SQL crudo y retornara los productos paginados */
        $products = Product::orderByRaw($ordering)
                                ->paginate($page);

        return $products;
    }

    /**
     * Obtiene los productos de la BD filtrados por categorias 
     * y rangos de precios ademas de ordenados y paginados
     *
     * @param  string  $page
     * @param  string  $order
     * @param  array   $categories
     * @param  object  $pricesRange
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProductsByFilters($page, $order, $categories, $pricesRange){
        /** Obtiene el orden que se va a implementar en base al numero que venga en el parametro order */
        $ordering = $this->getOrder($order);
        /** 
         * Inicializa la variable where con 1 = 1 para agregarla a la consulta en crudo
         * y dependiendo de si hay categorias por parametro realizar un where in 
         */
        $where = '1 = 1';
        if($categories != null){
            /** Si el parametro tiene categorias entonces se agregan al where */
            $where = 'category in ('.implode(",", $categories).')';
        }

        /** 
         * Consulta Eloquent con el orden,where between y el where como SQL crudo y retornara los productos paginados 
         * el rango de precios actua sobre el precio aplicando el descuento
         */
        $products = Product::whereBetween(DB::raw('price - ((price*discount)/100)'), [$pricesRange->min, $pricesRange->max])
                            ->whereRaw($where)
                            ->orderByRaw($ordering)
                            ->paginate($page);
        
        /** Retorna la coleccion de productos */
        return $products;
    }

    /**
     * Busca los productos que coincidan con el texto a buscar 
     *
     * @param  string  $page
     * @param  string  $order
     * @param  string   $search
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProductsBySearch($page, $order, $search){
        /** Obtiene el orden que se va a implementar en base al numero que venga en el parametro order */
        $ordering = $this->getOrder($order);

        /** 
         * Inicializa la variable where con 1 = 1 para agregarla a la consulta en crudo
         * y dependiendo de si hay texto a buscar, agregarlo al where
         */
        $where = '1 = 1';
        if($search){
            $where = "product.name like '%$search%' or ";
            $where .= "category.name like '%$search%'";
        }

        /** 
         * Consulta Eloquent con el orden y where como SQL crudo y retornara los productos paginados 
         */
        $products = Product::join('category', 'product.category', '=', 'category.id')
                                ->whereRaw($where)
                                ->select(
                                    'product.*',
                                    'category.name as category_name'
                                )
                                ->orderByRaw($ordering)
                                ->paginate($page);

        /** Retorna la coleccion de productos */
        return $products;
    }

    /**
     * Retorna productos segun la cantidad especificada en el parametro
     * ordenados descendentemente principalmente por descuento y luego por precio original
     *
     * @param  string  $quantity
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSomeProducts($quantity){
        /** obtiene una cantidad de productos segun quantity y ordenada descendentemente por el descuento */
        $products = Product::orderByRaw('product.discount DESC')
                                ->orderByRaw('product.price DESC')
                                ->limit($quantity)
                                ->get();
        return $products;
    }

    /**
     * Retorna los precios minimo y maximo considerando si tiene descuento 
     * y teniendo en cuenta todos los productos existentes
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPricesRange(){
        /** Se busca el minimo y maximo aplicando el calculo de descuento */
        $pricesRange = Product::select(
                                    DB::raw('MIN(product.price - ((product.price * product.discount) / 100)) as min'),
                                    DB::raw('MAX(product.price - ((product.price * product.discount) / 100)) as max')
                                )->get();

        return $pricesRange;
    }

    /**
     * Retorna la sentencia sql para el ordenamiento en base a un rango de numeros
     *
     * @return string
     */
    private function getOrder($order){
        /** Inicializa la variable a retornar con la sentencia sql por defecto */
        $ordering = 'product.name ASC';
        /** 
         * Si el parametro orden contiene algo, entonces asocia la sentencia sql para ese numero
         * En el caso de los precios se aplica el descuento 
         */
        if($order != null){
            switch ($order) {
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

        return $ordering;
    }
}
