<?php
require_once 'fpdf.php';
require_once '../../api/auth/check_auth1.php';
include_once "../../conf/conectar_msqli/conectar_lipdf.php";
include_once "../../consulta_estudiantes/class/class_buscar.php";

// if (!empty($_GET['IdAlumno'])) {
//     $IdAlumno = $_GET['IdAlumno'];
// }

$IdAlumno = $decoded->data->id;
$oAlumno = new Consultar_Alumno($conexion, $IdAlumno);
$alumno = $oAlumno->consultar('Apellido_Paterno') . " " . $oAlumno->consultar('Apellido_Materno') . " " . $oAlumno->consultar('Nombres');
$asig_estu = $conexion->query("SELECT * FROM asignacion_estudiantes_carreras WHERE id_cedula='$IdAlumno'");
$ca = $asig_estu->fetch_assoc();
$id_carrera_verifica = $ca['id_carrera'];


class PDF extends FPDF
{
    protected $conn;
    protected $IdAlumno;
    protected $carrera;

    function __construct($conn, $IdAlumno, $carrera)
    {
        parent::__construct();
        $this->conn = $conn;
        $this->IdAlumno = $IdAlumno;
        $this->carrera =  $carrera;
    }

    var $widths;
    var $aligns;

    function SetWidths($w)
    {
        //Set the array of column widths
        $this->widths = $w;
    }

    function SetAligns($a)
    {
        //Set the array of column alignments
        $this->aligns = $a;
    }

    function Row($data, $carrera)
    {

        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 5 * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h, $carrera);
        //Draw the cells of the row
        $this->Cell(9, 8, '', 0);
        for ($i = 0; $i < count($data); $i++) {

            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            //Print the text
            $this->MultiCell($w, 5, $data[$i], 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h, $carrera)
    {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
            $this->TableHeader($carrera);
        }
    }

    function NbLines($w, $txt)
    {
        //Computes the number of lines a MultiCell of width w will take
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);

        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }
    function Header()
    {
        $this->Image('../images/logo_unesum.jpg', 18, 6, 12, 18, 'JPG');
        $this->SetFont('Arial', 'B', 18);
        $this->Cell(0, 10, "UNIVERSIDAD ESTATAL DEL SUR DE MANABI\n", 0, 0, 'C');
        $this->Ln(6);
        $this->SetFont('Arial', 'I', 10);
        $this->Cell(0, 10, utf8_decode("Creada el 7 de febrero del año 2001, según registro oficial #261"), 0, 0, 'C');
        $this->Ln(6);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, utf8_decode('RECORD ACADÉMICO'), 0, 0, 'C');
        $this->Ln(12);

        $detalle_estudiante =  $this->conn->query("SELECT IdCarrera,Estudiante,Unidad,Carrera,id_unidad from record_academico WHERE IdAlumno='" . $this->IdAlumno . "' and IdCarrera=" . $this->carrera);
        $count = mysqli_fetch_array($detalle_estudiante);
        $Estudiante = $count['Estudiante'];

        $carrera = $count['Carrera'];
        $facultad = $count['Unidad'];
        if ($this->PageNo() == 1) {
            $this->SetFont('Arial', '', 10);
            $this->Cell(17, 8, '', 0);
            $this->Cell(40, 10, 'FACULTAD', 0);
            $this->Cell(40, 10, ': ' . $facultad, 0);
            $this->Ln(4);
            $this->Cell(17, 8, '', 0);
            $this->Cell(40, 10, 'CARRERA', 0);
            $this->Cell(40, 10, ': ' . $carrera, 0);
            $this->Ln(4);
            $this->Cell(17, 8, '', 0);
            $this->Cell(40, 10, utf8_decode('IDENTIFICACIÓN'), 0);
            $this->Cell(40, 10, utf8_decode(': ' . $this->IdAlumno), 0);
            $this->Ln(4);
            $this->Cell(17, 8, '', 0);
            $this->Cell(40, 10, 'ESTUDIANTE', 0);
            $this->Cell(40, 10, ': ' . $Estudiante, 0);
            $this->Ln(4);
            $this->Cell(17, 8, '', 0);
            $this->Cell(40, 10, 'FECHA', 0);
            $this->Cell(40, 10, ': ' . date('d/m/Y'), 0);
            $this->Ln(15);
        }
    }

    function TableHeader($carrera)
    {
        $cred = "CREDITOS";
        if ($this->carrera > 18) {
            $cred = "HORAS";
        }
        $this->Cell(9, 8, '', 0);
        $this->SetFont('Arial', 'B', 9);
        $y = $this->GetY();
        $x = $this->GetX();
        $this->MultiCell(8, 8, 'No', 1, 'C', 1);
        $this->SetXY($x + 8, $y);
        $y = $this->GetY();
        $x = $this->GetX();
        $this->MultiCell(20, 4, 'COD. MATERIA', 1, 1, 'C');

        $this->SetXY($x + 20, $y);
        $y = $this->GetY();
        $x = $this->GetX();
        $this->MultiCell(28, 8, 'MATERIA', 1, 1, 'C');
        $this->SetXY($x + 28, $y);
        $y = $this->GetY();
        $x = $this->GetX();
        $this->MultiCell(20, 4, 'TIPO CREDITO', 1, 'C', 1);
        $this->SetXY($x + 20, $y);
        $y = $this->GetY();
        $x = $this->GetX();
        $this->MultiCell(20, 8, $cred, 1, 1, 'C');
        $this->SetXY($x + 20, $y);
        $y = $this->GetY();
        $x = $this->GetX();
        $this->MultiCell(18, 8, 'NIVEL', 1, 1, 'C');
        $this->SetXY($x + 18, $y);
        $y = $this->GetY();
        $x = $this->GetX();
        $this->MultiCell(15, 8, 'NOTA', 1, 1, 'C');
        $this->SetXY($x + 15, $y);
        $y = $this->GetY();
        $x = $this->GetX();
        $this->MultiCell(26, 8, 'PERIODO', 1, 1, 'C');
        $this->SetXY($x + 26, $y);
        $y = $this->GetY();
        $x = $this->GetX();
        $this->MultiCell(18, 8, 'ESTADO', 1, 1, 'C');

        $this->SetFont('Arial', '', 8);
    }
    function Footer()
    {
        $oAlumno = new Consultar_Alumno($this->conn, $this->IdAlumno);
        $alumno = $oAlumno->consultar('Apellido_Paterno') . " " . $oAlumno->consultar('Apellido_Materno') . " " . $oAlumno->consultar('Nombres');
        // Posición: a 1,5 cm del final
        $this->SetY(-25);
        // Arial italic 8

        // Número de página
        $this->Ln(3);
        $this->SetFont('Times', '', 7);
        $this->Cell(0, 10, utf8_decode('FUENTE: S@U'), 0, 0, 'L');
        $this->Ln(3);
        $this->Line(10, $this->y + 3.2, 200, $this->y + 3.2); // Ln(15,$pdf->y,200,$pdf->y);

        $this->Cell(0, 10, utf8_decode('ALUMNO: ' . $alumno), 0, 0, 'L');
        $this->Cell(2.7, 10, utf8_decode('No Página: ' . $this->PageNo() . '/{nb}'), 0, 0, 'R');
        $this->Ln(3);
        $this->Line(10, $this->y + 3.2, 200, $this->y + 3.2);
        $this->Cell(0, 10, utf8_decode('FECHA DE IMPRESIÓN: ' . date('d/m/Y')), 0, 0, 'L');
        $this->Cell(0, 10, utf8_decode('Hora: ' . date('H:i:s')), 0, 0, 'R');
        $this->Line(10, $this->y + 6.2, 200, $this->y + 6.2);
        // . $this->PageNo() . '/{nb}'
    }
}


$total_record_academico =  mysqli_query($conexion, "select SUM(notaf) as suma,COUNT(Materia) as total_materias,sum(creditos) as creditos_aprobados from record_academico WHERE IdAlumno='$IdAlumno' AND Estado_Credito='Aprobado' AND IdCarrera='$id_carrera_verifica'");
$record_ = mysqli_fetch_assoc($total_record_academico);
$total_materias = $record_['total_materias'];
$suma = $record_['suma'];
$creditos_aprobados = $record_['creditos_aprobados'];
$promedio = round($suma / $total_materias, 2);

$total_materias_apro =  mysqli_query($conexion, "select COUNT(id_materia) as total_materias_carrera,sum(id_creditos) as creditos_total from materias_carreras WHERE estado='s' and carrera='$id_carrera_verifica'");
$record_materias = mysqli_fetch_assoc($total_materias_apro);


$total_materias_carrera = $record_materias['total_materias_carrera'];
$creditos_total_x_aprobar = $record_materias['creditos_total'] - $creditos_aprobados;
$materias_x_aprobar = $total_materias_carrera - $total_materias;

$sql = "SELECT * FROM record_academico WHERE IdAlumno='$IdAlumno' and idCarrera='$id_carrera_verifica' ORDER BY IdSemestre,Materia";
$resEmp = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
$totEmp = mysqli_num_rows($resEmp);

$ixx = 0;
$suma_creditos = 0;
$creditos = 0;

$pdf = new PDF($conexion, $IdAlumno, $id_carrera_verifica);
$pdf->SetFillColor(177, 178, 181);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->TableHeader($id_carrera_verifica);




while ($datatmp = mysqli_fetch_assoc($resEmp)) {
    $ixx = $ixx + 1;



    $creditos = $datatmp['creditos'];
    $suma_creditos = $suma_creditos + $creditos;
    if(intval($datatmp['id_periodoA'])>41){
        $periodo_academico = new Consultar_PeriodoA($conexion, intval($datatmp['id_periodoA']) );
        $periodo_academico_fila = $periodo_academico->consultar();
        $datatmp['Periodo'] = $periodo_academico_fila['Periodo'];
      }
    $row = array($ixx,  $datatmp['Cod_Materia'], $datatmp['Materia'], $datatmp['tipo_creditos'],  $datatmp['creditos'],  $datatmp['Semestre'], $datatmp['notaf'],  $datatmp['Periodo'],  $datatmp['Estado_Credito']);


    $pdf->SetWidths(array(8, 20, 28, 20, 20, 18, 15, 26, 18));

    $pdf->Row($row, $id_carrera_verifica);
}

$pdf->Ln(10);

$pdf->Cell(18, 8, '');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(60, 10, 'TOTAL MATERIAS MALLA', 0);
$pdf->Cell(40, 10, ': ' . $total_materias_carrera, 0);
$pdf->Ln(5);
$pdf->Cell(18, 8, '');
$pdf->Cell(60, 10, 'MATERIAS APROBADAS', 0);
$pdf->Cell(40, 10, ': ' . $total_materias, 0);
$pdf->Ln(5);
$pdf->Cell(18, 8, '');
$pdf->Cell(60, 10, 'MATERIAS X APROBAR', 0);
$pdf->Cell(40, 10, ': ' . $materias_x_aprobar, 0);
$pdf->Ln(5);
$pdf->Cell(18, 8, '');
$pdf->Cell(60, 10, 'SUMA', 0);
$pdf->Cell(40, 10, ': ' . $suma, 0);
$pdf->Ln(5);
$pdf->Cell(18, 8, '');
$pdf->Cell(60, 10, 'PROMEDIO', 0);
$pdf->Cell(40, 10, ': ' . $promedio, 0);
$pdf->Ln(5);
$pdf->Cell(18, 8, '');
if ($id_carrera_verifica >= 18) {
    $pdf->Cell(60, 10, "TOTAL HORAS APROBADOS", 0);
    $pdf->Cell(40, 10, ": " . $creditos_aprobados, 0);
    $pdf->Ln(5);
    $pdf->Cell(18, 8, '');
    $pdf->Cell(60, 10, "TOTAL HORAS POR APROBAR", 0);
    $pdf->Cell(60, 10, ": " . $creditos_total_x_aprobar, 0);
} else {
    $pdf->Cell(60, 10, "TOTAL CREDITOS APROBADOS", 0);
    $pdf->Cell(40, 10, ": " . $creditos_aprobados, 0);
    $pdf->Ln(5);
    $pdf->Cell(18, 8, '');
    $pdf->Cell(60, 10, "TOTAL CREDITOS POR APROBAR", 0);
    $pdf->Cell(60, 10, ": " . $creditos_total_x_aprobar, 0);
}
$pdf->Ln(20);
$pdf->Cell(60);
$pdf->Cell(120, 10, "SECRETARIA GENERAL (E) UNESUM", 0, 0, 'L');
$pdf->Output();
