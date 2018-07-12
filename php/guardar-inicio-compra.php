<?php

//include 'funciones.php';
require_once 'db.php';
require_once "EstadosPagos.php";

if(!isset($_SESSION)){session_start();}

$sessionId=session_id();



$nombre = $_POST['pnombre'];
$mail = $_POST['pmail'];
$suscribirse = $_POST['psuscribirse']=="true" ? 1 : 0;
$idEnvio = $_POST['pidEnvio'];
$precioEnvio = $_POST['pprecioEnvio'];
$producto = $_POST['pproducto'];
$comentario = $_POST['pcomentario'];
$efectivo = $_POST['pefectivo']=="true" ? 1 : 0;
$precioProducto=null;

switch ($producto) {
    case "cs1-kit":
				$precioProducto=899.00;
        break;
    case "cs1-pedal":
        //completar
        break;
    case "ff-kit":
        //completar
        break;
}



//header('HTTP/1.1 500 Internal Server Error'); exit("nom:".$nombre.$mail."sus:".$suscribirse.$idEnvio.$precioEnvio.$producto.$efectivo);
$error=0;
try {
  $stmt = DB::run("INSERT INTO venta (producto, precioProducto, nombre, mail, sessionId, idEnvio, precioEnvio, efectivo, suscribirse, fechaYhora ) VALUES (?,?,?,?,?,?,?,?,?,now())",
  [$producto, $precioProducto, $nombre, $mail, $sessionId,$idEnvio, $precioEnvio, $efectivo, $suscribirse]);
  $idVenta = DB::lastInsertId();

  $_SESSION['idVenta'] = $idVenta;

  if($efectivo==1){
    $stmt = DB::run("INSERT INTO venta_estado (id_venta, id_estado, comentario, fechaYhora) VALUES (?,?,?,now())",
    [$idVenta, EstadosPagos::checkoutEfectivo, $comentario]);
  } else {
    $stmt = DB::run("INSERT INTO venta_estado (id_venta, id_estado, comentario, fechaYhora) VALUES (?,?,?,now())",
    [$idVenta, EstadosPagos::checkoutMp, $comentario]);
  }
}catch (Exception $e){
    $error=1;
    throw $e;
} finally {
  if( $suscribirse == 1){
    $stmt = DB::run("INSERT INTO suscripcion (nombre, mail, campania, fechaAgregado) VALUES (?,?,?,now())",
    [$nombre, $mail, "compra ".$producto]);
  }
  if($error==1){
    header('HTTP/1.1 500 Internal Server Error'); exit("Hubo un inconveniente de conexión al intentar realizar la compra. Nada que no se pueda arreglar, comunicate con nosotros.");
  }
}












//para error
//header('HTTP/1.1 500 Internal Server Error'); exit("Something went wrong when we tried to save your comment. Please try again later. Sorry for any inconvenience");




?>
