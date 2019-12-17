<?php
include_once '../../conf/__bd.php';
require "../../vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMPTP;
use PHPMailer\PHPMailer\Exception;

function sendEmail(array $user, string $token)
{
    $mail = new PHPMailer();
    $mail = new PHPMailer;
    $mail->isSMTP();
    // change this to 0 if the site is going live
    $mail->SMTPDebug = 0;
    $mail->Debugoutput = 'html';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';


    //use SMTP authentication
    $mail->SMTPAuth = true;
    //Username to use for SMTP authentication
    $mail->Username = "usi.admin@unesum.edu.ec";
    $mail->Password = "RecA#2018";
    $mail->setFrom('sau.matriculas@unesum.edu.ec', utf8_decode('S@U UNESUM'));
    $mail->smtpConnect(
        array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
                "allow_self_signed" => true
            )
        )
    );

    $mail->addAddress($user['correo'], $user['nombres']);
    $mail->Subject = utf8_decode("Solicitud de cambio de contraseña | S@U");
  
    $message = utf8_decode("<p>Estimado(a) " . utf8_decode($user['nombres']) . " </p>
    <p>Hemos recibido una petición para restablecer la contraseña de su cuenta.</p>
	<p>Si hiciste esta petición, haz clic en el siguiente enlace, si no hiciste esta petición puedes ignorar este correo.</p>
	<p><a href='https://matriculacion.unesum.edu.ec/accounts/change_pass.php?user=" . sha1($user['id_persona']) . "&token={$token}'>abrir enlace </a></p>"); // Cambiar la ip por el dominio 
    $mail->msgHTML($message);


    return $mail->send();
}

function temporal_pass(array $user, $conn)
{
    $key = $user['id_persona'] . "$" . rand(1, 999) . "-" . date('d-m-Y H:s:i');

    $token = hash("sha256", $key);
    try{
        $query = "INSERT INTO reset_pass_requests (usuario, token, valido_hasta) values(?,?,?)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(1, $user['id_persona']);
        $stmt->bindParam(2, $token);
        $valido = date('Y-m-d H:i:s', strtotime(' + 1 day'));
        $stmt->bindParam(3, $valido);
        $stmt->execute();
        return $token;
    }catch(PDOException $ex){
        echo $ex->getMessage();
    }
    
   
  
    
}

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");




$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();



$data = json_decode(file_get_contents("php://input"));
$email = $data->email;

$table_name = 'vista_usuarios';

$query = "SELECT * FROM " . $table_name . " WHERE correo = ?  and IdTipoUsuario= 1";
$stmt = $conn->prepare($query);

$stmt->bindParam(1, $email);
$stmt->execute();
$num = $stmt->rowCount();
if ($num > 0) {
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $token = temporal_pass($user,$conn);
    $resp = sendEmail($user, $token);
    if ($resp) {
        echo json_encode(array("message" => "Las instrucciones para restablecer su contraseña fueron enviadas a este correo {$email}", "exist" => true));
    } else {
        echo json_encode(array("message" => "El correo {$email} asociado a esta cuenta es incorrecto ", "exist" => false));
    }
} else {
    echo json_encode(array("message" => "El correo que ingresó no está asociado a ninguna cuenta", "exist" => false));
}
