@if ($rol != 'SuperAdmin')
    <a role="button" href="{{ route('usuarios.edit', $id) }}" class="btn btn-sm btn-outline-primary" title="Editar Usuario.">
        <i class="fas fa-user-edit"></i>
    </a>

    <a role="button" href="#" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#modalBorrar" data-id="{{ $id }}" data-nombre="{{ $nombre }}" title="Borrar Usuario.">
        <i class="fas fa-trash fa-sm"></i>
    </a>

    @if ($status != 1)
        <a role="button" href="#" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#modalStatus" data-id="{{ $id }}" data-nombre="{{ $nombre }}" data-status="{{ $status }}" title="Desactivar Usuario.">
            <i class="fas fa-unlock-alt"></i>
        </a>
    @else
        <a role="button" href="#" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#modalStatus" data-id="{{ $id }}" data-nombre="{{ $nombre }}" data-status="{{ $status }}" title="Desactivar Usuario.">
            <i class="fas fa-user-lock"></i>
        </a>
    @endif
@endif