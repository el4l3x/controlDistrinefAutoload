@extends('adminlte::page')

@section('title', 'DivisonLed')

@section('content_header')
    <h1>Editar Producto {{ $product->nombre }}</h1>
@stop

@section('content')
    <div class="card">
        
        <div class="card-body">
            
            <div class="row mb-3">
                <div class="col-md-12">

                    <form action="{{ route("divisonled.products.update", $product) }}" method="post">
                        @method('PUT')
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
                            @forelse ($competitors as $item)
                                @if ($item->products()->where('product_id', $product->id)->first() != null)
                                    <x-adminlte-input name="competitor-{{ $item->id }}" label="Url para {{ $item->nombre }}" value="{{ $item->products()->where('product_id', $product->id)->first()->pivot->url }}" enable-old-support fgroup-class="col-lg-6 col-md-6 col-sm-12"/>
                                    <x-slot name="bottomSlot">
                                        @error('competitor-{{ $item->id }}')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </x-slot>
                                @else
                                    <x-adminlte-input name="competitor-{{ $item->id }}" label="Url para {{ $item->nombre }}" enable-old-support fgroup-class="col-lg-6 col-md-6 col-sm-12"/>
                                    <x-slot name="bottomSlot">
                                        @error('competitor-{{ $item->id }}')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </x-slot>
                                @endif                                
                            @empty
                                <small>No hay competidores registrados.</small>
                            @endforelse
                        </div>
                        
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Guardar</button>
                            <a class="btn btn-secondary" role="button" href="{{ route("divisonled.dashboard") }}">Volver</a>
                        </div>
                    </form>

                </div>    
            </div>

        </div>
    </div>

@stop