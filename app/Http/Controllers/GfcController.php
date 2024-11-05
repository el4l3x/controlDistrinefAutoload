<?php

namespace App\Http\Controllers;

use App\Models\Competitor;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class GfcController extends Controller
{


    public function __construct() {
        Event::listen(BuildingMenu::class, function(BuildingMenu $event)
        {
            $event->menu->add([
                'text'      => 'Dashboard',
                'route'     => 'gfc.dashboard',
                'active'    => ['gasfriocalor/dashboard'],
                'icon'      => 'fas fa-chart-pie mr-2',
                'can'       => 'dashboard.index',
            ]);
            $event->menu->add([
                'text' => 'Mejores Productos',
                'route'  => 'gfc.bestproducts',
                'active' => ['gasfriocalor/mejores-productos'],
                'icon'      => 'fas fa-crown mr-2',
                'can'       => 'mejores.productos.index',
            ]);
            $event->menu->add([
                'text' => 'Monitor de Precios',
                'route'  => 'gfc.monprice',
                'active' => ['gasfriocalor/monitor-precios'],
                'icon'      => 'fas fa-chart-bar mr-2',
                'can'       => 'monitor.index',
            ]);
            $event->menu->add([
                'text' => 'Oportunidades de Venta',
                'route'  => 'gfc.oportunidad.ventas',
                'active' => ['gasfriocalor/oportunidad-ventas'],
                'icon'      => 'fas fa-euro-sign mr-2',
                'can'       => 'oportunidades.index',
            ]);
        });
    }

    public function dashboard(Request $request) {
        $validator = Validator::make($request->all(), [
            'start' => 'required|date',
            'end' => 'required|date',
        ]);
 
        if ($validator->fails()) {
            $start = $request->session()->get('startDashboard', Carbon::yesterday());
            $end = $request->session()->get('endDashboard', Carbon::now());
			
			//return $start;
        } else {
            $start = $request->date('start')->format('Y-m-d H:i:s'); 
            $end = $request->date('end')->format('Y-m-d H:i:s');

            $request->session()->put('startDashboard', $start);
            $request->session()->put('endDashboard', $end);
			
			//return $start;
        }		

        /* Pedidos Entrados */
        $pedidosEntrados = DB::connection('presta')->table('orders')
            /* ->where('orders.valid', 1) */
            ->whereBetween('orders.date_add', [$start, $end])
            ->count();
            
        /* Importe Facturado */
        $importeFacturado = DB::connection('presta')->table('orders')
            /* ->where('orders.valid', 1) */
            ->whereBetween('orders.date_add', [$start, $end])
            ->sum('orders.total_paid_tax_incl');
            
        /* Carritos Totales */
        $carritosTotales = DB::connection('presta')->table('cart')
            ->whereBetween('cart.date_add', [$start, $end])
            ->count();
            
        /* Carritos Clientes */
        $carritosClientes = DB::connection('presta')->table('cart')
            ->where('id_customer', '!=', 0)
            ->where('id_customer', '!=', 66079)
            ->whereBetween('cart.date_add', [$start, $end])
            ->count();

        /* Productos Activos */
        $productsAct = DB::connection('presta')->table('product')->where('active', 1)->count();
        /* Productos Desactivados */
        $productsDes = DB::connection('presta')->table('product')->where('active', 0)->count();
        /* Combinaciones Activas */
        $productsUpd = DB::connection('presta')->table('product_attribute')
            ->select(
                'product_attribute.id_product'
            )
            ->join('product', 'product_attribute.id_product', '=', 'product.id_product')
            ->where('product.active', 1)
            ->count();

        /* Productos Nunca Vendidos */
        $productsNeverSales = DB::connection('presta')->table('product')
            ->select('product.id_product')
            ->join('order_detail', 'order_detail.product_id', '=', 'product.id_product', 'left outer')
            ->where('active', 1)
            ->whereNull('order_detail.product_id')
            ->count();

        // Charts

        $mes = date('m');
        $ano = date('Y');        

        for ($i=0; $i < 12-$mes; $i++) { 
            $result[] = 12-$i;
            switch ($mes+$i+1) {
                case 12:
                    $labelM[] = 'Dic '.$ano-1;
                    $dataQ[] = ['mes' => $mes+$i+1, 'año' => $ano-1];
                    break;
                
                case 11:
                    $labelM[] = 'Nov '.$ano-1;
                    $dataQ[] = ['mes' => $mes+$i+1, 'año' => $ano-1];
                    break;
                    
                case 10:
                    $labelM[] = 'Oct '.$ano-1;
                    $dataQ[] = ['mes' => $mes+$i+1, 'año' => $ano-1];
                    break;
                
                case 9:
                    $labelM[] = 'Sep '.$ano-1;
                    $dataQ[] = ['mes' => $mes+$i+1, 'año' => $ano-1];
                    break;
                    
                case 8:
                    $labelM[] = 'Ago '.$ano-1;
                    $dataQ[] = ['mes' => $mes+$i+1, 'año' => $ano-1];
                    break;
                
                case 7:
                    $labelM[] = 'Jul '.$ano-1;
                    $dataQ[] = ['mes' => $mes+$i+1, 'año' => $ano-1];
                    break;
                    
                case 6:
                    $labelM[] = 'Jun '.$ano-1;
                    $dataQ[] = ['mes' => $mes+$i+1, 'año' => $ano-1];
                    break;
                
                case 5:
                    $labelM[] = 'May '.$ano-1;
                    $dataQ[] = ['mes' => $mes+$i+1, 'año' => $ano-1];
                    break;
                    
                case 4:
                    $labelM[] = 'Abr '.$ano-1;
                    $dataQ[] = ['mes' => $mes+$i+1, 'año' => $ano-1];
                    break;
                    
                case 3:
                    $labelM[] = 'Mar '.$ano-1;
                    $dataQ[] = ['mes' => $mes+$i+1, 'año' => $ano-1];
                    break;
                
                case 2:
                    $labelM[] = 'Feb '.$ano-1;
                    $dataQ[] = ['mes' => $mes+$i+1, 'año' => $ano-1];
                    break;
                    
                case 1:
                    $labelM[] = 'Ene '.$ano-1;
                    $dataQ[] = ['mes' => $mes+$i+1, 'año' => $ano-1];
                    break;
            }
        }

        for ($i=0; $i < $mes; $i++) { 
            $data[] = $mes-$i;
            switch ($i+1) {
                case 12:
                    $labelM[] = 'Dic '.$ano;
                    $dataQ[] = ['mes' => $i+1, 'año' => $ano];
                    break;
                
                case 11:
                    $labelM[] = 'Nov '.$ano;
                    $dataQ[] = ['mes' => $i+1, 'año' => $ano];
                    break;
                    
                case 10:
                    $labelM[] = 'Oct '.$ano;
                    $dataQ[] = ['mes' => $i+1, 'año' => $ano];
                    break;
                
                case 9:
                    $labelM[] = 'Sep '.$ano;
                    $dataQ[] = ['mes' => $i+1, 'año' => $ano];
                    break;
                    
                case 8:
                    $labelM[] = 'Ago '.$ano;
                    $dataQ[] = ['mes' => $i+1, 'año' => $ano];
                    break;
                
                case 7:
                    $labelM[] = 'Jul '.$ano;
                    $dataQ[] = ['mes' => $i+1, 'año' => $ano];
                    break;
                    
                case 6:
                    $labelM[] = 'Jun '.$ano;
                    $dataQ[] = ['mes' => $i+1, 'año' => $ano];
                    break;
                
                case 5:
                    $labelM[] = 'May '.$ano;
                    $dataQ[] = ['mes' => $i+1, 'año' => $ano];
                    break;
                    
                case 4:
                    $labelM[] = 'Abr '.$ano;
                    $dataQ[] = ['mes' => $i+1, 'año' => $ano];
                    break;
                    
                case 3:
                    $labelM[] = 'Mar '.$ano;
                    $dataQ[] = ['mes' => $i+1, 'año' => $ano];
                    break;
                
                case 2:
                    $labelM[] = 'Feb '.$ano;
                    $dataQ[] = ['mes' => $i+1, 'año' => $ano];
                    break;
                    
                case 1:
                    $labelM[] = 'Ene '.$ano;
                    $dataQ[] = ['mes' => $i+1, 'año' => $ano];
                    break;
            }
        }

        foreach ($dataQ as $key => $value) {
            $pedidosChartQ = DB::connection('presta')->table('orders')
            ->whereMonth('orders.date_add', $value['mes'])
            ->whereYear('orders.date_add', $value['año'])
            ->count();

            $facturacionChartQ = DB::connection('presta')->table('orders')
            ->whereMonth('orders.date_add', $value['mes'])
            ->whereYear('orders.date_add', $value['año'])
            ->sum('orders.total_paid_tax_incl');

            $pedidosChart[] = $pedidosChartQ;
            $facturacionChart[] = $facturacionChartQ;
        }

        $subcategoriesAires = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_parent', 770)
            ->orWhere('id_category', 770)
            ->get();
        $arrayCategoriesAires = $subcategoriesAires->map(function($item){
            return $item->id_category;
        });
        $subcategoriesAiresTwo = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_category', 770)
            ->orWhereIn('id_parent', $arrayCategoriesAires)
            ->get();
        $arrayCategoriesAiresTwo = $subcategoriesAiresTwo->map(function($item){
            return $item->id_category;
        });
        $subcategoriesAiresThree = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_category', 770)
            ->orWhereIn('id_parent', $arrayCategoriesAiresTwo)
            ->get();
        $arrayCategoriesAiresThree = $subcategoriesAiresThree->map(function($item){
            return $item->id_category;
        });

        $aires = DB::connection('presta')->table('product')
        ->join('product_lang', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })
        ->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })
        ->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order');                
        })        
        ->select(
            'product.id_product',
            DB::raw('SUM('.env('PRESTA_PREFIX').'order_detail.product_quantity) as total_products'),
        )
        ->where('orders.valid', 1)
        ->whereBetween('orders.date_add', [$start, $end])
        ->whereIn('product.id_category_default', $arrayCategoriesAiresThree)
        ->groupBy('product.id_product')
        ->get()
        ->count();

        // Query Categoria y  Subcategorias Calderas
        $subcategoriesCalderas = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('id_parent', 768)
            ->orWhere('id_category', 768)
            ->get();
        $arrayCategoriesCalderas = $subcategoriesCalderas->map(function($item){
            return $item->id_category;
        });
        $subcategoriesCalderasTwo = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_category', 768)
            ->orWhereIn('id_parent', $arrayCategoriesCalderas)
            ->get();
        $arrayCategoriesCalderasTwo = $subcategoriesCalderasTwo->map(function($item){
            return $item->id_category;
        });
        $subcategoriesCalderasThree = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_category', 768)
            ->orWhereIn('id_parent', $arrayCategoriesCalderasTwo)
            ->get();
        $arrayCategoriesCalderasThree = $subcategoriesCalderasThree->map(function($item){
            return $item->id_category;
        });

        $calderas = DB::connection('presta')->table('product')
        ->join('product_lang', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })
        ->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })
        ->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order');                
        })        
        ->select(
            'product.id_product',
            DB::raw('SUM('.env('PRESTA_PREFIX').'order_detail.product_quantity) as total_products'),
        )
        ->where('orders.valid', 1)
        ->whereBetween('orders.date_add', [$start, $end])
        ->whereIn('product.id_category_default', $arrayCategoriesCalderasThree)
        ->groupBy('product.id_product')
        ->orderBy('total_products', 'DESC')
        ->get()
        ->count();

        // Query categoria y subs Aerotermia
        $subcategoriesAerotermia = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('id_parent', 959)
            ->orWhere('id_category', 959)
            ->get();
        $arrayCategoriesAerotermia = $subcategoriesAerotermia->map(function($item){
            return $item->id_category;
        });
        $subcategoriesAerotermiaTwo = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_category', 959)
            ->orWhereIn('id_parent', $arrayCategoriesAerotermia)
            ->get();
        $arrayCategoriesAerotermiaTwo = $subcategoriesAerotermiaTwo->map(function($item){
            return $item->id_category;
        });

        $aerotermia = DB::connection('presta')->table('product')
        ->join('product_lang', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })
        ->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })
        ->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order');                
        })        
        ->select(
            'product.id_product',
            DB::raw('SUM('.env('PRESTA_PREFIX').'order_detail.product_quantity) as total_products'),
        )
        ->where('orders.valid', 1)
        ->whereBetween('orders.date_add', [$start, $end])
        ->whereIn('product.id_category_default', $arrayCategoriesAerotermiaTwo)
        ->groupBy('product.id_product')
        ->orderBy('total_products', 'DESC')
        ->get()
        ->count();

        // Query categoria y subs Ventilacion
        $subcategoriesVentilacion = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('id_parent', 1121)
            ->orWhere('id_category', 1121)
            ->get();
        $arrayCategoriesVentilacion = $subcategoriesVentilacion->map(function($item){
            return $item->id_category;
        });
        $subcategoriesVentilacionTwo = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_category', 1121)
            ->orWhereIn('id_parent', $arrayCategoriesVentilacion)
            ->get();
        $arrayCategoriesVentilacionTwo = $subcategoriesVentilacionTwo->map(function($item){
            return $item->id_category;
        });

        $ventilacion = DB::connection('presta')->table('product')
        ->join('product_lang', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })
        ->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })
        ->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order');                
        })        
        ->select(
            'product.id_product',
            DB::raw('SUM('.env('PRESTA_PREFIX').'order_detail.product_quantity) as total_products'),
        )
        ->where('orders.valid', 1)
        ->whereBetween('orders.date_add', [$start, $end])
        ->whereIn('product.id_category_default', $arrayCategoriesVentilacionTwo)
        ->groupBy('product.id_product')
        ->orderBy('total_products', 'DESC')
        ->get()
        ->count();

        // Query Categoria y subs Calentadores a Gas
        $subcategoriesCalentadoresGas = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('id_parent', 769)
            ->orWhere('id_category', 769)
            ->get();
        $arrayCategoriesCalentadoresGas = $subcategoriesCalentadoresGas->map(function($item){
            return $item->id_category;
        });

        $calentadoresGas = DB::connection('presta')->table('product')
        ->join('product_lang', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })
        ->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })
        ->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order');                
        })        
        ->select(
            'product.id_product',
            DB::raw('SUM('.env('PRESTA_PREFIX').'order_detail.product_quantity) as total_products'),
        )
        ->where('orders.valid', 1)
        ->whereBetween('orders.date_add', [$start, $end])
        ->whereIn('product.id_category_default', $arrayCategoriesCalentadoresGas)
        ->groupBy('product.id_product')
        ->orderBy('total_products', 'DESC')
        ->get()
        ->count();

        // Query Categoria y subs Termos Electricos
        $subcategoriesTermosElectricos = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('id_parent', 771)
            ->orWhere('id_category', 771)
            ->get();
        $arrayCategoriesTermosElectricos = $subcategoriesTermosElectricos->map(function($item){
            return $item->id_category;
        });
        $subcategoriesTermosElectricosTwo = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_category', 771)
            ->orWhereIn('id_parent', $arrayCategoriesTermosElectricos)
            ->get();
        $arrayCategoriesTermosElectricosTwo = $subcategoriesTermosElectricosTwo->map(function($item){
            return $item->id_category;
        });

        $termosElectricos = DB::connection('presta')->table('product')
        ->join('product_lang', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })
        ->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })
        ->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order');                
        })        
        ->select(
            'product.id_product',
            DB::raw('SUM('.env('PRESTA_PREFIX').'order_detail.product_quantity) as total_products'),
        )
        ->where('orders.valid', 1)
        ->whereBetween('orders.date_add', [$start, $end])
        ->whereIn('product.id_category_default', $arrayCategoriesTermosElectricosTwo)
        ->groupBy('product.id_product')
        ->orderBy('total_products', 'DESC')
        ->get()
        ->count();
        // End Chartss
        
        return view("gfc.dashboard", [
            "productsAct"   => $productsAct,
            "productsDes"   => $productsDes,
            "productsUpd"   => $productsUpd,
            "pedidosEntrados"   => $pedidosEntrados,
            "carritosTotales"   => $carritosTotales,
            "carritosClientes"   => $carritosClientes,
            "importeFacturado"   => number_format($importeFacturado, 2, ",", ".")." €",
            "productsNeverSales"=> $productsNeverSales,
            "startDate" => Carbon::createFromDate($start)->format('d/m/Y'),
            "endDate" => Carbon::createFromDate($end)->format('d/m/Y'),
            "startDateFormat" => $start,
            "endDateFormat" => $end,
            'labelm' => $labelM,
            "facturacionChart"  => $facturacionChart,
            "pedidosChart"      => $pedidosChart,
            "airesPedidos"          => $aires,
            "calderasPedidos"          => $calderas,
            "aerotermiaPedidos"          => $aerotermia,
            "ventilacionPedidos"          => $ventilacion,
            "calentadoresGasPedidos"          => $calentadoresGas,
            "termosElectricosPedidos"          => $termosElectricos,
        ]);
    }

    public function bestProducts(Request $request) {
        $validator = Validator::make($request->all(), [
            'start' => 'required|date',
            'end' => 'required|date',
        ]);
 
        if ($validator->fails()) {
            $start = $request->session()->get('startBestsProducts', Carbon::yesterday());
            $end = $request->session()->get('endBestsProducts', Carbon::now());
        } else {
            $start = $request->date('start')->format('Y-m-d H:i:s'); 
            $end = $request->date('end')->format('Y-m-d H:i:s');

            $request->session()->put('startBestsProducts', $start);
            $request->session()->put('endBestsProducts', $end);
        }
		
		//return $start;

        // Query Categoria y  Subcategorias Aires
        $subcategoriesAires = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_parent', 770)
            ->orWhere('id_category', 770)
            ->get();
        $arrayCategoriesAires = $subcategoriesAires->map(function($item){
            return $item->id_category;
        });
        $subcategoriesAiresTwo = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_category', 770)
            ->orWhereIn('id_parent', $arrayCategoriesAires)
            ->get();
        $arrayCategoriesAiresTwo = $subcategoriesAiresTwo->map(function($item){
            return $item->id_category;
        });
        $subcategoriesAiresThree = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_category', 770)
            ->orWhereIn('id_parent', $arrayCategoriesAiresTwo)
            ->get();
        $arrayCategoriesAiresThree = $subcategoriesAiresThree->map(function($item){
            return $item->id_category;
        });

        $aires = DB::connection('presta')->table('product')
        ->join('product_lang', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })
        ->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })
        ->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order');                
        })        
        ->select(
            'product.id_product',
            DB::raw('SUM('.env('PRESTA_PREFIX').'order_detail.product_quantity) as total_products'),
        )
        ->where('orders.valid', 1)
        ->whereBetween('orders.date_add', [$start, $end])
        ->whereIn('product.id_category_default', $arrayCategoriesAiresThree)
        ->groupBy('product.id_product')
        ->get();

        $totalUnidadesAires = 0;
        for ($i=0; $i < $aires->count(); $i++) { 
            $totalUnidadesAires += $aires[$i]->total_products;
        }

        // Query Categoria y  Subcategorias Calderas
        $subcategoriesCalderas = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('id_parent', 768)
            ->orWhere('id_category', 768)
            ->get();
        $arrayCategoriesCalderas = $subcategoriesCalderas->map(function($item){
            return $item->id_category;
        });
        $subcategoriesCalderasTwo = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_category', 768)
            ->orWhereIn('id_parent', $arrayCategoriesCalderas)
            ->get();
        $arrayCategoriesCalderasTwo = $subcategoriesCalderasTwo->map(function($item){
            return $item->id_category;
        });
        $subcategoriesCalderasThree = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_category', 768)
            ->orWhereIn('id_parent', $arrayCategoriesCalderasTwo)
            ->get();
        $arrayCategoriesCalderasThree = $subcategoriesCalderasThree->map(function($item){
            return $item->id_category;
        });

        $calderas = DB::connection('presta')->table('product')
        ->join('product_lang', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })
        ->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })
        ->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order');                
        })        
        ->select(
            'product.id_product',
            DB::raw('SUM('.env('PRESTA_PREFIX').'order_detail.product_quantity) as total_products'),
        )
        ->where('orders.valid', 1)
        ->whereBetween('orders.date_add', [$start, $end])
        ->whereIn('product.id_category_default', $arrayCategoriesCalderasThree)
        ->groupBy('product.id_product')
        ->orderBy('total_products', 'DESC')
        ->get();

        $totalUnidadesCalderas = 0;
        for ($i=0; $i < $calderas->count(); $i++) { 
            $totalUnidadesCalderas += $calderas[$i]->total_products;
        }

        // Query categoria y subs Aerotermia
        $subcategoriesAerotermia = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('id_parent', 959)
            ->orWhere('id_category', 959)
            ->get();
        $arrayCategoriesAerotermia = $subcategoriesAerotermia->map(function($item){
            return $item->id_category;
        });
        $subcategoriesAerotermiaTwo = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_category', 959)
            ->orWhereIn('id_parent', $arrayCategoriesAerotermia)
            ->get();
        $arrayCategoriesAerotermiaTwo = $subcategoriesAerotermiaTwo->map(function($item){
            return $item->id_category;
        });

        $aerotermia = DB::connection('presta')->table('product')
        ->join('product_lang', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })
        ->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })
        ->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order');                
        })        
        ->select(
            'product.id_product',
            DB::raw('SUM('.env('PRESTA_PREFIX').'order_detail.product_quantity) as total_products'),
        )
        ->where('orders.valid', 1)
        ->whereBetween('orders.date_add', [$start, $end])
        ->whereIn('product.id_category_default', $arrayCategoriesAerotermiaTwo)
        ->groupBy('product.id_product')
        ->orderBy('total_products', 'DESC')
        ->get();

        $totalUnidadesAerotermia = 0;
        for ($i=0; $i < $aerotermia->count(); $i++) { 
            $totalUnidadesAerotermia += $aerotermia[$i]->total_products;
        }

        // Query categoria y subs Ventilacion
        $subcategoriesVentilacion = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('id_parent', 1121)
            ->orWhere('id_category', 1121)
            ->get();
        $arrayCategoriesVentilacion = $subcategoriesVentilacion->map(function($item){
            return $item->id_category;
        });
        $subcategoriesVentilacionTwo = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_category', 1121)
            ->orWhereIn('id_parent', $arrayCategoriesVentilacion)
            ->get();
        $arrayCategoriesVentilacionTwo = $subcategoriesVentilacionTwo->map(function($item){
            return $item->id_category;
        });

        $ventilacion = DB::connection('presta')->table('product')
        ->join('product_lang', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })
        ->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })
        ->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order');                
        })        
        ->select(
            'product.id_product',
            DB::raw('SUM('.env('PRESTA_PREFIX').'order_detail.product_quantity) as total_products'),
        )
        ->where('orders.valid', 1)
        ->whereBetween('orders.date_add', [$start, $end])
        ->whereIn('product.id_category_default', $arrayCategoriesVentilacionTwo)
        ->groupBy('product.id_product')
        ->orderBy('total_products', 'DESC')
        ->get();

        $totalUnidadesVentilacion = 0;
        for ($i=0; $i < $ventilacion->count(); $i++) { 
            $totalUnidadesVentilacion += $ventilacion[$i]->total_products;
        }

        // Query Categoria y subs Calentadores a Gas
        $subcategoriesCalentadoresGas = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('id_parent', 769)
            ->orWhere('id_category', 769)
            ->get();
        $arrayCategoriesCalentadoresGas = $subcategoriesCalentadoresGas->map(function($item){
            return $item->id_category;
        });

        $calentadoresGas = DB::connection('presta')->table('product')
        ->join('product_lang', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })
        ->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })
        ->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order');                
        })        
        ->select(
            'product.id_product',
            DB::raw('SUM('.env('PRESTA_PREFIX').'order_detail.product_quantity) as total_products'),
        )
        ->where('orders.valid', 1)
        ->whereBetween('orders.date_add', [$start, $end])
        ->whereIn('product.id_category_default', $arrayCategoriesCalentadoresGas)
        ->groupBy('product.id_product')
        ->orderBy('total_products', 'DESC')
        ->get();

        $totalUnidadesCalentadoresGas = 0;
        for ($i=0; $i < $calentadoresGas->count(); $i++) { 
            $totalUnidadesCalentadoresGas += $calentadoresGas[$i]->total_products;
        }

        // Query Categoria y subs Termos Electricos
        $subcategoriesTermosElectricos = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('id_parent', 771)
            ->orWhere('id_category', 771)
            ->get();
        $arrayCategoriesTermosElectricos = $subcategoriesTermosElectricos->map(function($item){
            return $item->id_category;
        });
        $subcategoriesTermosElectricosTwo = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_category', 771)
            ->orWhereIn('id_parent', $arrayCategoriesTermosElectricos)
            ->get();
        $arrayCategoriesTermosElectricosTwo = $subcategoriesTermosElectricosTwo->map(function($item){
            return $item->id_category;
        });

        $termosElectricos = DB::connection('presta')->table('product')
        ->join('product_lang', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })
        ->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })
        ->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order');                
        })        
        ->select(
            'product.id_product',
            DB::raw('SUM('.env('PRESTA_PREFIX').'order_detail.product_quantity) as total_products'),
        )
        ->where('orders.valid', 1)
        ->whereBetween('orders.date_add', [$start, $end])
        ->whereIn('product.id_category_default', $arrayCategoriesTermosElectricosTwo)
        ->groupBy('product.id_product')
        ->orderBy('total_products', 'DESC')
        ->get();

        $totalUnidadesTermosElectricos = 0;
        for ($i=0; $i < $termosElectricos->count(); $i++) { 
            $totalUnidadesTermosElectricos += $termosElectricos[$i]->total_products;
        }

        $superventas = DB::connection('presta')->table('product')
        ->select(
            'product.id_product',
            DB::raw('SUM('.env('PRESTA_PREFIX').'order_detail.product_quantity) as total_products'),
        )
        ->join('product_lang', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })
        ->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })
        ->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order');                
        })
        ->join('category_product', 'product.id_product', '=', 'category_product.id_product')        
        ->where('orders.valid', 1)
        ->whereBetween('orders.date_add', [$start, $end])
        ->where('category_product.id_category', 2227)
        ->groupBy('product.id_product')
        ->orderBy('total_products', 'DESC')
        ->get();

        $totalUnidadesSuperventas = 0;
        for ($i=0; $i < $superventas->count(); $i++) { 
            $totalUnidadesSuperventas += $superventas[$i]->total_products;
        }

        return view("gfc.best_products", [
            "airesMasVendidos"  =>  $aires->count(),
            "totalUnidadesAires"    => $totalUnidadesAires,
            "calderasMasVendidos"  =>  $calderas->count(),
            "totalUnidadesCalderas"    => $totalUnidadesCalderas,
            "aerotermiaMasVendidos"  =>  $aerotermia->count(),
            "totalUnidadesAerotermia"    => $totalUnidadesAerotermia,
            "ventilacionMasVendidos"  =>  $ventilacion->count(),
            "totalUnidadesVentilacion"    => $totalUnidadesVentilacion,
            "calentadoresGasMasVendidos"  =>  $calentadoresGas->count(),
            "totalUnidadesCalentadoresGas"    => $totalUnidadesCalentadoresGas,
            "termosElectricosMasVendidos"  =>  $termosElectricos->count(),
            "totalUnidadesTermosElectricos"    => $totalUnidadesTermosElectricos,
            "superventasMasVendidos"  =>  $superventas->count(),
            "totalUnidadesSuperventas"    => $totalUnidadesSuperventas,
            "startDate" => Carbon::createFromDate($start)->format('d/m/Y'),
            "endDate" => Carbon::createFromDate($end)->format('d/m/Y'),
            "startDateFormat" => $start,
            "endDateFormat" => $end,
        ]);
    }

    public function monPrice() {
        $competitors = Competitor::get();

        $arrayHeads = collect();
        $arrayColumns = collect();

        foreach ($competitors as $key => $value) {
            
            if ($value->id != env('GFC_SCRAP_ID')) {
                $arrayColumns->push(['data'  => $value->nombre, 'orderable' => false]);
                $arrayHeads->push(['label' => Str::limit($value->nombre, 13, '.'), 'no-export' => true]);
                $arrayHeads->push($value->nombre);
                $arrayColumns->push(['data'  => $value->nombre.'-price', 'searchable' => false, 'visible'=> false]);
                $arrayHeads->push('%');
                $arrayColumns->push(['data'  => $value->nombre.'-percent', 'searchable' => false, 'visible'=> false]);
            } else {
                $arrayColumns->push(['data'  => $value->nombre, 'orderable' => false, 'className' => 'table-primary']);
                $arrayHeads->push(['label' => Str::limit($value->nombre, 13, '.')]);
            }
        }

        $arrayHeads->prepend('Producto');
        $arrayHeads->prepend('Referencia');
        $arrayHeads->prepend('#');
        $arrayHeads->push(['label' => 'Opciones', 'no-export' => true]);

        $arrayColumns->prepend(['data'  => 'nombre']);
        $arrayColumns->prepend(['data'  => 'reference']);
        $arrayColumns->prepend(['data'  => 'idgfc']);
        $arrayColumns->push(['data'  => 'opciones', 'orderable' => false, 'width' => '70px']);

        return view("gfc.mon_price", [
            'arrayHeads'=> $arrayHeads,
            'arrayColumns'=> $arrayColumns,
        ]);
    }

    public function datatable() {
        try {
            $products = Product::query();

            $competitors = Competitor::with('products')->get();
            $gfcData = Competitor::with('products')->where('id', env('GFC_SCRAP_ID'))->first();

            $dt = DataTables::eloquent($products)
                ->editColumn('nombre', function (Product $product) use ($gfcData) {
                    return view('gfc.products.datatables.nombre', [
                        'url'       => ($product->competidor()->find($gfcData->id) != null) ? $product->competidor()->find($gfcData->id)->pivot->url : "#",
                        'nombre'    => $product->nombre,
                    ]);
                });

            foreach ($competitors as $competitor) {
                $dt->addColumn($competitor->nombre, function (Product $product) use ($gfcData, $competitor) {
                    return view('gfc.products.datatables.competidor_price', [
                        'gfcData' => $gfcData,
                        'product'    => $product,
                        'competitor'    => $competitor,
                    ]);
                });
                if ($competitor->id != env('GFC_SCRAP_ID')) {
                    $dt->addColumn($competitor->nombre.'-price', function (Product $product) use ($gfcData, $competitor) {
                        return view('gfc.products.datatables.competidor_price_export', [
                            'gfcData' => $gfcData,
                            'product'    => $product,
                            'competitor'    => $competitor,
                        ]);
                    });
                    $dt->addColumn($competitor->nombre.'-percent', function (Product $product) use ($gfcData, $competitor) {
                        return view('gfc.products.datatables.competidor_percent_export', [
                            'gfcData' => $gfcData,
                            'product'    => $product,
                            'competitor'    => $competitor,
                        ]);
                    });
                }
            }

            $dt->addColumn('opciones', function ($product) {
                    return view('gfc.products.datatables.buttons', [
                        'nombre'    => $product->nombre,
                        'id'    => $product->id,
                    ]);
                });

            return $dt->toJson();
        } catch (\Throwable $th) {
            //throw $th;
            Log::info("------------ Fallo el Datatable del Monitor ------------");
            Log::info($th->getMessage().' '.$th->getFile().' '.$th->getLine());
        }
    }

    public function datatableMejoresProductos(Request $request) {

        $validator = Validator::make($request->all(), [
            'start' => 'required',
            'end' => 'required',
        ]);
 
        if ($validator->fails()) {
            $start = Carbon::yesterday();
            $end = Carbon::now()->addHours(23)->addMinutes(59)->addSeconds(59);
        } else {
            $start = $request->date('start'); 
            $end = $request->date('end')->addHours(23)->addMinutes(59)->addSeconds(59);
        }

        $select = DB::connection('presta')->table('product')
        ->join('product_lang', function (JoinClause $joinClause) {
                $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order')
                ->where('orders.valid', 1)
                ->whereBetween('orders.date_add', [$start, $end]);
        })->select(
            'product.id_product',
            'product.reference as SKU',
            'order_detail.product_reference',
            'order_detail.product_name as Product_Name_Combination',
            'product_lang.name as Product_Name',
            'product_lang.link_rewrite as url_name',
            DB::raw("count(".env('PRESTA_PREFIX')."order_detail.product_id) as ordered_qty"),
            DB::raw('SUM('.env('PRESTA_PREFIX').'order_detail.product_quantity) as total_products'),
        )->groupBy('product.id_product')
        ->orderBy('total_products', 'DESC');

        $dt = DataTables::of($select)
            ->editColumn('Product_Name_Combination', function ($product) {
                return view('gfc.products.datatables.nombre', [
                    'url' => 'https://www.gasfriocalor.com/'.$product->url_name,
                    'nombre'    => $product->Product_Name,
                ]);
            });            

        return $dt->toJson();
    }

    public function datatableMejoresAires(Request $request) {

        $validator = Validator::make($request->all(), [
            'start' => 'required',
            'end' => 'required',
        ]);
 
        if ($validator->fails()) {
            $start = Carbon::yesterday();
            $end = Carbon::now()->addHours(23)->addMinutes(59)->addSeconds(59);
        } else {
            $start = $request->date('start'); 
            $end = $request->date('end')->addHours(23)->addMinutes(59)->addSeconds(59);
        }

        $subcategoriesAires = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_parent', 770)
            ->orWhere('id_category', 770)
            ->get();
        $arrayCategoriesAires = $subcategoriesAires->map(function($item){
            return $item->id_category;
        });
        $subcategoriesAiresTwo = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_category', 770)
            ->orWhereIn('id_parent', $arrayCategoriesAires)
            ->get();
        $arrayCategoriesAiresTwo = $subcategoriesAiresTwo->map(function($item){
            return $item->id_category;
        });
        $subcategoriesAiresThree = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_category', 770)
            ->orWhereIn('id_parent', $arrayCategoriesAiresTwo)
            ->get();
        $arrayCategoriesAiresThree = $subcategoriesAiresThree->map(function($item){
            return $item->id_category;
        });

        $aires = DB::connection('presta')->table('product')
        ->join('product_lang', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })
        ->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })
        ->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order');                
        })        
        ->select(
            'product.id_product',
            'product.reference as SKU',
            'order_detail.product_reference',
            'order_detail.product_name as Product_Name_Combination',
            'product_lang.name as Product_Name',
            'product_lang.link_rewrite as url_name',
            DB::raw("count(".env('PRESTA_PREFIX')."order_detail.id_order) as ordered_qty"),
            DB::raw('SUM('.env('PRESTA_PREFIX').'order_detail.product_quantity) as total_products'),
            DB::raw('GROUP_CONCAT(DISTINCT '.env('PRESTA_PREFIX').'orders.id_order ORDER BY '.env('PRESTA_PREFIX').'orders.id_order  SEPARATOR", ") as orders_ids'),
        )
        ->where('orders.valid', 1)
        ->whereBetween('orders.date_add', [$start, $end])
        ->whereIn('product.id_category_default', $arrayCategoriesAiresThree)
        ->groupBy('product.id_product')
        ->orderBy('total_products', 'DESC');

        $dt = DataTables::of($aires)
            ->editColumn('Product_Name_Combination', function ($product) {
                return view('gfc.products.datatables.nombre', [
                    'url' => 'https://www.gasfriocalor.com/'.$product->url_name,
                    'nombre'    => $product->Product_Name,
                ]);
            })
            ->editColumn('ordered_qty', function ($product) {
                return view('gfc.products.datatables.pedidos', [
                    'pedidos_count' => $product->ordered_qty,
                    'pedidos_ids'    => $product->orders_ids,
                    'nombre'    => $product->Product_Name,
                ]);
            });

        return $dt->toJson();
    }
    
    public function datatableMejoresCalderas(Request $request) {

        $validator = Validator::make($request->all(), [
            'start' => 'required',
            'end' => 'required',
        ]);
 
        if ($validator->fails()) {
            $start = Carbon::yesterday();
            $end = Carbon::now()->addHours(23)->addMinutes(59)->addSeconds(59);
        } else {
            $start = $request->date('start'); 
            $end = $request->date('end')->addHours(23)->addMinutes(59)->addSeconds(59);
        }

        $subcategoriesCalderas = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('id_parent', 768)
            ->orWhere('id_category', 768)
            ->get();
        $arrayCategoriesCalderas = $subcategoriesCalderas->map(function($item){
            return $item->id_category;
        });
        $subcategoriesCalderasTwo = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_category', 768)
            ->orWhereIn('id_parent', $arrayCategoriesCalderas)
            ->get();
        $arrayCategoriesCalderasTwo = $subcategoriesCalderasTwo->map(function($item){
            return $item->id_category;
        });
        $subcategoriesCalderasThree = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_category', 768)
            ->orWhereIn('id_parent', $arrayCategoriesCalderasTwo)
            ->get();
        $arrayCategoriesCalderasThree = $subcategoriesCalderasThree->map(function($item){
            return $item->id_category;
        });

        $calderas = DB::connection('presta')->table('product')
        ->join('product_lang', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })
        ->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })
        ->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order');                
        })        
        ->select(
            'product.id_product',
            'product.reference as SKU',
            'order_detail.product_reference',
            'order_detail.product_name as Product_Name_Combination',
            'product_lang.name as Product_Name',
            'product_lang.link_rewrite as url_name',
            DB::raw("count(".env('PRESTA_PREFIX')."order_detail.id_order) as ordered_qty"),
            DB::raw('SUM('.env('PRESTA_PREFIX').'order_detail.product_quantity) as total_products'),
            DB::raw('GROUP_CONCAT(DISTINCT '.env('PRESTA_PREFIX').'orders.id_order ORDER BY '.env('PRESTA_PREFIX').'orders.id_order  SEPARATOR", ") as orders_ids'),
        )
        ->where('orders.valid', 1)
        ->whereBetween('orders.date_add', [$start, $end])
        ->whereIn('product.id_category_default', $arrayCategoriesCalderasThree)
        ->groupBy('product.id_product')
        ->orderBy('total_products', 'DESC');

        $dt = DataTables::of($calderas)
            ->editColumn('Product_Name_Combination', function ($product) {
                return view('gfc.products.datatables.nombre', [
                    'url' => 'https://www.gasfriocalor.com/'.$product->url_name,
                    'nombre'    => $product->Product_Name,
                ]);
            })
            ->editColumn('ordered_qty', function ($product) {
                return view('gfc.products.datatables.pedidos', [
                    'pedidos_count' => $product->ordered_qty,
                    'pedidos_ids'    => $product->orders_ids,
                    'nombre'    => $product->Product_Name,
                ]);
            });

        return $dt->toJson();
    }
    
    public function datatableMejoresAerotermia(Request $request) {

        $validator = Validator::make($request->all(), [
            'start' => 'required',
            'end' => 'required',
        ]);
 
        if ($validator->fails()) {
            $start = Carbon::yesterday();
            $end = Carbon::now()->addHours(23)->addMinutes(59)->addSeconds(59);
        } else {
            $start = $request->date('start'); 
            $end = $request->date('end')->addHours(23)->addMinutes(59)->addSeconds(59);
        }

        $subcategoriesAerotermia = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('id_parent', 959)
            ->orWhere('id_category', 959)
            ->get();
        $arrayCategoriesAerotermia = $subcategoriesAerotermia->map(function($item){
            return $item->id_category;
        });
        $subcategoriesAerotermiaTwo = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_category', 959)
            ->orWhereIn('id_parent', $arrayCategoriesAerotermia)
            ->get();
        $arrayCategoriesAerotermiaTwo = $subcategoriesAerotermiaTwo->map(function($item){
            return $item->id_category;
        });

        $aerotermia = DB::connection('presta')->table('product')
        ->join('product_lang', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })
        ->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })
        ->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order');                
        })        
        ->select(
            'product.id_product',
            'product.reference as SKU',
            'order_detail.product_reference',
            'order_detail.product_name as Product_Name_Combination',
            'product_lang.name as Product_Name',
            'product_lang.link_rewrite as url_name',
            DB::raw("count(".env('PRESTA_PREFIX')."order_detail.id_order) as ordered_qty"),
            DB::raw('SUM('.env('PRESTA_PREFIX').'order_detail.product_quantity) as total_products'),
            DB::raw('GROUP_CONCAT(DISTINCT '.env('PRESTA_PREFIX').'orders.id_order ORDER BY '.env('PRESTA_PREFIX').'orders.id_order  SEPARATOR", ") as orders_ids'),
        )
        ->where('orders.valid', 1)
        ->whereBetween('orders.date_add', [$start, $end])
        ->whereIn('product.id_category_default', $arrayCategoriesAerotermiaTwo)
        ->groupBy('product.id_product')
        ->orderBy('total_products', 'DESC');

        $dt = DataTables::of($aerotermia)
            ->editColumn('Product_Name_Combination', function ($product) {
                return view('gfc.products.datatables.nombre', [
                    'url' => 'https://www.gasfriocalor.com/'.$product->url_name,
                    'nombre'    => $product->Product_Name,
                ]);
            })
            ->editColumn('ordered_qty', function ($product) {
                return view('gfc.products.datatables.pedidos', [
                    'pedidos_count' => $product->ordered_qty,
                    'pedidos_ids'    => $product->orders_ids,
                    'nombre'    => $product->Product_Name,
                ]);
            });                 

        return $dt->toJson();
    }
    
    public function datatableMejoresVentilacion(Request $request) {

        $validator = Validator::make($request->all(), [
            'start' => 'required',
            'end' => 'required',
        ]);
 
        if ($validator->fails()) {
            $start = Carbon::yesterday();
            $end = Carbon::now()->addHours(23)->addMinutes(59)->addSeconds(59);
        } else {
            $start = $request->date('start'); 
            $end = $request->date('end')->addHours(23)->addMinutes(59)->addSeconds(59);
        }

        $subcategoriesVentilacion = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('id_parent', 1121)
            ->orWhere('id_category', 1121)
            ->get();
        $arrayCategoriesVentilacion = $subcategoriesVentilacion->map(function($item){
            return $item->id_category;
        });
        $subcategoriesVentilacionTwo = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_category', 1121)
            ->orWhereIn('id_parent', $arrayCategoriesVentilacion)
            ->get();
        $arrayCategoriesVentilacionTwo = $subcategoriesVentilacionTwo->map(function($item){
            return $item->id_category;
        });

        $ventilacion = DB::connection('presta')->table('product')
        ->join('product_lang', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })
        ->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })
        ->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order');                
        })        
        ->select(
            'product.id_product',
            'product.reference as SKU',
            'order_detail.product_reference',
            'order_detail.product_name as Product_Name_Combination',
            'product_lang.name as Product_Name',
            'product_lang.link_rewrite as url_name',
            DB::raw("count(".env('PRESTA_PREFIX')."order_detail.id_order) as ordered_qty"),
            DB::raw('SUM('.env('PRESTA_PREFIX').'order_detail.product_quantity) as total_products'),
            DB::raw('GROUP_CONCAT(DISTINCT '.env('PRESTA_PREFIX').'orders.id_order ORDER BY '.env('PRESTA_PREFIX').'orders.id_order  SEPARATOR", ") as orders_ids'),
        )
        ->where('orders.valid', 1)
        ->whereBetween('orders.date_add', [$start, $end])
        ->whereIn('product.id_category_default', $arrayCategoriesVentilacionTwo)
        ->groupBy('product.id_product')
        ->orderBy('total_products', 'DESC');

        $dt = DataTables::of($ventilacion)
            ->editColumn('Product_Name_Combination', function ($product) {
                return view('gfc.products.datatables.nombre', [
                    'url' => 'https://www.gasfriocalor.com/'.$product->url_name,
                    'nombre'    => $product->Product_Name,
                ]);
            })
            ->editColumn('ordered_qty', function ($product) {
                return view('gfc.products.datatables.pedidos', [
                    'pedidos_count' => $product->ordered_qty,
                    'pedidos_ids'    => $product->orders_ids,
                    'nombre'    => $product->Product_Name,
                ]);
            });

        return $dt->toJson();
    }
    
    public function datatableMejoresCaletadoresgas(Request $request) {

        $validator = Validator::make($request->all(), [
            'start' => 'required',
            'end' => 'required',
        ]);
 
        if ($validator->fails()) {
            $start = Carbon::yesterday();
            $end = Carbon::now()->addHours(23)->addMinutes(59)->addSeconds(59);
        } else {
            $start = $request->date('start'); 
            $end = $request->date('end')->addHours(23)->addMinutes(59)->addSeconds(59);
        }

        $subcategoriesCalentadoresGas = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('id_parent', 769)
            ->orWhere('id_category', 769)
            ->get();
        $arrayCategoriesCalentadoresGas = $subcategoriesCalentadoresGas->map(function($item){
            return $item->id_category;
        });

        $calentadoresGas = DB::connection('presta')->table('product')
        ->join('product_lang', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })
        ->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })
        ->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order');                
        })        
        ->select(
            'product.id_product',
            'product.reference as SKU',
            'order_detail.product_reference',
            'order_detail.product_name as Product_Name_Combination',
            'product_lang.name as Product_Name',
            'product_lang.link_rewrite as url_name',
            DB::raw("count(".env('PRESTA_PREFIX')."order_detail.id_order) as ordered_qty"),
            DB::raw('SUM('.env('PRESTA_PREFIX').'order_detail.product_quantity) as total_products'),
            DB::raw('GROUP_CONCAT(DISTINCT '.env('PRESTA_PREFIX').'orders.id_order ORDER BY '.env('PRESTA_PREFIX').'orders.id_order  SEPARATOR", ") as orders_ids'),
        )
        ->where('orders.valid', 1)
        ->whereBetween('orders.date_add', [$start, $end])
        ->whereIn('product.id_category_default', $arrayCategoriesCalentadoresGas)
        ->groupBy('product.id_product')
        ->orderBy('total_products', 'DESC');

        $dt = DataTables::of($calentadoresGas)
            ->editColumn('Product_Name_Combination', function ($product) {
                return view('gfc.products.datatables.nombre', [
                    'url' => 'https://www.gasfriocalor.com/'.$product->url_name,
                    'nombre'    => $product->Product_Name,
                ]);
            })
            ->editColumn('ordered_qty', function ($product) {
                return view('gfc.products.datatables.pedidos', [
                    'pedidos_count' => $product->ordered_qty,
                    'pedidos_ids'    => $product->orders_ids,
                    'nombre'    => $product->Product_Name,
                ]);
            });

        return $dt->toJson();
    }
    
    public function datatableMejoresTermoselectricos(Request $request) {

        $validator = Validator::make($request->all(), [
            'start' => 'required',
            'end' => 'required',
        ]);
 
        if ($validator->fails()) {
            $start = Carbon::yesterday();
            $end = Carbon::now()->addHours(23)->addMinutes(59)->addSeconds(59);
        } else {
            $start = $request->date('start'); 
            $end = $request->date('end')->addHours(23)->addMinutes(59)->addSeconds(59);
        }

        $subcategoriesTermosElectricos = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('id_parent', 771)
            ->orWhere('id_category', 771)
            ->get();
        $arrayCategoriesTermosElectricos = $subcategoriesTermosElectricos->map(function($item){
            return $item->id_category;
        });
        $subcategoriesTermosElectricosTwo = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_category', 771)
            ->orWhereIn('id_parent', $arrayCategoriesTermosElectricos)
            ->get();
        $arrayCategoriesTermosElectricosTwo = $subcategoriesTermosElectricosTwo->map(function($item){
            return $item->id_category;
        });

        $termosElectricos = DB::connection('presta')->table('product')
        ->join('product_lang', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })
        ->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })
        ->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order');                
        })        
        ->select(
            'product.id_product',
            'product.reference as SKU',
            'order_detail.product_reference',
            'order_detail.product_name as Product_Name_Combination',
            'product_lang.name as Product_Name',
            'product_lang.link_rewrite as url_name',
            DB::raw("count(".env('PRESTA_PREFIX')."order_detail.id_order) as ordered_qty"),
            DB::raw('SUM('.env('PRESTA_PREFIX').'order_detail.product_quantity) as total_products'),
            DB::raw('GROUP_CONCAT(DISTINCT '.env('PRESTA_PREFIX').'orders.id_order ORDER BY '.env('PRESTA_PREFIX').'orders.id_order  SEPARATOR", ") as orders_ids'),
        )
        ->where('orders.valid', 1)
        ->whereBetween('orders.date_add', [$start, $end])
        ->whereIn('product.id_category_default', $arrayCategoriesTermosElectricosTwo)
        ->groupBy('product.id_product')
        ->orderBy('total_products', 'DESC');

        $dt = DataTables::of($termosElectricos)
            ->editColumn('Product_Name_Combination', function ($product) {
                return view('gfc.products.datatables.nombre', [
                    'url' => 'https://www.gasfriocalor.com/'.$product->url_name,
                    'nombre'    => $product->Product_Name,
                ]);
            })
            ->editColumn('ordered_qty', function ($product) {
                return view('gfc.products.datatables.pedidos', [
                    'pedidos_count' => $product->ordered_qty,
                    'pedidos_ids'    => $product->orders_ids,
                    'nombre'    => $product->Product_Name,
                ]);
            });

        return $dt->toJson();
    }
    
    public function datatableMejoresSuperventas(Request $request) {

        $validator = Validator::make($request->all(), [
            'start' => 'required',
            'end' => 'required',
        ]);
 
        if ($validator->fails()) {
            $start = Carbon::yesterday();
            $end = Carbon::now()->addHours(23)->addMinutes(59)->addSeconds(59);
        } else {
            $start = $request->date('start'); 
            $end = $request->date('end')->addHours(23)->addMinutes(59)->addSeconds(59);
        }

        $superventas = DB::connection('presta')->table('product')
        ->select(
            'product.id_product',
            'product.reference as SKU',
            'order_detail.product_reference',
            'order_detail.product_name as Product_Name_Combination',
            'product_lang.name as Product_Name',
            'product_lang.link_rewrite as url_name',
            DB::raw("count(".env('PRESTA_PREFIX')."order_detail.id_order) as ordered_qty"),
            DB::raw('SUM('.env('PRESTA_PREFIX').'order_detail.product_quantity) as total_products'),
            DB::raw('GROUP_CONCAT(DISTINCT '.env('PRESTA_PREFIX').'orders.id_order ORDER BY '.env('PRESTA_PREFIX').'orders.id_order  SEPARATOR", ") as orders_ids'),
        )
        ->join('product_lang', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })
        ->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })
        ->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order');                
        })
        ->join('category_product', 'product.id_product', '=', 'category_product.id_product')        
        ->where('orders.valid', 1)
        ->whereBetween('orders.date_add', [$start, $end])
        ->where('category_product.id_category', 2227)
        ->groupBy('product.id_product')
        ->orderBy('total_products', 'DESC');

        $dt = DataTables::of($superventas)
            ->editColumn('Product_Name_Combination', function ($product) {
                return view('gfc.products.datatables.nombre', [
                    'url' => 'https://www.gasfriocalor.com/'.$product->url_name,
                    'nombre'    => $product->Product_Name,
                ]);
            })
            ->editColumn('ordered_qty', function ($product) {
                return view('gfc.products.datatables.pedidos', [
                    'pedidos_count' => $product->ordered_qty,
                    'pedidos_ids'    => $product->orders_ids,
                    'nombre'    => $product->Product_Name,
                ]);
            });                 

        return $dt->toJson();
    }

    public function oportunidadVentas() {        
        return view('gfc.oportunidades');
    }

    public function oportunidadContactar(Request $request, $idCart) {

        $oportunidad = DB::connection('presta')->table('cart')
            ->where('id_cart', $idCart)
            ->update(['contactado' => 1, 'comentario' => $request->comment, 'fecha_contacto' => now()]);

        return redirect(route('gfc.oportunidad.ventas'));
    }

    public function productosNoVendidos() {
        return view('gfc.products.nunca-vendidos');
    }
}
