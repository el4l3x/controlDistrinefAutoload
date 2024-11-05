@if (!empty($productos))
    <a role="button" href="#" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#modalProductos" data-id="{{ $id }}" data-nombres="{{ $productos }}" title="Ver todos los pedidos.">
        Ver Productos
    </a>
@else
    Sin Productos
@endif