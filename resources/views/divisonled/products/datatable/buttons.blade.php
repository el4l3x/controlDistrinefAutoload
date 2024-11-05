<a role="button" href="{{ route('divisonled.products.edit', $id) }}" class="btn btn-sm btn-outline-primary" title="Editar Producto.">
    <i class="fas fa-edit fa-sm"></i>
</a>

<a role="button" href="#" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#modalBorrar" data-id="{{ $id }}" data-nombre="{{ $nombre }}" title="Borrar Producto.">
    <i class="fas fa-trash fa-sm"></i>
</a>