<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Query\JoinClause;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DtoCompraExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $products = DB::connection('presta')->table('product')
            ->select([
                'product.id_product', 
                'product.wholesale_price',
                'product.price',
                'product_attribute.id_product_attribute',  
                'product_attribute.price as adicional',
                'product.DtoCompra', 
                'product.pcompra_mags', 
                'product.pcompra_abad', 
                'product.pcompra_cale', 
                'product.pcompra_ferre', 
                'product.pcompra_elect', 
                'product.pcompra_caly', 
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
            ->whereNotNull('product.pcompra_mags')
            ->orWhereNotNull('product.pcompra_abad')
            ->orWhereNotNull('product.pcompra_cale')
            ->orWhereNotNull('product.pcompra_ferre')
            ->orWhereNotNull('product.pcompra_elect')
            ->orWhereNotNull('product.pcompra_caly')
            ->groupBy('product.id_product', 'product_attribute_combination.id_product_attribute')
            ->get();

        $data = collect();

        foreach ($products as $product) {
            $pcompra_mags = ($product->pcompra_mags != Null && $product->pcompra_mags > 0) ? str_replace(',', '.', $product->pcompra_mags) : false ;
            $pcompra_abad = ($product->pcompra_abad != Null && $product->pcompra_abad > 0) ? str_replace(',', '.', $product->pcompra_abad) : false ;
            $pcompra_cale = ($product->pcompra_cale != Null && $product->pcompra_cale > 0) ? str_replace(',', '.', $product->pcompra_cale) : false ;
            $pcompra_ferre = ($product->pcompra_ferre != Null && $product->pcompra_ferre > 0) ? str_replace(',', '.', $product->pcompra_ferre) : false ;
            $pcompra_elect = ($product->pcompra_elect != Null && $product->pcompra_elect > 0) ? str_replace(',', '.', $product->pcompra_elect) : false ;
            $pcompra_caly = ($product->pcompra_caly != Null && $product->pcompra_caly > 0) ? str_replace(',', '.', $product->pcompra_caly) : false ;
            //$pcompra_ditri = ($product->wholesale_price != Null && $product->wholesale_price > 0) ? $product->wholesale_price : false ;
            $pcompra_ditri = false;
            $compra = array($pcompra_mags, $pcompra_abad, $pcompra_cale, $pcompra_ferre, $pcompra_elect, $pcompra_caly, $pcompra_ditri);
            $compra = array_filter($compra);

            if (count($compra) > 0) {
                switch (min($compra)) {
                    case $pcompra_mags:
                        $bestCompra = $pcompra_mags;
                        break;
                    case $pcompra_abad:
                        $bestCompra = $pcompra_abad;
                        break;
                    case $pcompra_cale:
                        $bestCompra = $pcompra_cale;
                        break;
                    case $pcompra_ferre:
                        $bestCompra = $pcompra_ferre;
                        break;
                    case $pcompra_elect:
                        $bestCompra = $pcompra_elect;
                        break;
                    case $pcompra_caly:
                        $bestCompra = $pcompra_caly;
                        break;
                    case $pcompra_ditri:
                        $bestCompra = $product->wholesale_price;
                        break;
                }

                $precio_venta=round($product->price, 2)+round($product->adicional, 2);
                
                $dtoCompra = 100*(1-$bestCompra/$precio_venta);

                if ($dtoCompra <= 0) {
                    $dtoCompra = -99;
                }
                
                $dtoVenta = round($product->dtoVenta*100, 2);
                $margenproducto = 1-((100-$product->DtoCompra)/(100-$dtoVenta));

                if ($product->adicional != NULL) {
                    DB::connection('presta')->table('product_attribute')
                    ->where('id_product_attribute', $product->id_product_attribute)
                    ->update(['wholesale_price' => $bestCompra, 'DtoCompra' => $dtoCompra]);

                    $data->push([
                        'id_product' => $product->id_product,
                        'tipo' => 'Combinacion',
                        'nombre' => $product->product_name,
                        'bestCompra' => $bestCompra,
                        'pventa' => $precio_venta,
                        'DtoCompra' => $product->DtoCompra,
                        'NewDtoCompra' => $dtoCompra,
                        'pcompra_mags' => $product->pcompra_mags,
                        'pcompra_abad' => $product->pcompra_abad,
                        'pcompra_cale' => $product->pcompra_cale,
                        'pcompra_ferre' => $product->pcompra_ferre,
                        'pcompra_elect' => $product->pcompra_elect,
                        'pcompra_caly' => $product->pcompra_caly,
                        'wholesale_price' => $product->wholesale_price,
                        'margen' => $margenproducto,
                        'precio_combinacion' => $product->adicional
                    ]);
                } else {
                    DB::connection('presta')->table('product')
                    ->where('id_product', $product->id_product)
                    ->update(['wholesale_price' => $bestCompra, 'DtoCompra' => $dtoCompra]);

                    DB::connection('presta')->table('product_shop')
                    ->where('id_product', $product->id_product)
                    ->update(['wholesale_price' => $bestCompra]);

                    $data->push([
                        'id_product' => $product->id_product,
                        'tipo' => 'Simple',
                        'nombre' => $product->product_name,
                        'bestCompra' => $bestCompra,
                        'pventa' => $precio_venta,
                        'DtoCompra' => $product->DtoCompra,
                        'NewDtoCompra' => $dtoCompra,
                        'pcompra_mags' => $product->pcompra_mags,
                        'pcompra_abad' => $product->pcompra_abad,
                        'pcompra_cale' => $product->pcompra_cale,
                        'pcompra_ferre' => $product->pcompra_ferre,
                        'pcompra_elect' => $product->pcompra_elect,
                        'pcompra_caly' => $product->pcompra_caly,
                        'wholesale_price' => $product->wholesale_price,
                        'margen' => $margenproducto,
                        'precio_combinacion' => $product->adicional
                    ]);
                }

            }

        }

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
            ->join('product_shop', function (JoinClause $joinClause) {
                $joinClause->on('product.id_product', '=', 'product_shop.id_product');
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

        foreach ($products as $product) {
            $pcompra_mags = ($product->pcompra_mags != Null && $product->pcompra_mags > 0) ? str_replace(',', '.', $product->pcompra_mags) : false ;
            $pcompra_abad = ($product->pcompra_abad != Null && $product->pcompra_abad > 0) ? str_replace(',', '.', $product->pcompra_abad) : false ;
            $pcompra_cale = ($product->pcompra_cale != Null && $product->pcompra_cale > 0) ? str_replace(',', '.', $product->pcompra_cale) : false ;
            $pcompra_ferre = ($product->pcompra_ferre != Null && $product->pcompra_ferre > 0) ? str_replace(',', '.', $product->pcompra_ferre) : false ;
            $pcompra_elect = ($product->pcompra_elect != Null && $product->pcompra_elect > 0) ? str_replace(',', '.', $product->pcompra_elect) : false ;
            $pcompra_caly = ($product->pcompra_caly != Null && $product->pcompra_caly > 0) ? str_replace(',', '.', $product->pcompra_caly) : false ;
            //$pcompra_ditri = ($product->wholesale_price != Null && $product->wholesale_price > 0) ? $product->wholesale_price : false ;
            $pcompra_ditri = false;
            $compra = array($pcompra_mags, $pcompra_abad, $pcompra_cale, $pcompra_ferre, $pcompra_elect, $pcompra_caly, $pcompra_ditri);
            $compra = array_filter($compra);

            if (count($compra) > 0) {
                switch (min($compra)) {
                    case $pcompra_mags:
                        $bestCompra = $pcompra_mags;
                        break;
                    case $pcompra_abad:
                        $bestCompra = $pcompra_abad;
                        break;
                    case $pcompra_cale:
                        $bestCompra = $pcompra_cale;
                        break;
                    case $pcompra_ferre:
                        $bestCompra = $pcompra_ferre;
                        break;
                    case $pcompra_elect:
                        $bestCompra = $pcompra_elect;
                        break;
                    case $pcompra_caly:
                        $bestCompra = $pcompra_caly;
                        break;
                    case $pcompra_ditri:
                        $bestCompra = $product->wholesale_price;
                        break;
                }

                $precio_venta=round($product->price, 2)+round($product->adicional, 2);
                
                $dtoCompra = 100*(1-$bestCompra/$precio_venta);

                if ($dtoCompra <= 0) {
                    $dtoCompra = -99;
                }
                
                $dtoVenta = round($product->dtoVenta*100, 2);
                $margenproducto = 1-((100-$product->DtoCompra)/(100-$dtoVenta));

                DB::connection('presta')->table('product_attribute')
                ->where('id_product_attribute', $product->id_product_attribute)
                ->update(['wholesale_price' => $bestCompra, 'DtoCompra' => $dtoCompra]);

                $data->push([
                    'id_product' => $product->id_product,
                    'tipo' => 'Combinacion',
                    'nombre' => $product->product_name,
                    'bestCompra' => $bestCompra,
                    'pventa' => $precio_venta,
                    'DtoCompra' => $product->DtoCompra,
                    'NewDtoCompra' => $dtoCompra,
                    'pcompra_mags' => $product->pcompra_mags,
                    'pcompra_abad' => $product->pcompra_abad,
                    'pcompra_cale' => $product->pcompra_cale,
                    'pcompra_ferre' => $product->pcompra_ferre,
                    'pcompra_elect' => $product->pcompra_elect,
                    'pcompra_caly' => $product->pcompra_caly,
                    'wholesale_price' => $product->wholesale_price,
                    'margen' => $margenproducto,
                    'precio_combinacion' => $product->adicional
                ]);

            }

        }

        return view('admin.exports.dto-compras', [
            'products' => $data
        ]);
    }
}
