<?php
require_once ('lib/mercadopago.php');
require_once ('productos.php');

if(!isset($_SESSION)){session_start();}

$idVenta = $_SESSION['idVenta'];

$nombre = $_POST['pnombre'];
$mail = $_POST['pmail'];
$producto = $_POST['pproducto'];
$precioProducto=null;
$tituloProducto=null;
$descProducto=null;
$dimensions=null;
$prod = getProducto($producto);
$precioProducto= $prod->precio;
//$precioProducto= 10;
$tituloProducto=$prod->nombre;
$descProducto=$prod->descripcion;
$dimensions=$prod->dimension;



$mp = new MP('6214401356148350', 'jnRiFXOi20shSLqmm1dyIhS5hc15crxw');


///////////
$preference_data = array(
	"items" => array(
		array(
			"id" => $producto,
			"title" => $tituloProducto,
			"currency_id" => "ARS",
			"picture_url" =>"https://www.mercadopago.com/org-img/MP3/home/logomp3.gif",
			"description" => $descProducto,
			"category_id" => "Category",
			"quantity" => 1,
			"unit_price" => $precioProducto
		)
	),
	"payer" => array(
		"name" => $nombre,
		//"surname" => "user-surname",
		"email" => $mail,
		//"date_created" => "2014-07-28T09:50:37.521-04:00",

		"address" => array(
			"zip_code" => "1408"
		)
	),
	"back_urls" => array(
		"success" => "https://armatupedal.com/checkout/checkout-listo.html",
		"failure" => "http://www.failure.com",
		"pending" => "http://www.pending.com"
	),
  //"success" => "https://192.168.0.103/a<tp/checkout/checkout-listo.html",
  //"failure" => "http://192.168.0.103/atp/checkout/index.html?pago=incompleto",
  //"pending" => "http://192.168.0.103/atp/checkout/index.html?pago=incompleto"
	//"auto_return" => "approved",
	/*"payment_methods" => array(
		"excluded_payment_methods" => array(
			array(
				"id" => "amex",
			)
		),
		"excluded_payment_types" => array(
			array(
				"id" => "ticket"
			)
		),
		"installments" => 24,
		"default_payment_method_id" => null,
		"default_installments" => null,
	),*/
	/*"shipments" => array(
		"receiver_address" => array(
			"zip_code" => "1430",
			"street_number"=> 123,
			"street_name"=> "Street",
			"floor"=> 4,
			"apartment"=> "C"
		)
	),*/
  "shipments" => array(
		"mode" => "me2",
		"dimensions" => $dimensions,
		"local_pickup" => true,
		"zip_code" => "1409"
	),
	"notification_url" => "https://www.your-site.com/ipn",
	"external_reference" => $idVenta,
	"expires" => false,
	"expiration_date_from" => null,
	"expiration_date_to" => null
);

////////////
/*
$preference_data = array(
	"items" => array(
		array(
			"title" => "Multicolor kite",
			"quantity" => 1,
			"currency_id" => "ARS", // Available currencies at: https://api.mercadopago.com/currencies
			"unit_price" => 10.00
		)
	)
);
*/
$preference = $mp->create_preference($preference_data);
echo $preference['response']['init_point'];

?>
