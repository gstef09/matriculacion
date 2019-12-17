<?php 
include_once '../conf/__bd.php';
require_once '../api/auth/check_auth.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cambio de Contraseña</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        .frm-box {
            width: 320px;
            height: auto;
            padding: 20px;
            margin: 10% auto;
            border-radius: 10px;
            border: 2px solid #28a745;
            background: white;
        }

        .frm-box-img {
            width: 120px;
            height: auto;
            margin-bottom: 10px;

        }

        .mt {
            margin-top: 20px;
        }
        .mt-100 {
            margin-top: 100px;
        }
        body {
            background: whitesmoke;
        }
        .note{
            font-size:0.7em;
            padding: 5px;
            background: #ecf9f2;
        }
        .footer{
        font-size: 0.8em;
        text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">

            <div class="col-md-12 mt-100">
                <div id="msg">

                </div>
            </div>

            <div class="frm-box">
                <div class="text-center">
                    <img class="frm-box-img" src="../assets/images/escudo_unesum.png" alt="">
                </div>

                <form >
                    <div class="form-row">
                        <div class="col-md-12">
                            <h4 class="text-center">Cambiar Contraseña</h4>
                            <p class="text-success text-justify note">Las contraseña debe contener de 8-16 caracteres , combinar letras mayúsculas, minúsculas , números y símbolos</p>
                        </div>
                      
                        <div class="col-md-12">
                            <label> Ingrese su nueva contarseña</label>
                            <input type="password" class="form-control" id="__pass" placeholder="Ingrese nueva contarseña" autocomplete="off" required>

                        </div>

                        <div class="col-md-12">
                            <label> Ingrese nuevamente la contraseña</label>
                            <input type="password" class="form-control" id="__pass_confirm" placeholder="Confirme nueva contarseña" autocomplete="off">

                        </div>

                        <div class="col-md-12 mt">
                            <button type="button" class="btn btn-outline-success btn-sm float-right" onclick="sendChanges()"> Aceptar</button>

                        </div>
                    </div>
                </form>
                <div class="col-md-12 mt">
                    <p class="mt text-info footer">
                        Copyright © <?php echo date('Y'); ?> Unidad de Sistemas Informáticos
                    </p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>

</html>