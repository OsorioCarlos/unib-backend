<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reporte Estudiantes</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
        }

        th, td {
            padding: 5px;
        }

        td, th, table {
            border: 1px solid black;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
<h1 style="text-align: center">REPORTE DE ESTUDIANTES</h1>
<table>
    <thead>
    <tr>
        <th>CEDULA</th>
        <th>NOMBRE ESTUDIANTE</th>
        <th>APELLIDO ESTUDIANTE</th>
        <th>CARRERA</th>
        <th>NIVEL</th>
        <th>ESTADO</th>
    </tr>
    </thead>
    <tbody>
    @foreach($practicasPreprofesionales as $practicaPreprofesional)
        <tr>
            <td>{{$practicaPreprofesional->student->user->identificacion}}</td>
            <td>{{$practicaPreprofesional->student->user->primer_nombre}} {{$practicaPreprofesional->student->user->segundo_nombre}}</td>
            <td>{{$practicaPreprofesional->student->user->primer_apellido}} {{$practicaPreprofesional->student->user->segundo_apellido}}</td>
            <td>{{$practicaPreprofesional->student->carreraCatalogo->nombre}}</td>
            <td>{{$practicaPreprofesional->student->nivelCatalogo->nombre}}</td>
            <td>{{$practicaPreprofesional->estadoCatalogo->nombre}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
