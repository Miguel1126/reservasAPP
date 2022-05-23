<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Equipos</title>
</head>
<body>
    <header class="header">
    <div class="header-content">
        <div><img class="#" src="#"></div>
        <h3 class="title">ITCHA</h3>
        <div><img class="clear"></div>
    </div>
    </header>
    <h1 class="report-title">Reporte de Equipos</h1>
    <table class="table" border="1px">
        <thead>
            <th>Codigo</th>
            <th>Nombre</th>
            <th>Descripcion</th>
            <th>Categoria</th>
            <th>Marca</th>
            <th>Estado</th>
            @foreach($equipos as $equipo)
            <tr>
                <td><?=$equipo->codigo?></td>
                <td><?=$equipo->nombre?></td>
                <td><?=$equipo->descripcion?></td>
                <td><?=$equipo->categoria?></td>
                <td><?=$equipo->marca?></td>
                <td><?=$equipo->estado?></td>
            </tr>
            @endforeach
        </thead>
    </table>
</body>
</html>