<?php

//include 'funciones.php';
require_once 'db.php';

if(!isset($_SESSION)){session_start();}

$sessionId=session_id();


$mail = $_POST['pmail'];
$idOrigenSus = $_POST['pidOrigenSus'];

//header('HTTP/1.1 500 Internal Server Error'); exit("nom:".$nombre.$mail."sus:".$suscribirse.$idEnvio.$precioEnvio.$producto.$efectivo);
$error=0;
try {
    $stmt = DB::run("INSERT INTO suscripcion (mail, campania, fechaAgregado) VALUES (?,?,now())",
    [$mail, "suscripcion idOrigenSus-".$idOrigenSus]);
        
  //$_SESSION['idVenta'] = $idVenta;
  
}catch (Exception $e){
    $error=1;
    error_log("error: ".$e);
    throw $e;
} finally {  
  if($error==1){
    header('HTTP/1.1 500 Internal Server Error'); exit("Hubo un inconveniente al intentar suscribirte. Lo estamos arreglando! Intentá mas tarde por favor.");
  }
}












//para error
//header('HTTP/1.1 500 Internal Server Error'); exit("Something went wrong when we tried to save your comment. Please try again later. Sorry for any inconvenience");




?>
