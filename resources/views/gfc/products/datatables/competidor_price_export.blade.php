@if (isset($competitor->products()->find($product->id)->pivot->precio) && $competitor->products()->find($product->id)->pivot->precio > 0)
    @php
        $product_price = $competitor->products()->find($product->id)->pivot->precio;
    @endphp
    {{ number_format($product_price, 2, ",", ".") }} €
@else
    @if (isset($competitor->products()->find($product->id)->pivot->precio) && $competitor->products()->find($product->id)->pivot->precio == 0)
        Falta Scrap
    @endif
@endif