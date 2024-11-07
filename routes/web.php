<?php

use App\Http\Controllers\CompetitorController;
use App\Http\Controllers\CronsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatatableController;
use App\Http\Controllers\DistibaseController;
use App\Http\Controllers\DivisonledController;
use App\Http\Controllers\GfcController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\PrivadoController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Mail\MonitorPrecios;
use App\Models\Competitor;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', function () {
        return redirect('/dashboard');
    });

    Route::get('/dashboard', function () {
        return redirect('gasfriocalor/dashboard');
    });

    Route::get('usuario/perfil', [UserController::class, 'perfil'])->name('perfil');
    Route::post('usuario/perfil', [UserController::class, 'perfilUpdate'])->name('perfil.update');

    Route::get('usuarios', [UserController::class,'usuarios'])->name('usuarios.index');
    Route::post('usuarios/datatable', [UserController::class,'usuariosDT'])->name('admin.datatable.usuarios');
    Route::get('usuarios/store', [UserController::class,'create'])->name('usuarios.create');
    Route::post('usuarios', [UserController::class,'store'])->name('usuarios.store');
    Route::get('/usuarios/editar/{usuario}', [UserController::class,'edit'])->name('usuarios.edit');
    Route::put('/usuarios/editar/{usuario}', [UserController::class,'update'])->name('usuarios.update');
    Route::delete('/usuarios/{usuario}', [UserController::class,'destroy'])->name('usuarios.destroy');
    Route::post('/usuarios/status/{usuario}', [UserController::class,'status'])->name('usuarios.status');

    Route::prefix('gasfriocalor')->group(function () {        
        Route::get('/dashboard', [GfcController::class, 'dashboard'])->name('gfc.dashboard');
        Route::post('/dashboard/rango-fechas', [GfcController::class, 'dashboard'])->name('gfc.dashboards.dates');

        Route::get('/mejores-productos', [GfcController::class, 'bestProducts'])->name('gfc.bestproducts');
        Route::post('/mejores-productos/categorias', [GfcController::class, 'bestProductsCategories'])->name('gfc.bestProducts.categories');
        Route::post('/mejores-productos/rango-fechas', [GfcController::class, 'bestProducts'])->name('gfc.bestproducts.dates');

        Route::get('/monitor-precios', [GfcController::class, 'monPrice'])->name('gfc.monprice');
        Route::get('/monitor-precios/exportar/csv', [MonitorController::class, 'exportarCsv'])->name('monitor.exportar.csv');

        Route::get('/oportunidad-ventas', [GfcController::class, 'oportunidadVentas'])->name('gfc.oportunidad.ventas');
        Route::post('/oportunidad/contactar/{id}', [GfcController::class, 'oportunidadContactar'])->name('gfc.oportunidad.contactar');

        Route::post('datatable/mejores-categorias', [GfcController::class, 'datatableMejoresCategorys'])->name('gfc.datatable.bescategorys');
        Route::get('datatable/monitor-precios', [GfcController::class, 'datatable'])->name('gfc.datatable.monprice');
        Route::post('datatable/oportunidades-ventas', [DatatableController::class, 'datatableOportunidadesVentas'])->name('gfc.datatable.oportunidades.ventas');
        Route::get('datatable/mejores-productos', [GfcController::class, 'datatableMejoresProductos'])->name('gfc.datatable.bestproducts');
        Route::get('datatable/mejores-aires', [GfcController::class, 'datatableMejoresAires'])->name('gfc.datatable.bestaires');
        Route::get('datatable/mejores-calderas', [GfcController::class, 'datatableMejoresCalderas'])->name('gfc.datatable.bestcalderas');
        Route::get('datatable/mejores-aerotermia', [GfcController::class, 'datatableMejoresAerotermia'])->name('gfc.datatable.bestaerotermia');
        Route::get('datatable/mejores-ventilacion', [GfcController::class, 'datatableMejoresVentilacion'])->name('gfc.datatable.bestventilacion');
        Route::get('datatable/mejores-caletadoresgas', [GfcController::class, 'datatableMejoresCaletadoresgas'])->name('gfc.datatable.bestcaletadoresgas');
        Route::get('datatable/mejores-termoselectricos', [GfcController::class, 'datatableMejoresTermoselectricos'])->name('gfc.datatable.besttermoselectricos');
        Route::get('datatable/mejores-superventas', [GfcController::class, 'datatableMejoresSuperventas'])->name('gfc.datatable.bestsuperventas');
        Route::post('datatable/nunca-vendidos', [DatatableController::class, 'nuncaVendidos'])->name('gfc.datatable.nunca.vendidos');
        
        Route::get('competidor/nuevo', [CompetitorController::class, 'create'])->name('gfc.competidors.create');
        Route::post('competidor/nuevo', [CompetitorController::class, 'store'])->name('gfc.competidors.store');
        
        Route::get('producto/nuevo', [ProductController::class, 'create'])->name('gfc.products.create');
        Route::post('producto/nuevo', [ProductController::class, 'store'])->name('gfc.products.store');
        Route::get('producto/{product}/edit', [ProductController::class, 'edit'])->name('gfc.products.edit');
        Route::put('producto/{product}', [ProductController::class, 'update'])->name('gfc.products.update');
        Route::delete('product/{product}', [ProductController::class, 'destroy'])->name('gfc.products.delete');
        
        Route::get('cambio-precios', [PrivadoController::class, 'cambioPrecios'])->name('gfc.privado.cambio-precios');
        Route::any('desbloquear-pedidos', [PrivadoController::class, 'desbloquearPedidos'])->name('gfc.privado.desbloquear-pedidos');
        Route::any('descargar-excels', [PrivadoController::class, 'descargarExcels'])->name('gfc.privado.descargar-excels');
        Route::get('upload-dtocompra', [PrivadoController::class, 'uploadDtocompra'])->name('gfc.privado.upload_dtocompra');
        Route::any('consulta-stock', [PrivadoController::class, 'consultaStockNetosEditor'])->name('gfc.privado.consulta_stock-netos_editor');
        Route::any('datatable/consulta-stock', function (Request $request) {
            include(app_path() . '/privado/controlador.php'); 
        });
        
        Route::get('productos-nunca-vendidos', [GfcController::class, 'productosNoVendidos'])->name('gfc.productos.novendidos');
        Route::get('productos-nunca-vendidos/exportar', [ProductController::class, 'productosNuncaVendidosExportar'])->name('productos.nunca.vendidos.exportar.csv');
        
    });
    
    Route::prefix('distribase')->group(function () {
        Route::get('/dashboard', [DistibaseController::class, 'dashboard'])->name('distribase.dashboard');
        Route::get('/socio/{partner}', [DistibaseController::class, 'partner'])->name('distribase.partner');

        Route::post('datatable/no-match', [DatatableController::class, 'nomatch'])->name('distribase.datatable.nomatch');
        Route::post('datatable/partners', [DatatableController::class, 'partners'])->name('distribase.datatable.partners');
    });
    
    Route::prefix('divisonled')->group(function () {
        Route::get('/dashboard', [DivisonledController::class, 'dashboard'])->name('divisonled.dashboard');
        //Route::get('/monitor-precios', [DivisonledController::class, 'monitor'])->name('divisonled.monitor');

        Route::get('competidor/nuevo', [CompetitorController::class, 'createDivisonled'])->name('divisonled.competidors.create');
        Route::post('competidor/nuevo', [CompetitorController::class, 'storeDivisonled'])->name('divisonled.competidors.store');

        Route::get('producto/nuevo', [ProductController::class, 'createDivisonled'])->name('divisonled.products.create');
        Route::post('producto/nuevo', [ProductController::class, 'storeDivisonled'])->name('divisonled.products.store');
        Route::get('producto/{product}/edit', [ProductController::class, 'editDivisonled'])->name('divisonled.products.edit');
        Route::put('producto/{product}', [ProductController::class, 'updateDivisonled'])->name('divisonled.products.update');
        Route::delete('product/{product}', [ProductController::class, 'destroyDivisonled'])->name('divisonled.products.delete');

        Route::get('datatable/monitor-precios', [DivisonledController::class, 'datatable'])->name('divisonled.datatable.monprice');
    });
});

Route::get('/monitor/scrap/{parte}', [MonitorController::class,'scrap'])->name('monitor.scrap');
Route::get('/monitor/scrap/product/{product}', [MonitorController::class,'scrapOne'])->name('monitor.scrap');

Route::get('gfc/dtos-compras', [CronsController::class, 'dtosCompras']);

Route::any('test/prueba-api', function (Request $request) {
   Log::info('Llamado a la api '.date('d-m-Y h:i')); 
   Log::info(var_dump($request)); 
});

/* Route::get('test/scrap-divisonled', function () {
    $web = new \Spekulatius\PHPScraper\PHPScraper;
    
    $web->go('https://www.divisionled.com/apliques-y-balizas-led/27343-aplique-de-pared-led-cubo-negro.html');
    //DivisonLed $string = $web->filter("//div[@class='current-price']//span[@itemprop='price']")->text();
    //EfectoLED $string = $web->filter("//div[@id='addToCart']")->text();
    //Lamparas $string = $web->filter("//div[@class='current-price']//span[@itemprop='price']")->text();
    $nombre = $web->filter("//h1[@itemprop='name']")->text();
    $id = $web->filter("//span[@itemprop='sku']")->text();
    $string = Str::remove('€', $string);
    $string = Str::remove(' ', $string);
    $string = Str::replace('.', '', $string);
    $string = Str::replace(',', '.', $string);
    $price = floatval($string);
    return $nombre;
}); */

Route::get('test/listado-reffabricante-marcas', function () {
    $response = Http::withOptions([
            'verify' => false,
            'http_errors' => false,
        ])
        ->withHeaders([
            'Authorization' => 'd2f77a06-9c1e-11e8-b6f8-005056b03ae4',
            'Content-Type'	=> 'application/json'
        ])
        ->timeout(10)
        ->retry(0, 100)
        ->get('https://api.distribase.online/v1/marcas');

    if ($response->serverError()) {
        return "Fallo la consulta de las marcas";
    } else {
        $marcas = collect();
        if ($response->status() == 200) {
            $response = $response->json();                
            foreach ($response as $value) {
                $marcas->push([
                    'idmarc' => $value['idmarc'],
                    'nombre' => $value['nombre'],
                ]);
            }
        } else {
            return "Fallo la consulta de las marcas";
        }
    }

    function csvtoarray($archivo,$delimitador = ","){

        if(!empty($archivo) && !empty($delimitador) && is_file($archivo)){
            $array_total = array();
            $fp = fopen($archivo,"r");
            while ($data = fgetcsv($fp, 150, $delimitador)){
                $num = count($data);
                //$array_total[] = array_map("utf8_encode",$data);
                $array_total[] = array_map(function ($item) {
                    return mb_convert_encoding($item, 'UTF-8', mb_detect_encoding($item));
                },$data);
            }
            fclose($fp);
            return $array_total;
        }
        else
            return false;
    }

    $arraycsv = csvtoarray("Articulos-Error-CSV-Distribase14-08-2024.csv");

    $totalfilas = count($arraycsv);
    $arr = [];
    $errors = 0;
    $lineas = 0;

    $resultado = collect();

    foreach ($arraycsv as $fila => $columna) {
        if($fila!=0  && $columna[1]!='' && !empty(trim($columna[1]))){
            //echo $lineas;
            if ($errors > 500) {
                echo "Linea: ".$lineas;
                break;
            }

            $idmarca = $marcas->where('nombre', $columna[0])->first();

            if ($idmarca != NULL) {
                /* echo 'Marca CSV: '.$columna[0]. ' - IDMARCA: '.$idmarca['idmarc'];
                echo '<br>';
                echo 'https://api.distribase.online/v1/catalogo?refere='.$columna[1].'&idmarc='.$idmarca['idmarc'].'<br>';
                echo '<br><br>'; */
                $response = Http::withOptions([
                    'verify' => false,
                    'http_errors' => false,
                ])
                ->withHeaders([
                    'Authorization' => 'd2f77a06-9c1e-11e8-b6f8-005056b03ae4',
                    'Content-Type'	=> 'application/json'
                ])
                //->timeout(2)
                ->retry(0, 100)
                ->get('https://api.distribase.online/v1/catalogo?refere='.$columna[1].'&idmarc='.$idmarca['idmarc']);

                if ($response->serverError()) {
                    $errors++;
                } else {
                    if ($response->status() == 200) {
                        $response = $response->json();
                        //echo $response;
                        if (count($response) > 1) {
                            foreach ($response as $value) {
                                $columna[] = $value['idarti'];
                                $columna[] = $value['codart1'];
                                $columna[] = $value['codart2'];
                            }
                            $resultado->push($columna);
                        } else {
                            $columna[] = $response[0]['idarti'];
                            $columna[] = $response[0]['codart1'];
                            $columna[] = $response[0]['codart2'];
                            $resultado->push($columna);
                        }
                        
                    } else {
                        $errors++;
                    }
                }
            }

        }
        $lineas++;
    }

    (new FastExcel($resultado))->export('log.xlsx');
    //return $resultado;

});

Auth::routes();
Auth::routes(['register' => false]);