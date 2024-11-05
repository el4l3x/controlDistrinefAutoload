<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            vertical-align: top;
            border-color: #dee2e6;
        }
        .table > tbody {
            vertical-align: inherit;
        }
        .table > thead {
            vertical-align: bottom;
        }

        th {
            text-align: inherit;
            text-align: -webkit-match-parent;
        }

        thead,
        tbody,
        tfoot,
        tr,
        td,
        th {
            border-color: inherit;
            border-style: solid;
            border-width: 0;
        }
    </style>
</head>
<body>
    <h3>Productos del Monitor de Precios</h3>
    <hr>
    <h4>Productos con un % menor al 2%</h4>
    <hr>
    <table class="table">
        <thead>
            <th>
                ID
            </th>

            <th>
                Producto
            </th>

            <th>
                Competidor
            </th>

            <th>
                Precio
            </th>

            <th>
                %
            </th>

            <th>
                Precio GFC
            </th>
        </thead>

        <tbody>            
            @foreach ($red as $item)
                <tr>
                    <td>
                        {{ $item['Id'] }}
                    </td>

                    <td>
                        {{ $item['Producto'] }}
                    </td>

                    <td>
                        {{ $item['Competidor'] }}
                    </td>

                    <td>
                        {{ $item['Precio'] }}
                    </td>

                    <td>
                        {{ $item['%'] }}
                    </td>

                    <td>
                        {{ $item['Precio GFC'] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    <hr>
    <h4>Productos con un % entre el 0 y 2%</h4>
    <hr>
    <table class="table">
        <thead>
            <th>
                ID
            </th>

            <th>
                Producto
            </th>

            <th>
                Competidor
            </th>

            <th>
                Precio
            </th>

            <th>
                %
            </th>

            <th>
                Precio GFC
            </th>
        </thead>

        <tbody>
            @foreach ($orange as $item)
                <tr>
                    <td>
                        {{ $item['Id'] }}
                    </td>

                    <td>
                        {{ $item['Producto'] }}
                    </td>

                    <td>
                        {{ $item['Competidor'] }}
                    </td>

                    <td>
                        {{ $item['Precio'] }}
                    </td>

                    <td>
                        {{ $item['%'] }}
                    </td>

                    <td>
                        {{ $item['Precio GFC'] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>