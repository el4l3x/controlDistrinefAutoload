@extends('adminlte::page')

@section('title', 'Gasfriocalor | Dashboard')

@section('content_header')
  <div class="row justify-content-between">
    <h1>Gasfriocalor.com</h1>

    <form action="{{ route('gfc.dashboards.dates') }}" method="post" id="frmDateRange" class="form-inline">
      @csrf
      <input type="hidden" id="start" name="start">
      <input type="hidden" id="end" name="end">
      <div class="form-group">
        <label for="range-date" style="margin-right: 5px">Desde - Hasta</label>
        <div class="input-group input-group-sm">
          <div class="input-group-prepend">
            <div class="input-group-text bg-dark">
              <i class="fas fa-calendar-alt"></i>
            </div>
          </div>
          <input id="range-date" class="form-control" name="range-date">
        </div>
      </div>
    </form>

  </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            {{-- Pedidos Entrados --}}
            <x-adminlte-small-box title="{{ $pedidosEntrados }}" text="Pedidos Entrados" icon="far fa-chart-bar" theme="primary" url="#" url-text="Mas Información"/>
        </div>
        
        <div class="col-md-3">
            {{-- Importe Facturado --}}
            <x-adminlte-small-box title="{{ $importeFacturado }}" text="Importe Facturado" icon="far fa-chart-bar" theme="primary" url="#" url-text="Mas Información"/>
        </div>

        <div class="col-md-3">
            {{-- Carritos Totales --}}
            <x-adminlte-small-box title="{{ $carritosTotales }}" text="Carritos Totales" icon="fas fa-shopping-cart" theme="primary" url="#" url-text="Mas Información"/>
        </div>

        <div class="col-md-3">
            {{-- Carritos Clientes --}}            
            <x-adminlte-small-box title="{{ $carritosClientes }}" text="Carritos Clientes" icon="fas fa-shopping-cart" theme="primary" url="#" url-text="Mas Información"/>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            {{-- Productos Activos Hoy --}}
            <x-adminlte-small-box title="{{ $productsAct }}" text="Productos Activos" icon="fas fa-shopping-bag" theme="warning" url="#" url-text="Mas Información"/>
        </div>
        
        <div class="col-md-3">
            {{-- Productos Desactivados Hoy --}}
            <x-adminlte-small-box title="{{ $productsDes }}" text="Productos Desactivados" icon="fas fa-shopping-bag" theme="warning" url="#" url-text="Mas Información"/>
        </div>

        <div class="col-md-3">
            {{-- Combinaciones Activas --}}
            <x-adminlte-small-box title="{{ $productsUpd }}" text="Combinaciones Activas" icon="fas fa-shopping-bag" theme="warning" url="#" url-text="Mas Información"/>
        </div>

        <div class="col-md-3">
            {{-- Productos Productos Nunca Vendidos --}}            
            <x-adminlte-small-box title="{{ $productsNeverSales }}" text="Productos Nunca Vendidos" icon="fas fa-shopping-bag" theme="warning" url="{{ route('gfc.productos.novendidos') }}" url-text="Mas Información"/>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-custom">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-chart-pie mr-1"></i>
                  Graficos
                </h3>
                <div class="card-tools">
                  <ul class="nav nav-pills ml-auto">
                    <li class="nav-item">
                      <a class="nav-link active" href="#revenue-chart" data-toggle="tab">Pedidos</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#sales-chart" data-toggle="tab">Facturacion</a>
                    </li>
                  </ul>
                </div>
              </div><!-- /.card-header -->
              <div class="card-body bg-gradient-info">
                <div class="tab-content p-0">
                  <!-- Morris chart - Sales -->
                  <div class="chart tab-pane active" id="revenue-chart"
                       style="position: relative; height: 300px;">
                      <canvas id="revenue-chart-canvas" height="300" style="height: 300px;"></canvas>
                   </div>
                  <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                    <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>
                  </div>
                </div>
              </div><!-- /.card-body -->
              <div class="card-footer bg-gradient-info">
                <div class="row mb-4">
                  <div class="col-4 text-center">
                    <input type="text" class="knob" data-readonly="true" value="{{ $airesPedidos }}" data-max="{{ $pedidosEntrados }}" data-width="60" data-height="60"
                           data-fgColor="#39CCCC">

                    <div class="text-white">Aires Acondicionados</div>
                  </div>
                  <!-- ./col -->
                  <div class="col-4 text-center">
                    <input type="text" class="knob" data-readonly="true" value="{{ $calderasPedidos }}" data-max="{{ $pedidosEntrados }}" data-width="60" data-height="60"
                           data-fgColor="#39CCCC">

                    <div class="text-white">Calderas</div>
                  </div>
                  <!-- ./col -->
                  <div class="col-4 text-center">
                    <input type="text" class="knob" data-readonly="true" value="{{ $aerotermiaPedidos }}" data-max="{{ $pedidosEntrados }}" data-width="60" data-height="60"
                           data-fgColor="#39CCCC">

                    <div class="text-white">Aerotermia</div>
                  </div>
                  <!-- ./col -->
                </div>
                <!-- /.row -->
                <div class="row">
                  <div class="col-4 text-center">
                    <input type="text" class="knob" data-readonly="true" value="{{ $ventilacionPedidos }}" data-max="{{ $pedidosEntrados }}" data-width="60" data-height="60"
                           data-fgColor="#39CCCC">

                    <div class="text-white">Ventilación</div>
                  </div>
                  <!-- ./col -->
                  <div class="col-4 text-center">
                    <input type="text" class="knob" data-readonly="true" value="{{ $calentadoresGasPedidos }}" data-max="{{ $pedidosEntrados }}" data-width="60" data-height="60"
                           data-fgColor="#39CCCC">

                    <div class="text-white">Calentadores a Gas</div>
                  </div>
                  <!-- ./col -->
                  <div class="col-4 text-center">
                    <input type="text" class="knob" data-readonly="true" value="{{ $termosElectricosPedidos }}" data-max="{{ $pedidosEntrados }}" data-width="60" data-height="60"
                           data-fgColor="#39CCCC">

                    <div class="text-white">Termos Electricos</div>
                  </div>
                  <!-- ./col -->
                </div>
                <!-- /.row -->
              </div>
            </div>
        </div>

    </div>
@stop

@section('plugins.DateRangePicker', true)
@section('plugins.Chartjs', true)
@section('plugins.JqueryKnob', true)

@section('css')
    <style>
        .card-custom:not(.card-outline) .card-header {
            background-color: #EEEEEE;
        }
    </style>
@stop

@section('js')
    <script>
      $(() => {
        const defaultRanges = {
            'Hoy': [
                moment().startOf('day'),
                moment().endOf('day')
            ],
            'Ayer': [
                moment().subtract(1, 'days').startOf('day'),
                moment().subtract(1, 'days').endOf('day')
            ],
            'Ultimos 7 Dias': [
                moment().subtract(6, 'days'),
                moment()
            ],
            'Ultimos 30 Dias': [
                moment().subtract(29, 'days'),
                moment()
            ],
            'Este Mes': [
                moment().startOf('month'),
                moment().endOf('month')
            ],
            'Ultimo Mes': [
                moment().subtract(1, 'month').startOf('month'),
                moment().subtract(1, 'month').endOf('month')
            ],
        }

        const startDate = @json($startDate);
        const endDate = @json($endDate);

        moment.locale('es');

        $('#range-date').daterangepicker({
            opens: 'left',
            ranges: defaultRanges,
            alwaysShowCalendars: true,
            startDate: startDate, 
            endDate: endDate,
            locale: {
                format: "DD/MM/YYYY"
            }
        }, function(start, end, label) {
            $("input#start").val(start);
            $("input#end").val(end);
            $('form#frmDateRange').submit();
        });

        /* jQueryKnob */
        $('.knob').knob()

        /* Chart.js Charts */
        // Sales chart
        var salesChartCanvas = document.getElementById('revenue-chart-canvas').getContext('2d')
        // $('#revenue-chart').get(0).getContext('2d');

        var labelm = {{ Js::from($labelm) }};     
        var pedidosChart = {{ Js::from($pedidosChart) }}; 
        var facturacionChart = {{ Js::from($facturacionChart) }}; 

        var salesChartData = {
            labels: labelm,
            datasets: [{
                    label: 'Pedidos',
                    fill: false,
                    borderWidth: 2,
                    lineTension: 0,
                    spanGaps: true,
                    borderColor: '#efefef',
                    pointRadius: 3,
                    pointHoverRadius: 7,
                    pointColor: '#efefef',
                    pointBackgroundColor: '#efefef',
                    data: pedidosChart,
                },
            ]
        }

        var salesChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
              display: false
            },
            scales: {
              xAxes: [{
                ticks: {
                  fontColor: '#efefef'
                },
                gridLines: {
                  display: false,
                  color: '#efefef',
                  drawBorder: false
                }
              }],
              yAxes: [{
                ticks: {
                  stepSize: 200,
                  fontColor: '#efefef'
                },
                gridLines: {
                  display: true,
                  color: '#efefef',
                  drawBorder: false
                }
              }]
            }
        }

        // This will get the first returned node in the jQuery collection.
        // eslint-disable-next-line no-unused-vars
        var salesChart = new Chart(salesChartCanvas, { // lgtm[js/unused-local-variable]
            type: 'line',
            data: salesChartData,
            options: salesChartOptions
        })

        // Donut Chart
        var pieChartCanvas = $('#sales-chart-canvas').get(0).getContext('2d')
        var pieData = {
            labels: labelm,
            datasets: [{
                    label: 'Facturado',
                    fill: false,
                    borderWidth: 2,
                    lineTension: 0,
                    spanGaps: true,
                    borderColor: '#efefef',
                    pointRadius: 3,
                    pointHoverRadius: 7,
                    pointColor: '#efefef',
                    pointBackgroundColor: '#efefef',
                    data: facturacionChart,
                },
            ]
        }
        var pieOptions = {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
              display: false
            },
            scales: {
              xAxes: [{
                ticks: {
                  fontColor: '#efefef'
                },
                gridLines: {
                  display: false,
                  color: '#efefef',
                  drawBorder: false
                }
              }],
              yAxes: [{
                ticks: {
                  /* stepSize: 200, */
                  fontColor: '#efefef'
                },
                gridLines: {
                  display: true,
                  color: '#efefef',
                  drawBorder: false
                }
              }]
            }
        }
        // Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        // eslint-disable-next-line no-unused-vars
        var pieChart = new Chart(pieChartCanvas, { // lgtm[js/unused-local-variable]
            type: 'line',
            data: pieData,
            options: pieOptions
        })
      });
    </script>

    {{-- <script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('dist/js/pages/dashboard.js') }}"></script> --}}
    
@stop