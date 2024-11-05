@extends('adminlte::page')

@section('title', 'Gasfriocalor | Productos')

@section('content_header')
  <div class="row justify-content-between">
    <h1>Gasfriocalor.com</h1>
  </div>
@stop

@section('content')
  <div class="card">
        <div class="card-body">

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">

                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="card-title">Productos Nunca Vendidos</h2>

                                <a href="{{ route('productos.nunca.vendidos.exportar.csv') }}" class="btn btn-primary" target="_blank">
                                    Exportación Completa
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            @php
                            $heads = [
                                ['label' => 'Id'],
                                ['label' => 'Referencia'],
                                ['label' => 'Nombre'],
                            ];
                            $config = [                                
                                'order' => [[0, 'asc']],
                                'ajax' => [
                                    'url' => route('gfc.datatable.nunca.vendidos'),
                                    'type' => 'POST',
                                    'headers' => [
                                        'X-CSRF-TOKEN'  => csrf_token()
                                    ],
                                ],
                                'processing' => true,
			                    'serverSide' => true,
                                'columns' => [
                                    [
                                        'data' => 'id_product',
                                    ],
                                    [
                                        'data' => 'reference',
                                    ],
                                    [
                                        'data' => 'name',
                                    ],
                                ],
                                'aLengthMenu'   => [[25, 50, 100], [25, 50, 100]],
                                'language'  => [
                                    'url'   => '//cdn.datatables.net/plug-ins/2.0.2/i18n/es-ES.json',
                                ],
                            ];
                            @endphp
                            <x-adminlte-datatable id="nunca-vendidos" :heads="$heads" :config="$config" beautify compressed striped hoverable with-buttons>
                                
                            </x-adminlte-datatable>
                        </div>
                    </div>    
                </div>    
            </div>
        </div>
    </div>
@stop

@section('css')

@stop

@section('js')
    
@stop