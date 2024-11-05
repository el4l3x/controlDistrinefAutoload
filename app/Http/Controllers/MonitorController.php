<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\MonitorPrecios;
use Weidner\Goutte\GoutteFacade;

class MonitorController extends Controller
{
    public function exportarCsv() {

        $productos = Product::with('competidor')->get();

        return (new FastExcel($productos))->download('Monitor_Productos.xlsx', function ($data) {
            return [
                '#'            => $data->idgfc,
                'Referencia'      => $data->reference,
                'Producto'    => $data->nombre,
                'Gasfriocalor'        => $data->competidor()->find(1)->pivot->precio,
                'Climahorro'        => (isset($data->competidor()->find(2)->pivot->precio)) ? $data->competidor()->find(2)->pivot->precio : '',
                'Ahorraclima'        => (isset($data->competidor()->find(3)->pivot->precio)) ? $data->competidor()->find(3)->pivot->precio : '',
                'Expertclima'        => (isset($data->competidor()->find(4)->pivot->precio)) ? $data->competidor()->find(4)->pivot->precio : '',
                'Tucalentadoreconomico'        => (isset($data->competidor()->find(5)->pivot->precio)) ? $data->competidor()->find(5)->pivot->precio : '',
                'Rehabilitaweb'        => (isset($data->competidor()->find(6)->pivot->precio)) ? $data->competidor()->find(6)->pivot->precio : '',
                'Tuandco'        => (isset($data->competidor()->find(7)->pivot->precio)) ? $data->competidor()->find(7)->pivot->precio : '',
                'Climamania'        => (isset($data->competidor()->find(8)->pivot->precio)) ? $data->competidor()->find(8)->pivot->precio : '',
                'Todoenclima'        => (isset($data->competidor()->find(9)->pivot->precio)) ? $data->competidor()->find(9)->pivot->precio : '',
                'Climaprecio'        => (isset($data->competidor()->find(10)->pivot->precio)) ? $data->competidor()->find(10)->pivot->precio : '',
                'Habitium'        => (isset($data->competidor()->find(11)->pivot->precio)) ? $data->competidor()->find(11)->pivot->precio : '',
            ];
        });
    }

    public function scrap(String $parte) {
        ini_set('max_execution_time', 3600);
        $start_time = microtime(true);

        $web = new \Spekulatius\PHPScraper\PHPScraper;
        
        switch ($parte) {
            case '1':
                $products = Product::with('competidor')->where('id', '<', 1409)->get();
                break;

            case '2':
                $products = Product::with('competidor')->where('id', '>', 1408)->where('id', '<', 2368)->get();
                break;

            case '3':
                $products = Product::with('competidor')->where('id', '>', 2367)->get();
                break;
            
            default:
                $products = Product::with('competidor')->get();
                break;
        }

        //$gfcData = Competitor::with('products')->where('id', env('GFC_SCRAP_ID'))->first();
        $orange = collect();
        $red = collect();

        foreach ($products as $product) {

            $gfc_price = $product->competidor()->where('competitor_id', env('GFC_SCRAP_ID'))->first();

            echo "Producto: ".$product->nombre."<br>";
            echo "ID Producto: ".$product->id."<br>";

            foreach ($product->competidor as $competidor) {

                if ($competidor->pivot->url != NULL) {
                    try {
                        $web->go($competidor->pivot->url);
                        $string = $web->filter($competidor->filtro)->text();
                        $string = Str::remove('€', $string);
                        $string = Str::remove(' ', $string);
                        $string = Str::replace('.', '', $string);
                        $string = Str::replace(',', '.', $string);
                        $price = floatval($string);

                        echo "Precio GFC ".$gfc_price->pivot->precio."<br>";
                        echo "Precio Producto ".$competidor->pivot->precio."<br>";
                        echo "URL ".$competidor->pivot->url."<br>";
                        echo "Filtro ".$competidor->filtro."<br>";
                        echo "Nuevo Precio Producto ".$price."<br>";
                        echo "<br>";

                        $product->competidor()->updateExistingPivot($competidor->id, [
                            'precio' => $price,
                            'updated_at' => now(),
                        ]);

                        if ($competidor->id != env('GFC_SCRAP_ID') && $gfc_price->pivot->precio != 0) {
                            $percent = number_format((((($gfc_price->pivot->precio - $price)/$gfc_price->pivot->precio))*100)*-1, 2);

                            if ($percent < -2) {
                                $red->push([
                                    'id' => $product->idgfc,
                                    'producto' => $product->nombre,
                                    'competidor' => $competidor->nombre,
                                    'gfc_price' => $gfc_price->pivot->precio,
                                    'price' => $price,
                                    'percent'=> $percent,
                                ]);
                            }

                            if ($percent > -2 && $percent < 0) {
                                $orange->push([
                                    'id' => $product->idgfc,
                                    'producto' => $product->nombre,
                                    'competidor' => $competidor->nombre,
                                    'gfc_price' => $gfc_price->pivot->precio,
                                    'price' => $price,
                                    'percent'=> $percent,
                                ]);
                            }
                        }

                    } catch (\Throwable $th) {
                        //return $th->getMessage().' '.$data->pivot->url.' '.$data->pivot->id;
                        Log::info("------------ Fallo el scrap ------------");
                        Log::info($th->getMessage().' '.$th->getFile().' '.$th->getLine().' '.$competidor->pivot->url.' ID Product: '.$product->id);

                        $product->competidor()->updateExistingPivot($competidor->id, [
                            'precio' => 0,
                            'updated_at' => now(),
                        ]);
                    }
                    
                }
                
            }

            echo "---------------------- FIN PRODUCTO ----------------------<br><br>";
        }    

        try {
            (new FastExcel($red))->export('Monitor_Productos_Rojos.xlsx', function ($data) {
                return [
                    'Id'            => $data['id'],
                    'Producto'      => $data['producto'],
                    'Competidor'    => $data['competidor'],
                    'Precio'        => $data['price'].'€ ',
                    '%'       => $data['percent'].'%',
                    'Precio GFC'    => $data['gfc_price'].'€',
                ];
            });

            (new FastExcel($orange))->export('Monitor_Productos_Naranjas.xlsx', function ($data) {
                return [
                    'Id'            => $data['id'],
                    'Producto'      => $data['producto'],
                    'Competidor'    => $data['competidor'],
                    'Precio'        => $data['price'].'€ ',
                    '%'       => $data['percent'].'%',
                    'Precio GFC'    => $data['gfc_price'].'€',
                ];
            });
        } catch (\Throwable $th) {
            //throw $th;
            Log::info("------------ Fallo el export ------------");
            Log::info($th->getMessage().' '.$th->getFile().' '.$th->getLine());
        }

        try {
            Mail::to('davidlopez.fya@gmail.com')->send(new MonitorPrecios($red, $orange));
            Mail::to('chavesgomez@gmail.com')->send(new MonitorPrecios($red, $orange));
            Mail::to('japorto@distrinef.com')->send(new MonitorPrecios($red, $orange));
        } catch (\Throwable $th) {
            //throw $th;
            Log::info("------------ Fallo el mail ------------");
            Log::info($th->getMessage().' '.$th->getFile().' '.$th->getLine());
        }
        
        $end_time = microtime(true);
        $duration = $end_time - $start_time;
        $hours = (int)($duration/60/60);
        $minutes = (int)($duration/60)-$hours*60;
        $seconds = (int)$duration-$hours*60*60-$minutes*60;
        echo '<p>Tiempo empleado para completar el proceso: <strong>'.$minutes.' minutos y '.$seconds.' segundos.</strong></p>';
    }

    public function scrapOne(Product $product) {
        //$web = new \Spekulatius\PHPScraper\PHPScraper;

        //return var_dump($product->competidor());

        foreach ($product->competidor()->get() as $value) {
            try {
                echo "Producto: ".$product->nombre;
                echo "<br>";
                echo "ID: ".$product->id;
                echo "<br>";
                echo "URL: ".$value->pivot->url;
                echo "<br>";
                echo "Filtro: ".$value->filtro;
                echo "<br>";

                if ($value->id == 11) {
                    $crawler = GoutteFacade::request('GET', $value->pivot->url);
                    $string = $crawler->filter('#our_price_display_with_tax')->text();
                    echo $string;
                    echo "<br>";
                    echo "<br>";
                }
                /* $crawler->filter('#our_price_display_with_tax')->each(function ($node) {
                    //dump($node->text());
                    echo $node->text();
                    echo "<br>";
                    echo "<br>";
                }); */
                
                /* $web->go($value->pivot->url);
                $string = $web->filter($value->filtro)->text();
                $string = Str::remove('€', $string);
                $string = Str::remove(' ', $string);
                $string = Str::replace('.', '', $string);
                $string = Str::replace(',', '.', $string);
                $price = floatval($string); */

                /* echo "Precio Scrap: ".$price;
                echo "<br>";
                echo "<br>";

                $product->competidor()->updateExistingPivot($value->id, [
                    'precio' => $price,
                    'updated_at' => now(),
                ]); */
                
            } catch (\Throwable $th) {
                return $th->getMessage();
            }
        }
    }

}
