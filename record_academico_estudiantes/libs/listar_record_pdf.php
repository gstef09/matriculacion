<?php 
session_start();
$IdAlumno=$_GET['IdAlumno'];
require_once('conexion.php');
$cn=  Conectarse();
$listado=  mysql_query("select * from record_academico WHERE IdAlumno='$IdAlumno' ORDER BY IdSemestre,Materia",$cn);
?>

 <script type="text/javascript" language="javascript" src="js/jslistadopaises.js"></script>

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

     
                   while($reg=  mysql_fetch_array($listado))
                   {
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
