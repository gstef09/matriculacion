<?php 
require_once '../api/auth/check_auth.php';
$IdAlumno = $decoded->data->id;

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Listado Record de Estudiantes</title>
        <meta charset="UTF-8">
        <!--    ESTILO GENERAL   -->
        <link type="text/css" href="css/style.css" rel="stylesheet" />
        <!--    ESTILO GENERAL    -->
        <!--    JQUERY   -->
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/myjava.js"></script>
        <script type="text/javascript" language="javascript" src="js/funciones.js"></script>
        <!--    JQUERY    -->
        <!--    FORMATO DE TABLAS    -->
        <link type="text/css" href="css/demo_table.css" rel="stylesheet" />
		<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="bootstrap/css/bootstrap-theme.css" rel="stylesheet">
		<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
		
        <script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
        <!--    FORMATO DE TABLAS    -->

    </head>
    <body>
    <header id="titulo">

        <h4>Historial Académico de Estudiantes</h4>
    </header>

    <article id="contenido"></article>
    <div style="text-align: center;">

        <a href="libs/record_pdf.php" class="btn btn-danger" target="_blank">Imprimir Historial Académico</a>

   </div>
</body>
</html>