<?php
if ( isset($_COOKIE['jwt'])) {
    setcookie('jwt','',time()-10,'/');
}
header('location:/');