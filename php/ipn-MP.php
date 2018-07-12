<?php
/**
 * MercadoPago SDK
 * Receive IPN
 * @date 2015/03/17
 * @author fvaccaro
 */
// Include Mercadopago library
require_once "lib/mercadopago.php";
require_once "EstadosPagos.php";
require_once 'db.php';

function agregarEstado($idVenta,$estado,$mp_id,$comentario){
	try{
		$stmt = DB::run("INSERT INTO venta_estado (id_venta, id_estado,mp_id,comentario, fechaYhora) VALUES (?,?,?,?,now())",
		[$idVenta, $estado, $mp_id,$comentario]);
	} catch (Exception $e){
			error_log("ipn-MP.php error al registrar estado: idVenta:".$idVenta." estado:".$estado." mp_id:".$mp_id);
	    throw $e;
	}
}

$mp = new MP('6214401356148350', 'jnRiFXOi20shSLqmm1dyIhS5hc15crxw');

$params = ["access_token" => $mp->get_access_token()];

// Check mandatory parameters
if (!isset($_GET["id"], $_GET["topic"]) || !ctype_digit($_GET["id"])) {
	http_response_code(400);
	return;
}

// Get the payment reported by the IPN. Glossary of attributes response in https://developers.mercadopago.com
if($_GET["topic"] == 'payment'){
	$payment_info = $mp->get("/collections/notifications/" . $_GET["id"], $params, false);
	$merchant_order_info = $mp->get("/merchant_orders/" . $payment_info["response"]["collection"]["merchant_order_id"], $params, false);
// Get the merchant_order reported by the IPN. Glossary of attributes response in https://developers.mercadopago.com
}else if($_GET["topic"] == 'merchant_order'){
	$merchant_order_info = $mp->get("/merchant_orders/" . $_GET["id"], $params, false);
}

//If the payment's transaction amount is equal (or bigger) than the merchant order's amount you can release your items
if ($merchant_order_info["status"] == 200) {
	$transaction_amount_payments= 0;
	$transaction_amount_order = $merchant_order_info["response"]["total_amount"];

    $payments=$merchant_order_info["response"]["payments"];
		$venta=$payment_info['response']['collection']['external_reference'];
    foreach ($payments as  $payment) {
			//echo $payment_info['response']['collection']['external_reference'];
			//echo $_GET["id"];
			echo $payment['status'];
			echo $_GET["id"];
    	if($payment['status'] == 'approved'){
	    	$transaction_amount_payments += $payment['transaction_amount'];
	    } else if($payment['status'] == 'pending'){
				agregarEstado($venta, EstadosPagos::mpPending, $_GET["id"], "ipn-MP.php");
			} else if($payment['status'] == 'in_process'){
				agregarEstado($venta, EstadosPagos::mpInProcess, $_GET["id"], "ipn-MP.php");
			} else if($payment['status'] == 'in_mediation'){
				agregarEstado($venta, EstadosPagos::mpInMediation, $_GET["id"], "ipn-MP.php");
			} else if($payment['status'] == 'rejected'){
				agregarEstado($venta, EstadosPagos::mpRejected, $_GET["id"], "ipn-MP.php");
			} else if($payment['status'] == 'cancelled'){
				agregarEstado($venta, EstadosPagos::mpCancelled, $_GET["id"], "ipn-MP.php");
			} else if($payment['status'] == 'refunded'){
				agregarEstado($venta, EstadosPagos::mpRefunded, $_GET["id"], "ipn-MP.php");
			} else if($payment['status'] == 'charged_back'){
				agregarEstado($venta, EstadosPagos::mpChargedBack, $_GET["id"], "ipn-MP.php");
			} else {
				agregarEstado($venta, EstadosPagos::desconocido, $_GET["id"], "ipn-MP.php - ".$payment['status']);
			}

    }
    if($transaction_amount_payments >= $transaction_amount_order){
    	echo "release your items";

			agregarEstado($venta, EstadosPagos::mpApproved, $_GET["id"], "ipn-MP.php");

    }
    else{
		echo "dont release your items";


	}
}


?>
