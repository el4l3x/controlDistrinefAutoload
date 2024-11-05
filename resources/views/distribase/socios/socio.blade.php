@extends('adminlte::page')

@section('title', 'Distribase | Dashboard')

@section('content_header')
  <div class="row justify-content-between">
    <h1>{{ $partner->name }}</h1>
  </div>
@stop

@section('content')

    @if ($partner->widgets->count() < 1)
        <div class="alert alert-danger" role="alert">
            No se han registrado reportes Hoy
        </div>       
    @else
        <div class="row">
            <div class="col">            
                <x-adminlte-small-box title="{{ $partner->widgets[0]->pivot->total }}" text="Filas totales del CSV" icon="fas fa-file-csv" theme="success" url="#" url-text="Mas Información"/>
            </div>

            @if($partner->widgets()->where('report_id', 3)->first() !== NULL)                
                <div class="col">
                    <x-adminlte-small-box title="{{ $partner->widgets()->where('report_id', 3)->first()->pivot->afectados }}" text="Introducidas en la Distribase" icon="fas fa-database" theme="success" url="#" url-text="Mas Información"/>
                </div>

                <div class="col">
                    <x-adminlte-small-box title="{{ Number::percentage(($partner->widgets()->where('report_id', 3)->sum('afectados')/$partner->widgets()->where('report_id', 3)->sum('total'))*100, maxPrecision: 2) }}" text="% matching Distribase" icon="fas fa-database" theme="success" url="#" url-text="Mas Información"/>
                </div>
            @endif
            
            <div class="col">
                <x-adminlte-small-box title="{{ $partner->widgets()->sum('revisados') }}" text="Activos en GFC" icon="fas fa-tasks" theme="success" url="#" url-text="Mas Información"/>
            </div>

            <div class="col">
                <x-adminlte-small-box title="{{ $partner->widgets()->where('report_id', '!=', 3)->sum('afectados') }}" text="Matcheados con GFC" icon="fas fa-store" theme="success" url="#" url-text="Mas Información"/>
            </div>

            @if ($partner->widgets()->sum('revisados'))
                <div class="col">
                    <x-adminlte-small-box title="{{ Number::percentage(($partner->widgets()->where('report_id', '!=', 3)->sum('afectados')/$partner->widgets()->sum('revisados'))*100, maxPrecision: 2) }}" text="% matching GFC" icon="fas fa-percent" theme="success" url="#" url-text="Mas Información"/>
                </div>                
            @endif

        </div>
    @endif

@stop

@section('css')
    <style>

    </style>
@stop

@section('js')
    
@stop