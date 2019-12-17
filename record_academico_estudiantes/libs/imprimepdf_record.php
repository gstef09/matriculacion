<?php

require_once('class.ezpdf.php');
include_once "../../conf/conectar_msqli/conectar_lipdf.php";
include_once "../../consulta_estudiantes/class/class_buscar.php";
// include_once('../../../funciones_class/class_buscar_li.php');
if(!empty($_GET['IdAlumno']) )
{$IdAlumno=$_GET['IdAlumno']; }

// $nom_carrera=$_SESSION['Carrera']; 
// $nombre_estudiante=$_SESSION['alumno_nom'];
// $id_carrera_es=$_SESSION['carrera_estudiante'];
// $facultad=$_SESSION['facultades'];  

$oAlumno=new Consultar_Alumno($conexion, $IdAlumno);
$alumno = $oAlumno->consultar('Apellido_Paterno')." ".$oAlumno->consultar('Apellido_Materno')." ". $oAlumno->consultar('Nombres');
$asig_estu = $conexion->query("SELECT * FROM asignacion_estudiantes_carreras WHERE id_cedula='$IdAlumno'");
$ca = $asig_estu->fetch_assoc();
$id_carrera_verifica = $ca['id_carrera']; 
      
$pdf =new Cezpdf('a4','portrait');

$pdf->ezSetCmMargins(0.5,1,1.8,1);

$universidad="UNIVERSIDAD ESTATAL DEL SUR DE MANABÍ\n";			
$creacion="Creada el 7 de febrero del año 2001, según registro oficial #261";	
$acta="RECORD ACADÉMICO";
$pagi="No Página:";


$all = $pdf->openObject();
$pdf->saveState();

$pdf->ezImage('../../images/logo_unesum.jpg',1,40,'none','left');
$pdf->setStrokeColor(0,0,0,1);
$pdf->line($pdf->ez['leftMargin'], $pdf->ez['bottomMargin']+10, $pdf->ez['pageWidth']-$pdf->ez['rightMargin'], $pdf->ez['bottomMargin']+10);//the bottom line
$pdf->line($pdf->ez['leftMargin'], $pdf->ez['bottomMargin']+20, $pdf->ez['pageWidth']-$pdf->ez['rightMargin'], $pdf->ez['bottomMargin']+20);//the bottom line
$pdf->line($pdf->ez['leftMargin'], $pdf->ez['bottomMargin']+30, $pdf->ez['pageWidth']-$pdf->ez['rightMargin'], $pdf->ez['bottomMargin']+30);//the bottom line
$pdf->addText(100,790,18,"<b>".utf8_decode($universidad)."</b>\n"); 
$pdf->addText(170,773,10,"<b>".utf8_decode($creacion)."</b>\n");
$pdf->addText(210,748,14,"<b>".utf8_decode($acta)."</b>");
$pdf->addText(480,50,7,"<b>".  utf8_decode($pagi)."</b>\n");
 
$pdf->addText(50,60,7,"<b>FUENTE: S@U  </b>\n"); 
$pdf->addText(50,50,7,"<b>AlUMNO: ".$alumno." </b>\n");
$pdf->addText(50,40,7,"<b>FECHA DE IMPRESION: ".date("d/m/Y")." </b>\n");
$pdf->addText(480,40,7,"<b>HORA: ".date("H:i:s")." </b>\n");
$pdf->ezStartPageNumbers($pdf->ez['pageWidth']-($pdf->ez['rightMargin'] +25), $pdf->ez['bottomMargin']+22,7, 'PAGINA', '{PAGENUM} de {TOTALPAGENUM}',1);
$pdf->addText(50,30,10,'');//bottom text
$pdf->restoreState();
$pdf->closeObject();
$pdf->addObject($all,'all');
$pdf->ezSetCmMargins(4,3,3,2);
// $pdf->addInfo($datacreator);
if(!empty($_GET['IdAlumno']) )
{
$IdAlumno=$_GET['IdAlumno'];

$detalle_estudiante=  mysqli_query($conexion,"select IdCarrera,Estudiante,Unidad,Carrera,id_unidad from record_academico WHERE IdAlumno='$IdAlumno' and IdCarrera='$id_carrera_verifica'");
$count = mysqli_fetch_array($detalle_estudiante); 
$Estudiante=$count['Estudiante'];
$IdCarrera=$count['IdCarrera'];
$IdUnidad=$count['id_unidad'];
$carrera = $count['Carrera'];
$facultad = $count['Unidad'];

$total_record_academico=  mysqli_query($conexion,"select SUM(notaf) as suma,COUNT(Materia) as total_materias,sum(creditos) as creditos_aprobados from record_academico WHERE IdAlumno='$IdAlumno' AND Estado_Credito='Aprobado' AND IdCarrera='$id_carrera_verifica'");
$record_ = mysqli_fetch_assoc($total_record_academico); 
$total_materias=$record_['total_materias'];
$suma=$record_['suma'];
$creditos_aprobados=$record_['creditos_aprobados'];
$promedio=round($suma/$total_materias,2);

//SSCAR CUANTAS MATERIAS TIENE LA CARRERA Y CUANTAS HA APROBADO

$total_materias_apro=  mysqli_query($conexion,"select COUNT(id_materia) as total_materias_carrera,sum(id_creditos) as creditos_total from materias_carreras WHERE estado='s' and carrera='$IdCarrera' AND id_unidadacademica='$IdUnidad'");
$record_materias = mysqli_fetch_assoc($total_materias_apro); 


$total_materias_carrera=$record_materias['total_materias_carrera'];
$creditos_total_x_aprobar=$record_materias['creditos_total'] - $creditos_aprobados;
$materias_x_aprobar=$total_materias_carrera-$total_materias;
//************************
//verifica carrera para que salga solo de la carrera
// $carre=mysql_query("SELECT * FROM asignacion_estudiantes_carreras WHERE id_cedula='$IdAlumno' ");
// $verificacarrera=  mysql_fetch_array($carre);
// $vericarre=$verificacarrera['id_carrera'];
//verifica carrera para que salga solo de la carrera termina

$sql="SELECT * FROM record_academico WHERE IdAlumno='$IdAlumno' and idCarrera='$IdCarrera' ORDER BY IdSemestre,Materia";
$resEmp= mysqli_query($conexion,$sql) or die(mysqli_error($conexion));
$totEmp = mysqli_num_rows($resEmp);

$ixx = 0;
$suma_creditos=0;
$creditos=0;

while($datatmp = mysqli_fetch_assoc($resEmp))
 { 
	$ixx = $ixx+1;
	$creditos=$datatmp['creditos'];
	$suma_creditos = $suma_creditos+$creditos;
	$data[] = array_merge($datatmp, array('num'=>$ixx));					
}					
				
 if($IdCarrera>=18 and $IdCarrera < 28){
  $title = array(
    'num'=>'<b>No</b>',
    'Cod_Materia'=>'<b>COD. MATERIA</b>',
    'Materia'=>'<b>MATERIA</b>',
    utf8_encode('tipo_creditos')=>'<b>TIPO CREDITO</b>',
    'creditos'=>'<b>HORAS</b>',
    'Semestre'=>'<b>NIVEL</b>',
    'notaf'=>'<b>NOTA</b>',
    'Periodo'=>'<b>PERIODO</b>',
    'Estado_Credito'=>'<b>ESTADO</b>'
        
    );
 }else{
  $title = array(
    'num'=>'<b>No</b>',
    'Cod_Materia'=>'<b>COD. MATERIA</b>',
    'Materia'=>'<b>MATERIA</b>',
    'tipo_creditos'=>'<b>TIPO CREDITO</b>',
    'creditos'=>'<b>CREDITOS</b>',
    'Semestre'=>'<b>NIVEL</b>',
    'notaf'=>'<b>NOTA</b>',
    'Periodo'=>'<b>PERIODO</b>',
    'Estado_Credito'=>'<b>ESTADO</b>'
        
    );
 }
       
	
     $options = array(
				'shadeCol'=>array(0.9,0.9,0.9),
				'showHeadings'=>0,
				'showLines'=> 1,
				'shaded' => 1,
				'xOrientation'=>'centre',
				'shadeCol2' => array(1.2,0.8,0.2),
				'textCol' => array(1,0,0)
				
			);


$identi="IDENTIFICACIÓN";		
$uni="FACULTAD";
$perioA="PERIODO ACADÉMICO";


$pdf->ezText("\n", 1);
$pdf->ezText("<b>".utf8_decode($uni)."                           :</b> ".utf8_decode($facultad), 10);
$pdf->ezText("<b>CARRERA                            :</b> ".utf8_decode($carrera), 10);
$pdf->ezText("<b>".utf8_decode($identi)."                 :</b> ".utf8_ENcode($IdAlumno), 10);
$pdf->ezText("<b>ESTUDIANTE                       :</b> ".$alumno, 10);
 $pdf->ezText("<b>FECHA                                  :</b> ".date("d/m/Y")."\n\n\n", 10); 
//$pdf->ezText("<b>FECHA                       :</b> ".$date("d/m/Y"), 10);
$pdf->ezText("\n", 1);

  
   $pdf->setLineStyle(1);
   $pdf->ezTable($data,$title,$options);
   $pdf->ezText("\n", 5);
   $pdf->setLineStyle(1);
   $pdf->ezText("<b>TOTAL MATERIAS MALLA                        :</b> ".$total_materias_carrera, 10);
   $pdf->ezText("<b>MATERIAS APROBADAS                          :</b> ".$total_materias, 10);
   $pdf->ezText("<b>MATERIAS X APROBAR                           :</b> ".$materias_x_aprobar, 10);
   $pdf->ezText("<b>SUMA                                                         :</b> ".$suma, 10);
   $pdf->ezText("<b>PROMEDIO                                                :</b> ".$promedio, 10);
   if ($id_carrera_verifica>=18) {
   $pdf->ezText("<b>TOTAL HORAS APROBADOS                   :</b> ".$creditos_aprobados, 10);
   $pdf->ezText("<b>TOTAL HORAS POR APROBAR                 :</b> ".$creditos_total_x_aprobar, 10);

   }
   else {
   $pdf->ezText("<b>TOTAL CREDITOS APROBADOS                   :</b> ".$creditos_aprobados, 10);
   $pdf->ezText("<b>TOTAL CREDITOS X APROBAR                   :</b> ".$creditos_total_x_aprobar, 10);
 }
   $pdf->ezText("\n\n\n\n\n", 8);
  
   $pdf->ezText("<b>                                              SECRETARIA GENERAL (E) UNESUM  </b>  ", 9);
   $pdf->ezText("\n", 3);
  // $pdf->ezText("<b>Fecha:</b> ".date("d/m/Y")."\n\n\n", 7); 
        
    ob_end_clean(); 
    $pdf->ezStream();
    $pdf->ezPRVTaddPageNumbers ()  ;
  
}
?>
 