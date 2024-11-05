<?php

namespace App\Http\Controllers;

use App\Models\CompetitorDivisonled;
use App\Models\ProductDivisonled;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class DivisonledController extends Controller
{
    public function __construct() {
        Event::listen(BuildingMenu::class, function(BuildingMenu $event)
        {
            $event->menu->add([
                'text'      => 'Monitor de Precios',
                'route'     => 'divisonled.dashboard',
                'active'    => ['divisonled/monitor'],
                'icon'      => 'fas fa-chart-bar mr-2',
            ]);
        });
    }

    public function dashboard() {
        $competitors = CompetitorDivisonled::get();

        $arrayHeads = collect();
        $arrayColumns = collect();

        foreach ($competitors as $key => $value) {
            
            if ($value->id != env('DLED_SCRAP_ID')) {
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
        $arrayHeads->push(['label' => 'Opciones', 'no-export' => true]);

        $arrayColumns->prepend(['data'  => 'nombre']);
        $arrayColumns->prepend(['data'  => 'reference']);
        $arrayColumns->push(['data'  => 'opciones', 'orderable' => false, 'width' => '70px']);

        return view('divisonled.dashboard', [
            'arrayHeads'=> $arrayHeads,
            'arrayColumns'=> $arrayColumns,
        ]);
    }

    public function datatable() {
        try {
            $products = ProductDivisonled::query();

            $competitors = CompetitorDivisonled::with('products')->get();
            $gfcData = CompetitorDivisonled::with('products')->where('id', env('DLED_SCRAP_ID'))->first();

            $dt = DataTables::eloquent($products)
                ->editColumn('nombre', function (ProductDivisonled $product) use ($gfcData) {
                    return view('gfc.products.datatables.nombre', [
                        'url'       => ($product->competidor()->find($gfcData->id) != null) ? $product->competidor()->find($gfcData->id)->pivot->url : "#",
                        'nombre'    => $product->nombre,
                    ]);
                });

            foreach ($competitors as $competitor) {
                $dt->addColumn($competitor->nombre, function (ProductDivisonled $product) use ($gfcData, $competitor) {
                    return view('divisonled.products.datatable.competidor_price', [
                        'gfcData' => $gfcData,
                        'product'    => $product,
                        'competitor'    => $competitor,
                    ]);
                });
                if ($competitor->id != env('DLED_SCRAP_ID')) {
                    $dt->addColumn($competitor->nombre.'-price', function (ProductDivisonled $product) use ($gfcData, $competitor) {
                        return view('gfc.products.datatables.competidor_price_export', [
                            'gfcData' => $gfcData,
                            'product'    => $product,
                            'competitor'    => $competitor,
                        ]);
                    });
                    $dt->addColumn($competitor->nombre.'-percent', function (ProductDivisonled $product) use ($gfcData, $competitor) {
                        return view('gfc.products.datatables.competidor_percent_export', [
                            'gfcData' => $gfcData,
                            'product'    => $product,
                            'competitor'    => $competitor,
                        ]);
                    });
                }
            }

            $dt->addColumn('opciones', function ($product) {
                    return view('divisonled.products.datatable.buttons', [
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
}
