@if (isset($competitor->products()->find($product->id)->pivot->precio) && $competitor->products()->find($product->id)->pivot->precio > 0)
    @php
        $gfc_price = $gfcData->products()->find($product->id)->pivot->precio;
        $product_price = $competitor->products()->find($product->id)->pivot->precio;
        if ($gfc_price != 0 && $product_price != 0) {
            $percent = number_format((((($gfc_price - $product_price)/$gfc_price))*100)*-1, 2);
        } else {
            $percent = NULL;
        }
        
    @endphp
    @if ($competitor->id != env('GFC_SCRAP_ID') && $percent != NULL)
        <span @class([
            'badge',
            'badge-danger' => $percent < -2,
            'bg-orange' => $percent > -2 && $percent < 0,
            'badge-warning' => $percent < 2 && $percent > 0,
            'badge-success' => $percent > 2 && $percent < 20,
            'badge-primary' => $percent > 20,
        ])>
            {{ $percent }}%
        </span>
    @endif
@else
    @if (isset($competitor->products()->find($product->id)->pivot->precio) && $competitor->products()->find($product->id)->pivot->precio == 0)
        Falta Scrap
    @endif
@endif