<?php

//include 'funciones.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';
    require_once 'db.php';
    require_once "EstadosPagos.php";
    require_once "productos.php";

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

$prod = getProducto($producto);



//header('HTTP/1.1 500 Internal Server Error'); exit("nom:".$nombre.$mail."sus:".$suscribirse.$idEnvio.$precioEnvio.$producto.$efectivo);
$error=0;
try {
  $stmt = DB::run("INSERT INTO venta (producto, precioProducto, nombre, mail, sessionId, idEnvio, precioEnvio, efectivo, suscribirse, fechaYhora ) VALUES (?,?,?,?,?,?,?,?,?,now())",
  [$prod->cod, $prod->precio, $nombre, $mail, $sessionId,$idEnvio, $precioEnvio, $efectivo, $suscribirse]);
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
    [$nombre, $mail, "compra ".$prod->cod]);
  }
    

    
      // Mail subject
            $subject = "[ATP-WEB] InicioCompra - ".$prod->cod." - ".$nombre;
    
            $date = date('Y-m-d H:i:s');
        
            // Mail body
            $body  = "Nuevo InicioCompra:<br><br>";
            $body .= '<table>';
            $body .= '<tbody>';
            $body .= '<tr><td>Fecha y hora:</td><td><strong>'.$date.'</strong></td></tr>';
            $body .= '<tr><td>Producto:</td><td><strong>'.$prod->cod.'</strong></td></tr>';
            $body .= '<tr><td>Precio:</td><td><strong>$'.$prod->precio.'</strong></td></tr>';
            $body .= '<tr><td>Nombre:</td><td><strong>'.$nombre.'</strong></td></tr>';            
            $body .= '<tr><td>Mail:</td><td>'. $mail .'</td></tr>';
            $body .= '<tr><td>SessionId:</td><td>'. $sessionId .'</td></tr>';            
            $body .= '<tr><td>IdEnvio:</td><td>'. $idEnvio .'</td></tr>';            
            $body .= '<tr><td>PrecioEnvio:</td><td>'. $precioEnvio .'</td></tr>';
            $body .= '<tr><td>Efectivo:</td><td>'. $efectivo .'</td></tr>';
            $body .= '<tr><td>Suscripto:</td><td>'. $suscribirse .'</td></tr>';

            $body .= '</tbody>';
            $body .= '</table>';            

                $mail = new PHPMailer;
                $mail->isSMTP(); 
                $mail->SMTPDebug = 0; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
                $mail->Host = "mx1.hostinger.com.ar"; // use $mail->Host = gethostbyname('smtp.gmail.com'); // if your network does not support SMTP over IPv6
                $mail->Port = 587; // TLS only
                $mail->SMTPSecure = 'tls'; // ssl is depracated
                $mail->SMTPAuth = true;
                $mail->Username = "web@armatupedal.com";
                $mail->Password = "atpclave1";
                $mail->setFrom("web@armatupedal.com", "[ATP-WEB]");
                $mail->addAddress("laureanoblonsky@gmail.com", "Laureano");
                $mail->Subject = $subject;
                $mail->msgHTML($body); //$mail->msgHTML(file_get_contents('contents.html'), __DIR__); //Read an HTML message body from an external file, convert referenced images to embedded,
                $mail->AltBody = 'HTML messaging not supported';
                // $mail->addAttachment('images/phpmailer_mini.png'); //Attach an image file
                
                if(! $mail->send()) {
                    $status = array(
                        'status' => 'error',
                        'notify_title' => 'Error!',
                        'notify_message' => 'El mensaje no se pudo enviar! Por favor, volvé a intentarlo.',
                        'notify_type' => 'danger'
                    );
                } else {
                    $status = array(
                        'status' => 'success',
                        'notify_title' => 'Enviado!',
                        'notify_message' => 'Mensaje enviado!',
                        'notify_type' => 'success'
                    );
                }
    
    
    
    
    
  if($error==1){
    header('HTTP/1.1 500 Internal Server Error'); exit("Hubo un inconveniente de conexión al intentar realizar la compra. Nada que no se pueda arreglar, comunicate con nosotros.");
  }
}












//para error
//header('HTTP/1.1 500 Internal Server Error'); exit("Something went wrong when we tried to save your comment. Please try again later. Sorry for any inconvenience");




?>
