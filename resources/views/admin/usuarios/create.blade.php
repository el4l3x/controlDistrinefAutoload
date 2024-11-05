@extends('adminlte::page')

@section('title', 'Distrinef')

@section('content_header')
    <h1>Nuevo Usuario</h1>    
@stop

@section('content')
    <div class="card">
        
        <div class="card-body">
            
            <div class="row mb-3">
                <div class="col-md-12">

                    <form action="{{ route("usuarios.store") }}" method="post">
                        @csrf
                        <div class="row">
                            <x-adminlte-input name="nombre" label="Nombre" placeholder="" enable-old-support fgroup-class="col-lg-6 col-md-6 col-sm-12">
                                <x-slot name="bottomSlot">
                                    @error('nombre')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </x-slot>
                            </x-adminlte-input>
                        
                            <x-adminlte-input name="usuario" label="Usuario" placeholder="" enable-old-support fgroup-class="col-lg-6 col-md-6 col-sm-12">
                                <x-slot name="bottomSlot">
                                    @error('usuario')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                        
                        <div class="row">
                            <x-adminlte-input name="clave" type="password" label="Clave" placeholder="" enable-old-support fgroup-class="col-lg-6 col-md-6 col-sm-12">
                                <x-slot name="bottomSlot">
                                    @error('clave')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </x-slot>
                            </x-adminlte-input>
                        
                            <x-adminlte-input name="clave_confirmation" type="password" label="Confirmar Clave" placeholder="" enable-old-support fgroup-class="col-lg-6 col-md-6 col-sm-12">
                                <x-slot name="bottomSlot">
                                    @error('clave_confirmation')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </x-slot>
                            </x-adminlte-input>
                        </div>

                        <div class="row">
                            <x-adminlte-select name="rol" label="Rol de Usuario" onchange="selectRol(this)" enable-old-support fgroup-class="col-lg-6 col-md-6 col-sm-12">
                                @foreach ($roles as $rol)
                                    <option value="{{ $rol->id }}">{{ $rol->name }}</option>
                                @endforeach
                            </x-adminlte-select>

                            @php
                            $config = [
                                "placeholder" => "",
                                "allowClear" => false,
                                "liveSearch" => true,
                                "liveSearchPlaceholder" => "Buscar...",
                                "title" => "Selecciona los permisos...",
                                "showTick" => false,
                                "actionsBox" => false,
                            ];
                            @endphp
                            <div class="col-lg-6 col-md-6 col-sm-12 d-none" id="permissions">
                                <x-adminlte-select2 id="permisos" name="permisos[]" label="Permisos Asignados" igroup-size="md" :config="$config" multiple enable-old-support>
                                    <x-slot name="prependSlot">
                                        <div class="input-group-text">
                                            <i class="fas fa-tag"></i>
                                        </div>
                                    </x-slot>

                                    @foreach ($permissions as $permiso)
                                        <option value="{{ $permiso->name }}">{{ $permiso->description }}</option>
                                    @endforeach

                                </x-adminlte-select2>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Guardar</button>
                            <a class="btn btn-secondary" role="button" href="{{ route("usuarios.index") }}">Volver</a>
                        </div>
                    </form>

                </div>    
            </div>

        </div>
    </div>

@stop

@section('plugins.Select2', true)

@section('css')
    
@stop

@section('js')    
    <script>
        function selectRol(select) {
            if (select.value == 3) {
                $('#permissions').removeClass('d-none')
            } else {
                $('#permissions').addClass('d-none')
            }
        }

        $(document).ready(function() {
            
        });
    </script>
@stop