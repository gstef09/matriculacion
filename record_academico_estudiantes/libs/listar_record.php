<?php 
require_once '../../api/auth/check_auth1.php';
include_once "../../conf/conectar_msqli/conectar_li.php"; 
include_once "../../consulta_estudiantes/class/class_buscar.php";
$IdAlumno = $decoded->data->id;
$listado= $conexion->query("select * from record_academico WHERE IdAlumno='$IdAlumno' ORDER BY  IdSemestre,Materia");

$detalle_estudiante=  mysqli_query($conexion,"select Nombres,Unidad,Carrera from vista_estudiantes_asignados_carreras WHERE id_cedula='$IdAlumno'");
$count = mysqli_fetch_array($detalle_estudiante); 
$unidad=$count['Unidad'];
$Carrera= $count['Carrera'];
// $IdAlumno=$count['IdAlumno'];
$Estudiante=$count['Nombres'];
?>

 <script type="text/javascript" language="javascript" src="js/jslistadopaises.js"></script>

 <table cellpadding="0" cellspacing="0"  class="display">
               
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
                 <tbody>
                    <?php

                                   echo '<tr><th>Identificación</th><td >'.$IdAlumno.'</td></tr>';
                                   echo '<tr><th>Estudiante</th><td >'.$Estudiante.'</td></tr>';
                                   echo '<tr><th>Unidad</th><td >'.$unidad.'</td></tr>';
                                   echo '<tr><th>Carrera</th><td >'.$Carrera.'</td></tr>';
                   
                    ?>
                <tbody>
   </table>
 
     <header id="titulo">
        <h5>Detalle de Materias</h5>
    </header>
 <p></p>
           <table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_record">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Materia</th>
                        <th>Tipo Crédito</th>
                        <th>Créditos</th>
                        <th>Nivel</th>
                        <th>Nota</th>
                        <th>Periodo</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                       
                     
                    </tr>
                </tfoot>
                  <tbody>
                    <?php

     
                   while($reg=  mysqli_fetch_array($listado))
                  {           
                    if(intval($reg['id_periodoA'])>41){
                        $periodo_academico = new Consultar_PeriodoA($conexion, intval($reg['id_periodoA']) );
                        $periodo_academico_fila = $periodo_academico->consultar();
                        $reg['Periodo'] = $periodo_academico_fila['Periodo'];
                    }
                             echo '<tr>';
                                   echo '<td >'.$reg['Cod_Materia'].'</td>';
                                   echo '<td>'.$reg['Materia'].'</td>';
                                   echo '<td >'.$reg['tipo_creditos'].'</td>';
                                   echo '<td>'.$reg['creditos'].'</td>';
                                   echo '<td >'.$reg['Semestre'].'</td>';
                                   echo '<td>'.$reg['notaf'].'</td>';
                                   echo '<td >'.$reg['Periodo'].'</td>';
                                   echo '<td>'.$reg['Estado_Credito'].'</td>';
                               echo '</tr>';
                     
                        }
                    ?>
                <tbody>
            </table>
