<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Tipo</th>
        <th>Producto</th>
        <th>Precio Compra</th>
        <th>Precio Venta</th>
        <th>Dto Compra</th>
        <th>Margen</th>
        <th>Precio+Combinacion</th>
        <th>Magservice</th>
        <th>Abad</th>
        <th>Calefon</th>
        <th>Ferreteria Ubetense</th>
        <th>Electromercantil</th>
        <th>Calygas</th>
    </tr>
    </thead>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td>{{ $product['id_product'] }}</td>
            <td>{{ $product['tipo'] }}</td>
            <td>{{ $product['nombre'] }}</td>
            <td>{{ $product['wholesale_price'] }}</td>
            <td>{{ $product['pventa'] }}</td>
            <td {!! ($product['NewDtoCompra'] > 0) ? "" : "bgcolor='#ef4444'" !!}>{{ $product['NewDtoCompra'] }}</td>
            {{-- <td>{{ $product['NewDtoCompra'] }}</td> --}}
            <td>{{ round($product['margen']*100, 2) }}</td>
            <td>{{ round($product['precio_combinacion'], 2) }}</td>
            <td {!! ($product['bestCompra'] == $product['pcompra_mags']) ? "bgcolor='#86efac'" : "" !!}>{{ $product['pcompra_mags'] }}</td>
            <td {!! ($product['bestCompra'] == $product['pcompra_abad']) ? "bgcolor='#86efac'" : "" !!}>{{ $product['pcompra_abad'] }}</td>
            <td {!! ($product['bestCompra'] == $product['pcompra_cale']) ? "bgcolor='#86efac'" : "" !!}>{{ $product['pcompra_cale'] }}</td>
            <td {!! ($product['bestCompra'] == $product['pcompra_ferre']) ? "bgcolor='#86efac'" : "" !!}>{{ $product['pcompra_ferre'] }}</td>
            <td {!! ($product['bestCompra'] == $product['pcompra_elect']) ? "bgcolor='#86efac'" : "" !!}>{{ $product['pcompra_elect'] }}</td>
            <td {!! ($product['bestCompra'] == $product['pcompra_caly']) ? "bgcolor='#86efac'" : "" !!}>{{ $product['pcompra_caly'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>