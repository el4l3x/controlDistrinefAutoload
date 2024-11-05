@if ($isContacted != 0)
<a role="button" href="#" class="btn btn-sm btn-outline-success" data-toggle="modal" data-target="#modalComments" data-id="{{ $idCart }}" data-comment="{{ $comment }}" data-date='{{ $date }}' title="Ver Comentario.">
        <i class="fas fa-check-circle"></i>
    </a>
@else
    <a role="button" href="#" class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#modalNewComment" data-id="{{ $idCart }}" title="Agregar Comentario.">
        <i class="fas fa-user-clock"></i>
    </a>
@endif