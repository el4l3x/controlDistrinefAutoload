@extends('adminlte::page')

@section('title', 'Mejores Productos')

@section('content_header')
    <div class="row justify-content-between">
        <h1>Mejores Productos</h1>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-custom">
                        
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="card-title">Agregue las categorias a las que se le realizara seguimiento.</h2>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-12">

                                    <form action="{{ route("gfc.bestProducts.categories") }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="row">
                                            {{-- <x-adminlte-input-file name="categorias" label="Suba un archivo csv con formato nombre;id para las categorias" fgroup-class="col-lg-12 col-md-12 col-sm-12" legend="Buscar..."/>
                                            <x-slot name="bottomSlot">
                                                @error('categorias')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </x-slot> --}}

                                            @php
                                            $config = [
                                                'allowedFileTypes'  => ['text'],
                                                'allowedFileExtensions'  => ['csv'],
                                                'language'          => 'es',
                                                'required'          => true,
                                                'browseOnZoneClick' => true,
                                            ];
                                            @endphp
                                            <x-adminlte-input-file-krajee name="categorias" label="Suba un archivo csv con formato nombre;id para las categorias" :config="$config" fgroup-class="col-lg-6 col-md-6 col-sm-12" data-msg-placeholder="Buscar archivo CSV" disable-feedback/>
                                        </div>
                                        
                                        <div class="form-group">
                                            <button class="btn btn-primary" type="submit">Guardar</button>
                                            <a class="btn btn-secondary" role="button" href="{{ route("gfc.dashboard") }}">Volver</a>
                                        </div>
                                    </form>

                                </div>    
                            </div>
                        </div>
                    </div>    
                </div>    
            </div>

        </div>
    </div>

@stop

@section('plugins.KrajeeFileinput', true)

@section('css')
    <style>
        
    </style>
@stop

@section('js')
    <script>

    </script>
@stop