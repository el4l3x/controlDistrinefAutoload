<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class DistibaseController extends Controller
{
    public function __construct() {
        Event::listen(BuildingMenu::class, function(BuildingMenu $event)
        {
            $event->menu->add([
                'text'      => 'Dashboard',
                'route'     => 'distribase.dashboard',
                'active'    => ['distribase/dashboard'],
                'icon'      => 'fas fa-chart-pie mr-2',
                'can'       => 'dashboard.index',
            ]);
            $event->menu->add([
                'text' => 'Socios',
                'icon' => 'fas fa-handshake',
                'submenu' => [
                    [
                        'text' => 'Abad',
                        'route'     => ['distribase.partner', ['partner' => 'abad']],
                        'active'    => ['distribase/abad'],
                        'can'       => 'dashboard.index',
                    ],
                    [
                        'text' => 'Ferreteria Ubetense',
                        'route'     => ['distribase.partner', ['partner' => 'ferreteria-ubetense']],
                        'active'    => ['distribase/ferreteria-ubetense'],
                        'can'       => 'dashboard.index',
                    ],
                    [
                        'text' => 'Magservices',
                        'route'     => ['distribase.partner', ['partner' => 'magserveis']],
                        'active'    => ['distribase/magservices'],
                        'can'       => 'dashboard.index',
                    ],
                    [
                        'text' => 'Calefon',
                        'route'     => ['distribase.partner', ['partner' => 'calefon']],
                        'active'    => ['distribase/calefon'],
                        'can'       => 'dashboard.index',
                    ],
                    [
                        'text' => 'Electromercantil',
                        'route'     => ['distribase.partner', ['partner' => 'electromercantil']],
                        'active'    => ['distribase/electromercantil'],
                        'can'       => 'dashboard.index',
                    ],
                ],
            ]);
        });
    }

    public function dashboard() {
        return view('distribase.dashboard');
    }

    public function partner(Partner $partner) {
        return view('distribase.socios.socio', [
            'partner' => $partner,
        ]);
    }
}
