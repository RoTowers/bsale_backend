El siguiente bloque contiene las distintas rutas de la API:

```php
Route::get('/products/{page?}', 'App\Http\Controllers\ECommerceController@index');
Route::get('/some', 'App\Http\Controllers\ECommerceController@getSomeProducts');
Route::post('/products/{page?}', 'App\Http\Controllers\ECommerceController@getProductsFilter');
Route::get('/search/{page?}', 'App\Http\Controllers\ECommerceController@search');
```

Para acceder a los productos se debe utilizar el siguiente enlace `(los productos se retornarán con una paginación por defecto de 12 productos por página)`:

`api/products`

petición que recepcionará la siguiente ruta:

```php
Route::get('/products/{page?}', 'App\Http\Controllers\ECommerceController@index');
```

 El cual admite algunos parámetros opcionales como `page`, `o` y `s`. También retornará un JSON con la siguiente estructura:
 
    

```JSON
{
    "status": 1,
    "message": "success",
    "data": {
        "current_page": 2,
        "data": [
            {
                "id": 5,
                "name": "ENERGETICA MR BIG",
                "url_image": "https://dojiw2m9tvv09.cloudfront.net/11132/product/misterbig3308256.jpg",
                "price": 1490,
                "discount": 20,
                "category": 1
            }
        ],
        "first_page_url": "https://fierce-woodland-71648.herokuapp.com/api/products?page=1",
        "from": 13,
        "last_page": 5,
        "last_page_url": "https://fierce-woodland-71648.herokuapp.com/api/products?page=5",
        "links": [
            {
                "url": "https://fierce-woodland-71648.herokuapp.com/api/products?page=1",
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "https://fierce-woodland-71648.herokuapp.com/api/products?page=1",
                "label": "1",
                "active": false
            },
            {
                "url": "https://fierce-woodland-71648.herokuapp.com/api/products?page=2",
                "label": "2",
                "active": true
            },
            {
                "url": "https://fierce-woodland-71648.herokuapp.com/api/products?page=3",
                "label": "3",
                "active": false
            },
            {
                "url": "https://fierce-woodland-71648.herokuapp.com/api/products?page=4",
                "label": "4",
                "active": false
            },
            {
                "url": "https://fierce-woodland-71648.herokuapp.com/api/products?page=5",
                "label": "5",
                "active": false
            },
            {
                "url": "https://fierce-woodland-71648.herokuapp.com/api/products?page=3",
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": "https://fierce-woodland-71648.herokuapp.com/api/products?page=3",
        "path": "https://fierce-woodland-71648.herokuapp.com/api/products",
        "per_page": 12,
        "prev_page_url": "https://fierce-woodland-71648.herokuapp.com/api/products?page=1",
        "to": 24,
        "total": 57
    }
}
```
 
 Ej:
 
 `?page=2` -> Si el parametro `page` tiene un valor numerico se podrá acceder a pagina especifica de la paginacion.
 
  `?o=3` -> Si el parametro `o` tiene un valor numerico del `1` al  `4` se retornarán los productos ordenados por `1 = nombre ascendentemente`, `2 = nombre descendentemente`, `3 = precio ascendentemente` y `4 = precio descendentemente`.
  
  `?s=true` -> Si el parámetro `s` tiene un valor `true` retornara el JSON agregando las categorias de productos que existen con sus respectivas cantidades
 