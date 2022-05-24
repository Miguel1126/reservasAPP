<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Prestamos</title>
</head>
<body>
    <header class="header">
    <div class="header-content">
        <div><img class="itcha" src="#"></div>
        <h3 class="title">ITCHA</h3>
        <div><img class="clear"></div>
    </div>
    </header>
    <h1 class="report-title">Reporte de Prestamos</h1>
    <table class="table" border="1px">
        <thead>
            <th>correlativo</th>
            <th>fecha_reserva</th>
            <th>fecha_prestamo</th>
            <th>hora_inicio</th>
            <th>hora_fin</th>
            <th>estado</th>
            <th>name</th>
            @foreach($prestamos as $prestamo)
            <tr>
                <td><?=$prestamo->correlativo?></td>
                <td><?=$prestamo->fecha_reserva?></td>
                <td><?=$prestamo->fecha_prestamo?></td>
                <td><?=$prestamo->hora_inicio?></td>
                <td><?=$prestamo->hora_fin?></td>
                <td><?=$prestamo->estado?></td>
                <td><?=$prestamo->name?></td>
            </tr>
            @endforeach
        </thead>
    </table>
</body>
</html>