<?php
require_once '../conf/conectar_msqli/conectar_li.php';
$periodo = $conexion->prepare("SELECT * FROM periodo_academico WHERE estado_c='A'");
$periodo->execute();
$periodo_academico = $periodo->get_result()->fetch_assoc();
$total = $conexion->prepare("SELECT count(distinct(id)) as Total from asignar_estudiantes where IdPeriodo=?");
$total->bind_param("i", $periodo_academico['Id']);
$total->execute();
$t = $total->get_result()->fetch_assoc();

$total_M = $conexion->prepare("SELECT count(distinct(id)) as Total from asignar_estudiantes where IdPeriodo=? and estado_m='M'");
$total_M->bind_param('i', $periodo_academico['Id']);
$total_M->execute();
$t_m = $total_M->get_result()->fetch_assoc();
$total_x_carrera = $conexion->query("SELECT  carrera.nombre as Carrera  , count(distinct(asignar_estudiantes.id)) as 'matriculados' 
FROM asignar_estudiantes  inner join carrera on asignar_estudiantes.carrera = carrera.id where IdPeriodo=".$periodo_academico['Id']."
group by asignar_estudiantes.carrera order by unidad"); 
$t_c = $total_x_carrera->fetch_all(MYSQLI_ASSOC);

$total_x_carrera1 = $conexion->query("SELECT  carrera.nombre as Carrera  , count(distinct(asignar_estudiantes.id)) as 'legalizados' 
FROM asignar_estudiantes  inner join carrera on asignar_estudiantes.carrera = carrera.id where IdPeriodo=".$periodo_academico['Id']." and estado_m='M'
group by asignar_estudiantes.carrera order by unidad"); 
$t_c1 = $total_x_carrera1->fetch_all(MYSQLI_ASSOC);
$conexion->close();
$data = [
    "periodo_academico" => $periodo_academico['Periodo'],
    "total_registrados" => $t['Total'],

    "total_legalizados" => $t_m['Total'],
    "registrados_x_carrera" => $t_c,
    "legalizados_x_carrera" => $t_c1

];
echo json_encode($data);
?>
