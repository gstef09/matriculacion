<?php
// session_start();

// include_once "conectar_msqli/conectar_li.php";
// include_once "modulos/funciones.php";

$fecha = date("d-m-Y");
$hora = date("H:i:s");
function get_real_ip()
{
    $ip = "";

    if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    } elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    } elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
        $ip = $_SERVER["HTTP_X_FORWARDED"];
    } elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
        $ip = $_SERVER["HTTP_FORWARDED_FOR"];
    } elseif (isset($_SERVER["HTTP_FORWARDED"])) {
        $ip = $_SERVER["HTTP_FORWARDED"];
    } elseif (isset($_SERVER["REMOTE_ADDR"])) {
        $ip = $_SERVER["REMOTE_ADDR"];
    }

    //return $_SERVER["REMOTE_ADDR"];
    return $ip;
}
$dir_ip = get_real_ip();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>SISTEMA ACADEMICO S@U</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema Académico de Matriculas estudiantes Unesum">
    <meta name="author">
    <meta name='keywords' content='unesum, matriculas, matricula, estudiantes, sistemas, jefatura, matriculas.unesum.edu.ec, informaticos, UNESUM, academico, Académico, calificaciones, historial, materias'>
    <!-- Le styles >
    <link href="css/bootstrap.css" rel="stylesheet"-->


    <!-- Font Awesome CSS -->
    <link href="../assets/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../assets/css/animate.css" rel="stylesheet">

    <!-- Custom CSS >
        <link href="css/style.css" rel="stylesheet"-->

    <!-- Custom Fonts -->
    <link href="../assets/css/estilos.css" rel="stylesheet">
    <link href="../assets/css/normalize.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">



    <script type="text/javascript" src="../assets/js/alertify.js"></script>
    <link rel="stylesheet" href="../assets/css/alertify.core.css" />
    <link rel="stylesheet" href="../assets/css/alertify.default.css" />



    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="../assets/images/logo_unesum_ico.png">
    <style>
    .mt{
        margin-bottom: 10px;
    }
    .mt-50{
        margin-top: 50px;
    }
    </style>
</head>



<body oncontextmenu="return false;">



    <div class="col-ant">

    </div>

    <div class="fluid" id="div3">

    </div>

    <div class="row">



        <div class="col-md-8 col-lg-8">

            <!-- Start Carousel Section -->
            <div class="home-slider">
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" style="padding-bottom: 30px;">

                    <div class="item mt-50">
                        

                        <H3 class="text-center item mt-50">Sistema Académico UNESUM</H3>
                        <div class="text-center">
                            <img src="../assets/images/escudo_unesum.png" alt="120" width="180" />
                        </div>
                        
                        <H5 class="text-center mt-50">UNIVERSIDAD ESTATAL DEL SUR DE MANABÍ</H5>

                       
                    </div>
                </div>
            </div>
        </div>
        <div style="width:300px; height:auto; margin:auto">



            <form name="form1" method="post" action="" class="form-signin" id="form1" style="padding:20px;">
                <div class="form-row">
                    <div class="col-md-12">
                        <h4 class="text-center">Ingreso al Sistema: S@U</h4>

                    </div>

                    <div class="col-md-12">
                        <input type="text" name="usu" class="form-control" id="__user" placeholder="Usuario" autocomplete="off" required="">
                        <input type="password" name="con" class="form-control" id="__pass" placeholder="Contraseña" autocomplete="off" required="">
                    </div>
                    <div class="col-md-12">
                        <div id="msg">

                        </div>


                    </div>
                    <div class="col-md-12">
                        <button id="lgn_btn" type="button" class="mt" onclick="login()"><img src="../assets/images/boton-ingresar.png" alt="ingresar" height="30" width="150"></button>
                    </div>
                    <div class="col-md-12">
                        <a class="btn btn-sm btn-info"href="pass_reset.php">Recuperar contraseña </a>
                    </div>

                    <!-- <ul>

                    <li>
                        <a href="http://unesum.edu.ec/programacion-de-calendario-academico-nov-2016-abril-2017/" target="_blank">
                            <small>Requisitos para Matriculas</small>
                        </a>
                    </li>

                    <li>
                        <a href="../img/MANUAL DE USUARIO DOCENTES.pdf" target="_blank">
                            <small> Manual de Usuarios Docentes</small>
                        </a>
                    </li>

                    <li>
                        <a href="../img/MANUAL DE USUARIO ALUMNO.pdf" target="_blank">
                            <small> de Usuarios Estudiantes</small>
                        </a>
                    </li>
                </ul> -->
                    <div class="col-md-12">
                        <small>Copyright © <?php echo date('Y'); ?> Unidad de Sistemas Informáticos</small>
                    </div>
                </div>

                <!-- <div align="right"><a href="../recuperar_contra/index.php" class="myButton">Recuperar Contraseña&nbsp;➜</a></div> -->
            </form>


        </div>
        <div class="col-md-4 col-lg-2 offset-md-2 offset-lg-1">


        </div>

    </div>
    <div class="fluid" id="div3">

    </div>








    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../assets/js/axios.js"></script>
    <script src="../assets/js/main.js"></script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>




</body>

</html>