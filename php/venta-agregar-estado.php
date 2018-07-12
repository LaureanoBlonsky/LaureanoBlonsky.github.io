<?php
if(!isset($_SESSION)){session_start();}

//include 'funciones.php';
require_once 'db.php';

$idVenta = $_SESSION['idVenta'];

$idEstado = $_POST['pidEstado'];
$comentario = $_POST['pcomentario'];


try {
  $stmt = DB::run("INSERT INTO venta_estado (id_venta, id_estado, comentario, fechaYhora) VALUES (?,?,?,now())",
  [$idVenta, $idEstado, $comentario]);
}catch (Exception $e){
  throw $e;
  header('HTTP/1.1 500 Internal Server Error'); exit("error venta-agregar-estado.php error1");
}











//para error
//header('HTTP/1.1 500 Internal Server Error'); exit("Something went wrong when we tried to save your comment. Please try again later. Sorry for any inconvenience");




?>
