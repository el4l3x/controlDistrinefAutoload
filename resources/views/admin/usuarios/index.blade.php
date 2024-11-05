@extends('adminlte::page')

@section('title', 'Distrinef')

@section('content_header')
    @can('usuarios.index')
        <a class="btn btn-primary btn-sm float-right" type="button" href="{{ route('usuarios.create') }}">Nuevo Usuario</a>
    @endcan
    <h1>Usuarios</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-custom">

                        <div class="card-body">
                            @php
                                $heads = [
                                    ['label' => 'Nombre'],
                                    ['label' => 'Usuario'],
                                    ['label' => 'Rol'],
                                    ['label' => 'Status'],
                                    ['label' => 'Opciones'],
                                ];

                                $config = [
                                    'ajax' => [
                                        'url' => route('admin.datatable.usuarios'),
                                        'type' => 'POST',
                                        'headers' => [
                                            'X-CSRF-TOKEN'  => csrf_token()
                                        ],
                                    ],
                                    'order' => [[1, 'desc']],
                                    'aLengthMenu'   => [[25, 50, 100, -1], [25, 50, 100, "Todas"]],
                                    'columns' => [
                                        [
                                            'data' => 'name',
                                        ],
                                        [
                                            'data' => 'username',
                                        ],
                                        [
                                            'data' => 'rol',
                                        ],
                                        [
                                            'data' => 'status',
                                        ],
                                        [
                                            'data' => 'opciones',
                                            'orderable' => false,
                                        ],
                                    ],
                                    'language' => [
                                        'url' => '//cdn.datatables.net/plug-ins/2.0.2/i18n/es-ES.json',
                                    ],
                                ];
                            @endphp
                            <x-adminlte-datatable id="usuariosTable" :heads="$heads" :config="$config" beautify striped
                                hoverable with-buttons>
                            </x-adminlte-datatable>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="modal fade" id="modalBorrar" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Usuarios</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <form action="" method="post" id="frmDelete">
                    <div class="modal-body">
                        <p></p>
                            @method('delete')
                            @csrf
                            <input type="hidden" name="id" id="idDel">
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="success" class="btn btn-primary">Continuar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalStatus" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Usuarios</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <form action="" method="post" id="frmDisable">
                <div class="modal-body">
                    <p></p>
                        @csrf
                        <input type="hidden" name="id" id="idStatus">
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="success" class="btn btn-primary">Continuar</button>
                </div>
            </form>
        </div>
    </div>

@stop

@section('plugins.Sweetalert2', true);

@section('js')
    <script>
        $(() => {

            @if($message = session('success'))
                Swal.fire({
                    title: 'Atencion!',
                    text: '{{ $message }}',
                    icon: 'info',
                    timer: 3000,
                    toast: true,
                    position: 'top-end',
                    timerProgressBar: true,
                    showConfirmButton: false,
                })
            @endif

            $('#modalBorrar').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var id = button.data('id')
                var nombre = button.data('nombre')
                var modal = $(this)

                modal.find('.modal-body p').empty()
                modal.find('.modal-body p').append('Esta seguro de dar de baja al usuario ' + nombre + '?')
                modal.find('.modal-title').empty()
                modal.find('.modal-title').text('Borrar ' + nombre)
                modal.find('#idDel').val(id)

                $("#frmDelete").attr('action', 'usuarios/'+id)
            });
            
            $('#modalStatus').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var id = button.data('id')
                var nombre = button.data('nombre')
                var status = button.data('status')
                var modal = $(this)

                if (status != 1) {
                    modal.find('.modal-body p').empty()
                    modal.find('.modal-body p').append('Esta seguro de reactivar al usuario ' + nombre + '?')
                    modal.find('.modal-title').empty()
                    modal.find('.modal-title').text('Reactivar a ' + nombre)
                } else {
                    modal.find('.modal-body p').empty()
                    modal.find('.modal-body p').append('Esta seguro de desactivar al usuario ' + nombre + '?')
                    modal.find('.modal-title').empty()
                    modal.find('.modal-title').text('Desactivar a ' + nombre)                    
                }

                modal.find('#idStatus').val(id)

                $("#frmDisable").attr('action', 'usuarios/status/'+id)
            });
            
        });

    </script>
@stop