<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class PrivadoController extends Controller
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
            $event->menu->add([
                'text' => 'Descargar Informes Excel',
                'route'  => 'gfc.privado.descargar-excels',
                'icon'      => 'fas fa-file-excel mr-2',
                'can'       => 'informes.excel',
            ]);
            $event->menu->add([
                'text' => 'Consultar Stocks y Netos',
                'route'  => 'gfc.privado.consulta_stock-netos_editor',
                'icon'      => 'fas fa-boxes mr-2',
                'can'       => 'consulta.stocks.netos',
            ]);
            $event->menu->add([
                'text' => 'Desbloquear Pedidos',
                'route'  => 'gfc.privado.desbloquear-pedidos',
                'icon'      => 'fas fa-unlock-alt mr-2',
                'can'       => 'desbloquear.pedidos',
            ]);
            $event->menu->add([
                'text' => "Subir Dto's de Compra CSV",
                'route'  => 'gfc.privado.upload_dtocompra',
                'target' => '_blank',
                'icon'      => 'fas fa-file-csv mr-2',
                'can'       => 'subir.dtos.compra',
            ]);
            $event->menu->add([
                'text' => 'Modificar Precios en Masa',
                'route'  => 'gfc.privado.cambio-precios',
                'target' => '_blank',
                'icon'      => 'fas fa-edit mr-2',
                'can'       => 'modificar.precios',
            ]);
        });
    }

    public function cambioPrecios() {
        include(app_path() . '/privado/cambio-precios.php');
    }
    
    public function desbloquearPedidos(Request $request) {
        /* include(app_path() . '/privado/desbloquear-pedidos.php'); */
        return view('gfc.privado.desbloquear-pedidos', compact('request'));
    }
    
    public function descargarExcels(Request $request) {
        return view('gfc.privado.descargar-excels', compact('request'));
        /* include(app_path() . '/privado/descargar-excels.php'); */
    }
    
    public function uploadDtocompra() {
        include(app_path() . '/privado/upload_dtocompra.php');
    }
	
	public function uploadcsv() {
        include(app_path() . '/privado/uploadcsv.php');
		if ($result) {
			return redirect()->route('gfc.privado.upload_dtocompra', [
				'ok'=> 1
			]);
		}
    }
    
    public function consultaStockNetosEditor(Request $request) {
        return view('gfc.privado.stock-netos', compact('request'));
        /* include(app_path() . '/privado/consulta_stock-netos_editor.php'); */
    }
}
