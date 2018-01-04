<?php

include 'funciones.php';

require_once "lib/mercadopago.php";

$mp = new MP('6214401356148350', 'jnRiFXOi20shSLqmm1dyIhS5hc15crxw');

$codPostal = $_POST['codPostal'];
$codProducto = $_POST['codProducto'];

$params = NULL;
$demoraProduccion = NULL;

switch ($codProducto) {
    case "cs1-kit":
				$params = array(
					"dimensions" => "30x30x30,500",
					"zip_code" => $codPostal,
								"item_price"=>"10000.58",
								"local_pickup" => true
								//,
					//"free_method" => "73328" // optional
				);
        $demoraProduccion = 0;
        break;
    case "cs1-pedal":
        //completar
        break;
    case "ff-kit":
        //completar
        break;
}





$response = $mp->get("/shipping_options", $params);



$shipping_options = $response['response']['options'];

foreach($shipping_options as $shipping_option) {
		$value = $shipping_option['shipping_method_id'];
		$name = $shipping_option['name'];
		$checked = $shipping_option['display'] == "recommended" ? "checked='checked'" : "";
		$shipping_speed = $shipping_option['estimated_delivery_time']['shipping'];
		$estimated_delivery = $shipping_speed < 24 ? 1 : ceil($shipping_speed / 24); //from departure, estimated delivery time
    $estimated_delivery = $estimated_delivery+$demoraProduccion;
		$cost = $shipping_option['cost'];
		$cost = $cost == 0 ? "FREE" : "$cost";

		echo "<tr class='cart_item'>";

		echo "<td class='product-name'>";
		echo "<div class='radio mt-2 ml-1'>";
		echo "<input type='radio' name='shippingOption' id='".$value."' value='".$value."'>";
		echo "<label style='letter-spacing: initial;'  class='c-gray strong-600' for='".$value."'>".$name."</label>";
		echo "</div>";
		echo "</td>";
		echo "<td class='product-total text-center' value='".$cost."' for='".$value."'>";
		echo "$".$cost;
		echo "</td>";
		echo "<td class='product-total text-right'>";

    echo formatDateDiaMes(getBusinessDayOfMonth($estimated_delivery));

		//echo $estimated_delivery == 1 ? "1 día hábil" : $estimated_delivery." días hábiles";
		echo "</td>";
		echo "</tr>";
}
?>
