<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    /** Se especifica el nombre de la tabla en la BD */
    protected $table = 'category';

    /**
     * Obtiene todas las categorias
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCategories(){
        /** Consulta eloquent para obtener todas las categorias y cuantos productos hay por cada una */
        $categories = Category::join('product', 'product.category', '=', 'category.id')
                                        ->groupBy(['category.id','category.name'])
                                        ->select(
                                            'category.id',
                                            'category.name',
                                            DB::raw('count(*) as count')
                                        )
                                        ->get();
        
        return $categories;
    }
}
