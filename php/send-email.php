<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';
    require_once 'db.php';


    $post = (! empty($_POST)) ? true : false;
$error=0;


//if($post):
            // POST variables
            $name = stripslashes($_POST['name']);
            $email = trim($_POST['email']);                        
            $message = stripslashes($_POST['message']);
            $date = date('Y-m-d H:i:s');

            if (isset($_POST['phone'])  && !empty($_POST["phone"]) ){
                $phone = stripslashes($_POST['phone']);                
            } else {
                $phone = null;
            }
//endif;


try {
    $stmt = DB::run("INSERT INTO contacto (nombre,mail,telefono,mensaje, fechaYhora) VALUES (?,?,?,?,now())",
    [$name,$email, $phone, $message]);
        
  //$_SESSION['idVenta'] = $idVenta;
  
}catch (Exception $e){
    
    $error=1;
    error_log("error: ".$e);
    throw $e;
} finally {  
  if($error==1){
    header('HTTP/1.1 500 Internal Server Error'); exit("Hubo un inconveniente al intentar suscribirte. Lo estamos arreglando! Intentá mas tarde por favor.");
  }
    
    
try {
            // Mail subject
            $subject = "[ATP-WEB] Nuevo contacto - ".$name;
        
            // Mail body
            $body  = "Nuevo contacto:<br><br>";
            $body .= '<table>';
            $body .= '<tbody>';
            $body .= '<tr><td>Fecha y hora:</td><td><strong>'.$date.'</strong></td></tr>';
            $body .= '<tr><td>Nombre:</td><td><strong>'.$name.'</strong></td></tr>';
            $body .= '<tr><td>Mail:</td><td>'. $email .'</td></tr>';
            $body .= '<tr><td>Tel:</td><td>'. $phone .'</td></tr>';            
            $body .= '<tr><td>Mensaje:</td><td>'. $message .'</td></tr>';

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

}catch (Exception $e){
    $error=1;
    error_log("error: ".$e);
    throw $e;
} finally {  
  if($error==1){
    header('HTTP/1.1 500 Internal Server Error'); exit("Hubo un inconveniente al intentar suscribirte. Lo estamos arreglando! Intentá mas tarde por favor.");
  }
}
    
    
    
    
}







echo json_encode($status);

?>
