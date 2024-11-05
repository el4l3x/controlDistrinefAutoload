@extends('adminlte::page')

@section('title', 'Distribase | Dashboard')

@section('content_header')
  <div class="row justify-content-between">
    <h1>Distribase</h1>
  </div>
@stop

@section('content')

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card card-custom">
                
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="card-title">Socios</h2>
                    </div>
                </div>

                <div class="card-body">
                    @php
                    $heads = [
                        ['label' => 'Socio'],
                        ['label' => 'Total CSV'],
                        ['label' => 'Enviados a Distribase'],
                        ['label' => 'Errores Distribase'],
                        ['label' => '% Match Distribase'],
                        ['label' => 'Activos GFC'],
                        ['label' => 'Match GFC'],
                        ['label' => '% Match GFC'],
                    ];

                    $config = [                                
                        'ajax'  => [
                            'url'   => route('distribase.datatable.partners'),
                            'type' => 'POST',
                            'headers' => [
                                'X-CSRF-TOKEN'  => csrf_token()
                            ],
                            'data'  =>   []
                        ],
                        'order' => [[0, 'asc']],
                        'columns' => [
                            [
                                'data'  => "nombre",
                            ], 
                            [
                                'data'  => "totalCsv",
                            ], 
                            [
                                'data'  => "distribase",
                            ],                             
                            [
                                'data'  => "distribaseErrors",
                            ],
                            [
                                'data'  => "distribasePercent",
                            ],
                            [
                                'data'  => "gfc",
                            ],
                            [
                                'data'  => "gfcMatch",
                            ],
                            [
                                'data'  => "gfcPercent",
                            ]
                        ],
                        'language'  => [
                            'url'   => '//cdn.datatables.net/plug-ins/2.0.2/i18n/es-ES.json',
                        ],
                    ];
                    @endphp
                    <x-adminlte-datatable id="partnersTable" :heads="$heads" :config="$config" beautify striped hoverable with-buttons>
                    </x-adminlte-datatable>
                </div>
            </div>    
        </div>    
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card card-custom">
                
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="card-title">Productos NO Matcheados entre CSV de Socios y GFC</h2>
                    </div>
                </div>

                <div class="card-body">
                    @php
                    $heads = [
                        ['label' => 'ID'],
                        'Nombre',
                        ['label' => 'Cod Distribase'],
                        ['label' => 'Cod Auna'],
                        ['label' => 'Cod Telematel'],
                    ];

                    $config = [                                
                        'ajax'  => [
                            'url'   => route('distribase.datatable.nomatch'),
                            'type' => 'POST',
                            'headers' => [
                                'X-CSRF-TOKEN'  => csrf_token()
                            ],
                            'data'  =>   []
                        ],
                        'order' => [[1, 'desc']],
                        'columns' => [
                            [
                                'data'  => "id_product",
                                'width' => '10px'
                            ], 
                            [
                                'data'  => "product_name",
                            ], 
                            [
                                'data'  => "mpn",
                            ], 
                            [
                                'data'  => "CodAuna",
                            ],
                            [
                                'data'  => "CodTelematel",
                            ]
                        ],
                        'language'  => [
                            'url'   => '//cdn.datatables.net/plug-ins/2.0.2/i18n/es-ES.json',
                        ],
                    ];
                    @endphp
                    <x-adminlte-datatable id="nomatchTable" :heads="$heads" :config="$config" beautify striped hoverable with-buttons>
                    </x-adminlte-datatable>
                </div>
            </div>    
        </div>    
    </div>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)

@section('css')
    <style>

    </style>
@stop

@section('js')
    
@stop