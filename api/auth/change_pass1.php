<?php
include_once 'check_auth1.php';
include_once '../../conf/__bd.php';

$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
$request = json_decode(file_get_contents("php://input"));


if ($request->data->new_pass === $request->data->pass_confirm) {
    $st = $conn->prepare("SELECT clave FROM  _bp_usuarios  where usuario = ?");
    $st->bindParam(1, $decoded->data->id);
    $st->execute();
    $row = $st->fetch(PDO::FETCH_ASSOC);
    if ($row['clave'] !== sha1($request->data->new_pass)) {
        $stmt1 = $conn->prepare("UPDATE _bp_usuarios set clave = SHA1(?) ,  pass_updated  = NOW() where usuario = ?");
        $stmt1->bindParam(1, $request->data->new_pass);
        $stmt1->bindParam(2, $decoded->data->id);
        $stmt1->execute();
        $updated = $stmt1->rowCount();
        if ($updated > 0) {
        
            echo json_encode(array(
                'message' => 'El cambio de contarseña de su cuenta se realizó exito. <a href="logout.php">Iniciar Sesión</>',
                'valid' => true
            ));
        } 
    } else{
        echo json_encode(array(
            'message' => 'La nueva contraseña no puede ser una contreseña anterior',
            'valid' => false
        ));
    }

    
} else {
    echo json_encode(array(
        'message' => 'Las contraseñas no counciden',
        'valid' => false
    ));
}
