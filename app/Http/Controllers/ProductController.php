<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Competitor;
use App\Models\CompetitorDivisonled;
use App\Models\ProductDivisonled;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Spekulatius\PHPScraper\PHPScraper;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;

class ProductController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $competitors = Competitor::all();

        return view('gfc.products.create', compact('competitors'));
    }
    
    public function createDivisonled()
    {
        $competitors = CompetitorDivisonled::all();

        return view('divisonled.products.create', compact('competitors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            DB::beginTransaction();

            $competitors = Competitor::all();

            $idGfc = env('GFC_SCRAP_ID');            

            if ($request->has('competitor-'.$idGfc) && $request->input('competitor-'.$idGfc) != null) {
                try {
                    $web = new PHPScraper();
                    $web->go($request->input('competitor-'.$idGfc));
                    $nombre = $web->filter("//h1[@class='h1 page-title']//span")->text();
                    $id = $web->filter("//input[@id='product_page_product_id']")->attr('value');
                    $product = new Product();
                    $product->nombre = $nombre;
                    $product->idgfc = $id;
                    $reference = DB::connection('presta')->table('product')
                        ->select('reference')
                        ->where('id_product', $id)
                        ->first();

                    $product->reference = $reference->reference;
                    $product->save();
                } catch (\Throwable $th) {
                    return $th->getMessage();
                    /* $string = null; */
                }
            }

            foreach ($competitors as $key => $value) {
                if ($request->has('competitor-'.$value->id) && $request->input('competitor-'.$value->id) != null) {
                    try {
                        $web = new PHPScraper();
                        $web->go($request->input('competitor-'.$value->id));
                        $string = $web->filter($value->filtro)->text();
                        $string = Str::remove('€', $string);
                        $string = Str::replace('.', '', $string);
                        $string = Str::replace(',', '.', $string);
                    } catch (\Throwable $th) {
                        $string = null;
                    }

                    $product->competidor()->attach($value->id, [
                        'url'       => $request->input('competitor-'.$value->id),
                        'precio'    => floatval($string),
                    ]);

                    /* Product::updateOrCreate(
                        ['nombre' => $request->nombre, 'competitor_id' => $value->id], 
                        ['url' => $request->input('competitor-'.$value->id), 'precio' => floatval(Str::replace(',', '.', $string))]
                    ); */
                }
            }

            DB::commit();

            return redirect()->route('gfc.monprice');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            //return $th->getMessage();
            /* return redirect()->route('gfc.monprice'); */
        }
    }

    public function storeDivisonled(StoreProductRequest $request)
    {
        try {
            DB::beginTransaction();

            $competitors = CompetitorDivisonled::all();

            $idGfc = env('DLED_SCRAP_ID');            

            if ($request->has('competitor-'.$idGfc) && $request->input('competitor-'.$idGfc) != null) {
                try {
                    $web = new PHPScraper();
                    $web->go($request->input('competitor-'.$idGfc));
                    $nombre = $web->filter("//h1[@class='h1 product-detail-name desk']")->text();
                    $id = $web->filter("//span[@itemprop='sku']")->text();
                    $product = new ProductDivisonled();
                    $product->nombre = $nombre;
                    $product->reference = $id;
                    $product->save();
                } catch (\Throwable $th) {
                    return $th->getMessage().' en '.$request->input('competitor-'.$idGfc);
                    /* $string = null; */
                }
            }

            foreach ($competitors as $key => $value) {
                if ($request->has('competitor-'.$value->id) && $request->input('competitor-'.$value->id) != null) {
                    try {
                        $web = new PHPScraper();
                        $web->go($request->input('competitor-'.$value->id));
                        $string = $web->filter($value->filtro)->text();
                        $string = Str::remove('€', $string);
                        $string = Str::replace('.', '', $string);
                        $string = Str::replace(',', '.', $string);
                    } catch (\Throwable $th) {
                        $string = null;
                    }

                    $product->competidor()->attach($value->id, [
                        'url'       => $request->input('competitor-'.$value->id),
                        'precio'    => floatval($string),
                    ]);

                    /* Product::updateOrCreate(
                        ['nombre' => $request->nombre, 'competitor_id' => $value->id], 
                        ['url' => $request->input('competitor-'.$value->id), 'precio' => floatval(Str::replace(',', '.', $string))]
                    ); */
                }
            }

            DB::commit();

            return redirect()->route('divisonled.dashboard');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            //return $th->getMessage();
            /* return redirect()->route('gfc.monprice'); */
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $competitors = Competitor::with('products')->get();

        return view('gfc.products.edit', [
            'competitors'   =>  $competitors,
            'product'       =>  $product,
        ]);
    }
    
    public function editDivisonled(ProductDivisonled $product)
    {
        $competitors = CompetitorDivisonled::with('products')->get();

        return view('divisonled.products.edit', [
            'competitors'   =>  $competitors,
            'product'       =>  $product,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            DB::beginTransaction();

            $competitors = Competitor::all();

            foreach ($competitors as $key => $value) {
                if ($request->has('competitor-'.$value->id) && $request->input('competitor-'.$value->id) != null) {
                    try {
                        $web = new PHPScraper();
                        $web->go($request->input('competitor-'.$value->id));
                        $string = $web->filter($value->filtro)->text();
                        $string = Str::remove('€', $string);
                        $string = Str::replace('.', '', $string);
                        $string = Str::replace(',', '.', $string);
                    } catch (\Throwable $th) {
                        /* return $th->getMessage().' - '.$request->input('competitor-'.$value->id); */
                        $string = null;
                    }
                    
                    if ($product->competidor()->find($value->id) != null) {
                        $product->competidor()->updateExistingPivot($value->id, [
                            'url' => $request->input('competitor-'.$value->id),
                            'precio' => floatval($string),
                        ]);
                    } else {
                        $product->competidor()->attach($value->id, [
                            'url'       => $request->input('competitor-'.$value->id),
                            'precio'    => floatval($string),
                        ]);
                    }                    
                }
            }

            DB::commit();

            return redirect()->route('gfc.monprice');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    
    public function updateDivisonled(UpdateProductRequest $request, ProductDivisonled $product)
    {
        try {
            DB::beginTransaction();

            $competitors = CompetitorDivisonled::all();

            foreach ($competitors as $key => $value) {
                if ($request->has('competitor-'.$value->id) && $request->input('competitor-'.$value->id) != null) {
                    try {
                        $web = new PHPScraper();
                        $web->go($request->input('competitor-'.$value->id));
                        $string = $web->filter($value->filtro)->text();
                        $string = Str::remove('€', $string);
                        $string = Str::replace('.', '', $string);
                        $string = Str::replace(',', '.', $string);
                    } catch (\Throwable $th) {
                        /* return $th->getMessage().' - '.$request->input('competitor-'.$value->id); */
                        $string = null;
                    }
                    
                    if ($product->competidor()->find($value->id) != null) {
                        $product->competidor()->updateExistingPivot($value->id, [
                            'url' => $request->input('competitor-'.$value->id),
                            'precio' => floatval($string),
                        ]);
                    } else {
                        $product->competidor()->attach($value->id, [
                            'url'       => $request->input('competitor-'.$value->id),
                            'precio'    => floatval($string),
                        ]);
                    }                    
                }
            }

            DB::commit();

            return redirect()->route('divisonled.dashboard');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            DB::table('competitor_product')->where('product_id', $product->id)->delete();
            $product->delete();

            DB::commit();

            return redirect()->route('gfc.monprice');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    
    public function destroyDivisonled(ProductDivisonled $product)
    {
        try {
            DB::beginTransaction();

            DB::table('competitor_product_divisonled')->where('product_id', $product->id)->delete();
            $product->delete();

            DB::commit();

            return redirect()->route('divisonled.dashboard');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function productosNuncaVendidosExportar() {
        //$productos = Product::with('competidor')->get();

        $nuncaVendidos = DB::connection('presta')->table('product')
                ->select('product.id_product as id_product', 'product.reference as reference', 'product_lang.name as name')
                ->join('product_lang', function (JoinClause $joinClause) {
                    $joinClause->on('product.id_product', '=', 'product_lang.id_product');
                })
                ->join('order_detail', 'order_detail.product_id', '=', 'product.id_product', 'left outer')
                ->where('active', 1)
                ->whereNull('order_detail.product_id')
                ->groupBy('product.id_product')
                ->get();

        return (new FastExcel($nuncaVendidos))->download('Nunca_vendidos.xlsx', function ($data) {
            return [
                '#'            => $data->id_product,
                'Referencia'      => $data->reference,
                'Producto'    => $data->name,
            ];
        });
    }
}
