<?php

namespace App\Http\Controllers;

use App\Exports\DtoCompraExport;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;

class CronsController extends Controller
{
    public function dtosCompras() {
        /*Excel::download(new DtoCompraExport, 'DtoCompra.xlsx');
        Excel::store(new DtoCompraExport, 'DtoCompra.xlsx');*/

        $products = DB::connection('presta')->table('product')
            ->select([
                'product.id_product', 
                'product.wholesale_price',
                'product.price',
                'product_attribute.id_product_attribute',  
                'product_attribute.price as adicional',
                'product.DtoCompra', 
                'product_attribute.pcompra_mags', 
                'product_attribute.pcompra_abad', 
                'product_attribute.pcompra_cale', 
                'product_attribute.pcompra_ferre', 
                'product_attribute.pcompra_elect', 
                'product_attribute.pcompra_caly', 
                'product_lang.name as product_name', 
                'product.id_manufacturer',
                'specific_price.reduction as dtoVenta' ])
            ->join('product_lang', function (JoinClause $joinClause) {
                $joinClause->on('product.id_product', '=', 'product_lang.id_product');
            })
            ->join('specific_price', function (JoinClause $joinClause) {
                $joinClause->on('product.id_product', '=', 'specific_price.id_product');
            })
            ->leftJoin('product_attribute', function (JoinClause $joinClause) {
                $joinClause->on('product.id_product', '=', 'product_attribute.id_product');
            })
            ->leftJoin('product_attribute_combination', function (JoinClause $joinClause) {
                $joinClause->on('product_attribute_combination.id_product_attribute', '=', 'product_attribute.id_product_attribute');
            })
            ->whereNull('product.pcompra_mags')
            ->whereNull('product.pcompra_abad')
            ->whereNull('product.pcompra_cale')
            ->whereNull('product.pcompra_ferre')
            ->whereNull('product.pcompra_elect')
            ->whereNull('product.pcompra_caly')
            ->groupBy('product.id_product', 'product_attribute_combination.id_product_attribute')
            ->get();

        return $products;
        //return true;
    }
}
