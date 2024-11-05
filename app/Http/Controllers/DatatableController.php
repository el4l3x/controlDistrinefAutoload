<?php

namespace App\Http\Controllers;

use App\Models\Oportunidad;
use App\Models\Partner;
use Carbon\Carbon;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Number;
use Yajra\DataTables\Facades\DataTables;

class DatatableController extends Controller
{
    public function datatableOportunidadesVentas(Request $request) {
        try {

            /* $oportunidadesPs = DB::connection('presta')->table('cart')
                ->select([
                    'cart.id_cart as cartId',
                    'cart.id_customer as cartCustomerId',
                    'cart.date_upd as cartDate',
                    //'cart.contactado as isContacted',
                    //'cart.comentario as comment',
                    //'cart.fecha_contacto as commentDate',
                    'customer.id_customer as customerId',
                    'customer.firstname as nombre',
                    'customer.lastname as apellido',
                    'customer.email as correo',
                    'address.id_address as addressId',
                    DB::raw('GROUP_CONCAT(DISTINCT IF('.env('PRESTA_PREFIX').'address.phone != 0, '.env('PRESTA_PREFIX').'address.phone, NULL) SEPARATOR ", ") as telefono'),
                    DB::raw('GROUP_CONCAT(DISTINCT IF('.env('PRESTA_PREFIX').'address.phone_mobile != 0, '.env('PRESTA_PREFIX').'address.phone_mobile, NULL) SEPARATOR ", ") as telefonoMovil'),
                    DB::raw('GROUP_CONCAT(DISTINCT '.env('PRESTA_PREFIX').'product_lang.name ORDER BY '.env('PRESTA_PREFIX').'product_lang.name SEPARATOR", ") as products_name'),
                ])
                ->join('customer', 'cart.id_customer', '=', 'customer.id_customer')
                ->join('address', 'address.id_customer', '=', 'customer.id_customer')
                ->leftJoin('cart_product', 'cart_product.id_cart', '=', 'cart.id_cart')
                ->leftJoin('product', 'product.id_product', '=', 'cart_product.id_product')
                ->leftJoin('product_lang', 'product_lang.id_product', '=', 'product.id_product')
                ->leftJoin('orders', 'orders.id_cart', '=', 'cart.id_cart')
                ->where('cart.id_customer', '!=', 0)
                ->whereNull('orders.id_cart')
                ->whereBetween('cart.date_upd', [Carbon::today()->subDays(14), Carbon::now()->subHours(24)])
                ->where('cart.date_add', '<', Carbon::now()->subHours(24))
                ->whereNotIn('cart.id_customer', DB::connection('presta')->table('orders')
                    ->where('id_cart', '!=', 0)
                    ->pluck('orders.id_customer')
                )
                ->groupBy('cart.id_customer')
                ->orderBy('cart.id_cart', 'desc')
                ->get(); */

                $oportunidadesPs = DB::connection('presta')->table('cart')
            ->select([
                'cart.id_cart as cartId',
                'cart.id_customer as cartCustomerId',
                'cart.date_upd as cartDate',
                //'cart.contactado as isContacted',
                //'cart.comentario as comment',
                //'cart.fecha_contacto as commentDate',
                'customer.id_customer as customerId',
                'customer.firstname as nombre',
                'customer.lastname as apellido',
                'customer.email as correo',
                'address.id_address as addressId',
                DB::raw('GROUP_CONCAT(DISTINCT IF('.env('PRESTA_PREFIX').'address.phone != 0, '.env('PRESTA_PREFIX').'address.phone, NULL) SEPARATOR ", ") as telefono'),
                DB::raw('GROUP_CONCAT(DISTINCT IF('.env('PRESTA_PREFIX').'address.phone_mobile != 0, '.env('PRESTA_PREFIX').'address.phone_mobile, NULL) SEPARATOR ", ") as telefonoMovil'),
                DB::raw('GROUP_CONCAT(DISTINCT '.env('PRESTA_PREFIX').'product_lang.name ORDER BY '.env('PRESTA_PREFIX').'product_lang.name SEPARATOR", ") as products_name'),
            ])
            ->join('customer', 'cart.id_customer', '=', 'customer.id_customer')
            ->join('address', 'address.id_customer', '=', 'customer.id_customer')
            ->leftJoin('cart_product', 'cart_product.id_cart', '=', 'cart.id_cart')
            ->leftJoin('product', 'product.id_product', '=', 'cart_product.id_product')
            ->leftJoin('product_lang', 'product_lang.id_product', '=', 'product.id_product')
            ->leftJoin('orders', 'orders.id_cart', '=', 'cart.id_cart')
            ->where('cart.id_customer', '!=', 0)
            ->whereNull('orders.id_cart')
            ->whereBetween('cart.date_upd', [Carbon::today()->subDays(14), Carbon::now()->subHours(24)])
            ->where('cart.date_add', '<', Carbon::now()->subHours(24))
            ->whereNotIn('cart.id_customer', DB::connection('presta')->table('orders')
                ->where('id_cart', '!=', 0)
                ->pluck('orders.id_customer')
            )
            ->groupBy('cart.id_customer')
            ->orderBy('cart.id_cart', 'desc')
            ->get();

            //return $oportunidadesPs;

            $oportunidades = Oportunidad::all();

            if ($oportunidades->count() > 0) {
                $data = collect();

                foreach ($oportunidades as $key => $oportunidad) {
                    if (!$oportunidadesPs->contains('cartId', '=', $oportunidad->ps_cart_id)) {
                        //echo '1-Eliminar: '.$oportunidad->ps_cart_id.'<br>';
                        $oportunidad->delete();
                    }
                }

                foreach ($oportunidadesPs as $oportunidadPs) {
                    if (!$oportunidades->contains('ps_cart_id', '=', $oportunidadPs->cartId)) {
                        echo '2-Nueva: '.$oportunidadPs->cartId.'<br>';

                        if (Oportunidad::withTrashed()->where('ps_cart_id', $oportunidadPs->cartId)->count() > 0) {
                            $newOportunidad = Oportunidad::withTrashed()->where('ps_cart_id', $oportunidadPs->cartId)->first();
                            
                            Oportunidad::withTrashed()
                                ->where('ps_cart_id', $oportunidadPs->cartId)
                                ->restore();
                        } else {
                            $newOportunidad = new Oportunidad();
                            $newOportunidad->ps_cart_id = $oportunidadPs->cartId;
                            $newOportunidad->save();
                        }

                        $data->push([
                            'id_oportunidad' => $newOportunidad->id,
                            'contactado' => $newOportunidad->contactado,
                            'comentario' => $newOportunidad->comentario,
                            'fecha_contacto' => $newOportunidad->fecha_contacto,
                            'ps_cart_id' => $oportunidadPs->cartId,
                            'cartCustomerId' => $oportunidadPs->cartCustomerId,
                            'cartDate' => $oportunidadPs->cartDate,
                            'customerId' => $oportunidadPs->customerId,
                            'nombre' => $oportunidadPs->nombre,
                            'apellido' => $oportunidadPs->apellido,
                            'correo' => $oportunidadPs->correo,
                            'addressId' => $oportunidadPs->addressId,
                            'telefono' => $oportunidadPs->telefono,
                            'telefonoMovil' => $oportunidadPs->telefonoMovil,
                            'products_name' => $oportunidadPs->products_name,
                        ]);
                    } else {
                        $oportunidadSaved = $oportunidades->where('ps_cart_id', '=', $oportunidadPs->cartId)->first();

                        $data->push([
                            'id_oportunidad' => $oportunidadSaved->id,
                            'contactado' => $oportunidadSaved->contactado,
                            'comentario' => $oportunidadSaved->comentario,
                            'fecha_contacto' => $oportunidadSaved->fecha_contacto,
                            'cartId' => $oportunidadPs->cartId,
                            'cartCustomerId' => $oportunidadPs->cartCustomerId,
                            'cartDate' => $oportunidadPs->cartDate,
                            'customerId' => $oportunidadPs->customerId,
                            'nombre' => $oportunidadPs->nombre,
                            'apellido' => $oportunidadPs->apellido,
                            'correo' => $oportunidadPs->correo,
                            'addressId' => $oportunidadPs->addressId,
                            'telefono' => $oportunidadPs->telefono,
                            'telefonoMovil' => $oportunidadPs->telefonoMovil,
                            'products_name' => $oportunidadPs->products_name,
                        ]);
                    }
                }
            } else {
                foreach ($oportunidadesPs as $oportunidadPs) {
                    $newOportunidad = new Oportunidad();
                    $newOportunidad->ps_cart_id = $oportunidadPs->cartId;
                    $newOportunidad->save();
                }
            }

            $dt = DataTables::of($data)
                ->editColumn('cartDate', function ($product) {
                    return view('gfc.datatables.fecha', [
                        'date'  => Carbon::createFromDate($product['cartDate'])->format('d-m-Y'),
                        'dateOrd'  => Carbon::createFromDate($product['cartDate']),
                    ]);
                })
                ->editColumn('nombre', function ($product) {
                    return view('gfc.datatables.nombre_clientes', [
                        'apellido'  => $product['apellido'],
                        'nombre'    => $product['nombre'],
                    ]);
                })
                ->editColumn('telefono', function ($product) {
                    return view('gfc.datatables.telefonos', [
                        'telefono'      => $product['telefono'],
                        'telefonoMovil' => $product['telefonoMovil'],
                    ]);
                })
                ->editColumn('products_name', function ($product) {
                    return view('gfc.datatables.products_modal', [
                        'productos' => $product['products_name'],
                        'id'        => $product['cartId'],
                    ]);
                })
                ->addColumn('contacto', function ($product) {
                    return view('gfc.datatables.oportunidades.contacto', [
                        'isContacted'   => $product['contactado'],
                        'comment'       => $product['comentario'],
                        'idCart'        => $product['cartId'],
                        'date'          => Carbon::createFromDate($product['fecha_contacto'])->format('d-m-Y h:s'),
                    ]);
                });

            return $dt->toJson();
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function nuncaVendidos(Request $request) {
        try {
            $nuncaVendidos = DB::connection('presta')->table('product')
                ->select('product.id_product as id_product', 'product.reference as reference', 'product_lang.name as name')
                ->join('product_lang', function (JoinClause $joinClause) {
                    $joinClause->on('product.id_product', '=', 'product_lang.id_product');
                })
                ->join('order_detail', 'order_detail.product_id', '=', 'product.id_product', 'left outer')
                ->where('active', 1)
                ->whereNull('order_detail.product_id')
                ->groupBy('product.id_product');

            $dt = DataTables::of($nuncaVendidos)
                /* ->addColumn('nombre', function ($product) {
                    return $product->name;
                }) */
                ->filterColumn('name', function($query, $keyword) {
                    $sql = "gfc_product_lang.name like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                });

            return $dt->toJson();
        } catch (\Throwable $th) {
            //throw $th;
            Log::info("------------ Fallo el DT nunca vendidos ------------");
            Log::info($th->getMessage().' '.$th->getFile().' '.$th->getLine());
            return $th->getMessage();
        }
    }

    public function nomatch(Request $request) {
        try {
            $nuncaVendidos = DB::connection('presta')->table('product')
                ->select('product.id_product as id_product', 'product_lang.name as product_name', 'product.mpn', 'product.CodAuna', 'product.CodTelematel')
                ->join('product_lang', function (JoinClause $joinClause) {
                    $joinClause->on('product.id_product', '=', 'product_lang.id_product');
                })
                ->whereNull('pcompra_mags')
                ->whereNull('pcompra_abad')
                ->whereNull('pcompra_cale')
                ->whereNull('pcompra_ferre')
                ->whereNull('stock_mags')
                ->whereNull('stock_abad')
                ->whereNull('stock_cale')
                ->whereNull('stock_ferre')
                ->where('active', 1)
                ->where('available_for_order', 1)
                ->where('reference', '!=', '')
                ->groupBy('product.id_product');

            $dt = DataTables::of($nuncaVendidos)
                ->filterColumn('name', function($query, $keyword) {
                    $sql = "gfc_product_lang.name like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                });

            return $dt->toJson();
        } catch (\Throwable $th) {
            //throw $th;
            Log::info("------------ Fallo el DT nomatch ------------");
            Log::info($th->getMessage().' '.$th->getFile().' '.$th->getLine());
            return $th->getMessage();
        }
    }
    
    public function partners(Request $request) {
        try {
            $data = Partner::has('widgets')->get();

            $partners = collect();

            foreach ($data as $value) {
                $nombre = $value->name;
                $totalCsv = $value->widgets[0]->pivot->total;
                if ($value->widgets()->where('report_id', 3)->first() !== null) {
                    $distribase = $value->widgets()->where('report_id', 3)->first()->pivot->afectados;
                    $distribaseErrors = $value->widgets()->where('report_id', 3)->first()->pivot->errores;
                    $distriSuccess = $distribase-$distribaseErrors;
                    $distribasePercent = Number::percentage(($distriSuccess / $totalCsv) * 100, maxPrecision: 2);

                } else {
                    $distribase = 0;
                    $distribasePercent = 0;
                    $distribaseErrors = 0;
                }
                $gfc = $value->widgets()->sum('revisados');
                $gfcMatch = $value->widgets()->where('report_id', '!=', 3)->sum('afectados');
                if ($value->widgets()->sum('revisados')) {
                    $gfcPercent = Number::percentage(($value->widgets()->where('report_id', '!=', 3)->sum('afectados') / $value->widgets[0]->pivot->total) * 100, maxPrecision: 2);
                } else {
                    $gfcPercent = 0;
                }

                $partners->push([
                    'nombre' => $nombre,
                    'totalCsv' => $totalCsv,
                    'distribase' => $distribase,
                    'distribasePercent' => $distribasePercent,
                    'distribaseErrors' => $distribaseErrors,
                    'gfc' => $gfc,
                    'gfcMatch' => $gfcMatch,
                    'gfcPercent' => $gfcPercent,
                ]);

            }


            $dt = DataTables::of($partners);

            return $dt->toJson();
        } catch (\Throwable $th) {
            //throw $th;
            Log::info("------------ Fallo el DT nomatch ------------");
            Log::info($th->getMessage().' '.$th->getFile().' '.$th->getLine());
            return $th->getMessage();
        }
    }
}
