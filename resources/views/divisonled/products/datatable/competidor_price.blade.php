@if (isset($competitor->products()->find($product->id)->pivot->precio))
    @if ($competitor->products()->find($product->id)->pivot->precio != 0)
        @php
            $gfc_price = $gfcData->products()->find($product->id)->pivot->precio;
            $product_price = $competitor->products()->find($product->id)->pivot->precio;
            if ($gfc_price != 0 && $product_price != 0) {
                $percent = number_format((((($gfc_price - $product_price)/$gfc_price))*100)*-1, 2);
            } else {
                $percent = NULL;
            }
        @endphp
        <div class="row">
            <div class="col-12 text-center">
                {{ number_format($product_price, 2, ",", ".") }} €
            </div>
        </div> 
        @if ($competitor->id != env('DLED_SCRAP_ID') && $percent != NULL)
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
        @if ($competitor->id != env('DLED_SCRAP_ID'))
            <i class="fas fa-exclamation-triangle" style="color: red" title="URL Rota"></i>
        @endif
    @endif
@endif