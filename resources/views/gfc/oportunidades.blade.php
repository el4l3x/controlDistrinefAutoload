@extends('adminlte::page')

@section('title', 'Gasfriocalor | Oportunidades de Ventas')

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
                    <div class="card card-custom">

                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h2 class="card-title">Oportunidades de Venta</h2>
                            </div>
                            <div class="alert alert-info alert-dismissible mt-5">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h5><i class="icon fas fa-info"></i> Mas Info</h5>
                                Se Muestra Listado de Carritos Sin Finalizar, con unos Determinados Requisitos que los convierten en Potencialmente Transaccionales.
                            </div>
                        </div>

                        <div class="card-body">
                            @php
                                $heads = [
                                    ['label' => 'Id Cart'],
                                    'Fecha',
                                    'Cliente',
                                    ['label' => 'Correo'],
                                    ['label' => 'Telefonos'],
                                    ['label' => 'Productos'],
                                    ['label' => 'Contactado'],
                                ];

                                $config = [
                                    'ajax' => [
                                        'url' => route('gfc.datatable.oportunidades.ventas'),
                                        'type' => 'POST',
                                        'headers' => [
                                            'X-CSRF-TOKEN'  => csrf_token()
                                        ],
                                    ],
                                    'order' => [[1, 'desc']],
                                    'aLengthMenu'   => [[25, 50, 100, -1], [25, 50, 100, "Todas"]],
                                    'columns' => [
                                        [
                                            'data' => 'cartId',
                                            'width' => '50px',
                                        ],
                                        [
                                            'data' => 'cartDate',
                                            'width' => '70px',
                                        ],
                                        [
                                            'data' => 'nombre',
                                        ],
                                        [
                                            'data' => 'correo',
                                        ],
                                        [
                                            'data' => 'telefono',
                                        ],
                                        [
                                            'data' => 'products_name',
                                            'width' => '90px',
                                        ],
                                        [
                                            'data' => 'contacto',
                                            'width' => '50px',
                                        ],
                                    ],
                                    'language' => [
                                        'url' => '//cdn.datatables.net/plug-ins/2.0.2/i18n/es-ES.json',
                                    ],
                                ];
                            @endphp
                            <x-adminlte-datatable id="oportunidadesTable" :heads="$heads" :config="$config" beautify striped
                                hoverable with-buttons>
                            </x-adminlte-datatable>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="modal fade" id="modalProductos" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Productos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <div class="modal-body">
                    <p></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="modal fade" id="modalNewComment" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Contactar Cliente</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <form action="" id="frmComment" method="post">
                    @method('POST')
                    @csrf
                    <div class="modal-body">
                        <x-adminlte-textarea name="comment" placeholder="Insertar comentario..."/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="success" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div>
        <div class="modal fade" id="modalComments" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Oportunidad Contactada</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <form action="" id="frmCommentEdit" method="post">
                    @method('POST')
                    @csrf
                    <div class="modal-body">
                        <div class="row justify-content-end">
                            <small class="text-muted"></small>
                        </div>
                        <x-adminlte-textarea name="comment" id="commentEdit" placeholder="Insertar comentario..."/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="success" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)

@section('css')
    <style>
        .mt-c {
            margin-top: 20px;
        }

    </style>
@stop

@section('js')
    <script>
        $(() => {

            $('#modalProductos').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var id = button.data('id')
                var nombres = button.data('nombres')
                var modal = $(this)
                modal.find('.modal-body p').empty()
                modal.find('.modal-body p').append(nombres)
                modal.find('.modal-title').empty()
                modal.find('.modal-title').text('Productos del carrito ' + id)
            });
            
            $('#modalComments').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var comment = button.data('comment')
                var id = button.data('id')
                var date = button.data('date')
                var modal = $(this)
                modal.find('.modal-body small').empty()
                modal.find('.modal-body small').append('Ultimo contacto el '+date)
                $('#commentEdit').val(comment)

                $("#frmCommentEdit").attr('action', 'oportunidad/contactar/'+id)
            });

            $('#modalNewComment').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var id = button.data('id')
                var modal = $(this)

                $("#frmComment").attr('action', 'oportunidad/contactar/'+id)
            });
            
        });

    </script>
@stop
