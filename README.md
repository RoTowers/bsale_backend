**Lista de contenidos**

### Estructura JSON

Al realizar una petición HTTP, el servicio retornará un JSON con la siguiente estructura:
```JSON
{
    "status": 1,
    "message": "success",
    "data": {
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
    }
}
```
- **status**, status de la solicitud,`1 = success`, `2 = error`.
- **message**, mensaje de estado de solicitud.
- **data**, el listado de productos, donde se encuentran los siguientes parámetros:
	- **id**, identificador del producto en la base de datos.
	- **name**, nombre del producto
	- **url_image**, enlace a la imagen del producto,
	- **price**, precio del producto
	- **discount**, descuento en porcentaje para el producto,
	- **category**, hace referencia al id de la categoria asociada
	
Esta estructura es una base, ya que en otros endpoints retornará esta estructura con algunos datos agregados como los siguientes:

#### Estructura JSON de Categorías y Rangos

A la estructura base se le agregarán los siguientes valores en la data del JSON para retornar las categorías y rangos de precios:

```json
"data2": [
        {
            "id": 1,
            "name": "bebida energetica",
            "count": 8
        },
        {
            "id": 2,
            "name": "pisco",
            "count": 21
        },
        {
            "id": 3,
            "name": "ron",
            "count": 13
        },
        {
            "id": 4,
            "name": "bebida",
            "count": 7
        },
        {
            "id": 5,
            "name": "snack",
            "count": 5
        },
        {
            "id": 6,
            "name": "cerveza",
            "count": 2
        },
        {
            "id": 7,
            "name": "vodka",
            "count": 1
        }
    ],
    "data3": [
        {
            "min": 500,
            "max": 19990
        }
    ]
```

- **id**, identificador de la categoría en la base de datos.
- **name**, nombre de la categoría.
- **count**, cantidad de productos por categoría.
- **min**, precio mínimo de todos los productos.
- **max**, precio máximo de todos los productos.


#### Estructura JSON de Paginación
A la estructura base se le agregarán los siguientes valores en la data del JSON para realizar una paginación:

```JSON
{
    "status": 1,
    "message": "success",
    "data": {
        "current_page": 2,
        "data": [...],
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

- **current_page**, página en la que está obteniendo los datos paginados.
- **first_page_url**, enlace de la primera página.
- **from**, numero de inicial del rango de productos que está retornando la página actual.
- **last_page**, número de la última página.
- **last_page_url**, url de la última página.
- **links**, provee un arreglo con los links a cada página como los siguientes,
	- **url**, url de la página.
	- **label**, texto que hace referencia al numero de pagina que llevará el enlace.
	- **active**, es `true` cuando es la página actual, de lo contrario es `false`
- **next_page_url**, siguiente página desde la actual.
- **path**, url del `endpoint`.
- **per_page**, cuantos productos se retornarán por página.
- **prev_page_url**, url de la página previa a la actual.
- **to**, número final del rango de productos que está retornando la página actual.
- **total**, total de productos retornados en todas las páginas.

### Rutas

El siguiente bloque contiene las distintas rutas de la API:

```php
Route::get('/products/{page?}', 'App\Http\Controllers\ECommerceController@index');
Route::get('/some', 'App\Http\Controllers\ECommerceController@getSomeProducts');
Route::post('/products/{page?}', 'App\Http\Controllers\ECommerceController@getProductsFilter');
Route::get('/search/{page?}', 'App\Http\Controllers\ECommerceController@search');
```
### GET Listado de Todos los Productos
`GET api/products` accederá a todos los productos ***(los productos se retornarán con una paginación por defecto de 12 productos por página)***

Petición que recepcionará la siguiente ruta:

```php
Route::get('/products/{page?}', 'App\Http\Controllers\ECommerceController@index');
```

**Parámetros**
 
 - ***page***, si el parametro `page` tiene un valor numerico se podrá acceder a pagina especifica de la paginacion.
 
- ***o***, si el parametro `o` tiene un valor numerico del `1` al  `4` se retornarán los productos ordenados por `1 = nombre ascendentemente`, `2 = nombre descendentemente`, `3 = precio ascendentemente` y `4 = precio descendentemente`.
  
- ***s***, si el parámetro `s` tiene un valor `true` retornara el JSON agregando las categorias de productos que existen con sus respectivas cantidades
 

**Ejemplo**

- `GET api/products?page=2&o=3&s=true`

------------

### GET Listado de Productos en Oferta

`GET api/some` accederá a algunos productos en oferta, los datos serán retornados ordenados descendentemente por descuento. El cual recepcionará la siguiente ruta:

```php
Route::get('/some', 'App\Http\Controllers\ECommerceController@getSomeProducts');
```

El endpoint admite el parámetro `quantity`.

- ***quantity***, limita la cantidad de productos retornados, debe ser un valor numérico entero.

**Ejemplo:**

- `GET api/some?quantity=6`

### GET Listado de Productos con Búsqueda

`GET api/search` accederá a algunos productos con la opción de ser filtrados. Será recepcionado por la siguiente ruta:

```php
Route::get('/search/{page?}', 'App\Http\Controllers\ECommerceController@search');
```

**Parámetros**
- ***page***, si el parametro `page` tiene un valor numerico se podrá acceder a pagina especifica de la paginacion.
- ***o***, si el parametro `o` tiene un valor numerico del `1` al  `4` se retornarán los productos ordenados por `1 = nombre ascendentemente`, `2 = nombre descendentemente`, `3 = precio ascendentemente` y `4 = precio descendentemente`.
- ***q***, texto a buscar.
- ***s***, si el parámetro `s` tiene un valor `true` retornara el JSON agregando las categorias de productos que existen con sus respectivas cantidades

**Ejemplo**
- `GET api/search?page=2&q=pisco&s=true&o=3`


### POST Listado de Productos con Filtros

`POST api/products` accederá a algunos productos con la opción de ser filtrados. Será recepcionado por la siguiente ruta:

```php
Route::post('/products/{page?}', 'App\Http\Controllers\ECommerceController@getProductsFilter');
```
**Parámetros**
- ***page***, número de página a retornar.
- ***options***, objeto con opciones de parametros
	- ***order***, ordenamiento de los datos `1,2,3 o 4`
	- ***category***, arreglo de Id's de categorias
	- ***price***, objeto con los valores de los precios mínimo y máximo
		- ***min***, precio mínimo.
		- ***max***, precio máximo.

**Ejemplos**

Se debe enviar un JSON con la siguiente estructura.

```json
{
   "options": {
		"categories": [2,3,4],
		"price": {
			"min": 500,
			"max": 19990
		},
		"order": 4
   }
}
```