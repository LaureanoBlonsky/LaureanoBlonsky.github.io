<?php



function formatDateDiaMes($fecha)
{
  $dias = array("domingo","lunes","martes","miercoles","jueves","viernes","sábado");
  $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

  return $dias[date ( 'w' ,$fecha )]." ".date('d',$fecha)." de ".$meses[date('n',$fecha)-1] ;
//Salida: Viernes 24 de Febrero del 2012

}

function getBusinessDayOfMonth( $days ) {
  date_default_timezone_set('America/Argentina/Buenos_Aires');
  //$date = date('m/d/Y h:i:s a', time());

   $time = time(); //finding # of business days after 1st of the month
   $i = 0; //start with zero
   while ($i < $days) { //loop through until reached the amount of weekdays
       $time = strtotime("+1 day", $time); //Increase day by 1
       if (date("N", $time) < 6) { //test if M-F
           $i++; //Increase by 1
       }
   }

   return $time;
}

function logMensaje($message) {
  //$config = include('config.php');
  error_log($message);
  //error_log($message, 3, $config['logPath']);
}
