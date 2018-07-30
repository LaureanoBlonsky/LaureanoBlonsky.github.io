<?php
require_once "Producto.php";

function getProducto($producto){
    
    $nom;
    $desc;
    $precio;
    $dimension;
    
    switch ($producto) {
    case "cs1-kit":
		$nom="Kit CS1 Compressor";
        $desc="Kit CS1 Compresor para armar.";
        $precio=899;	
        $dimension="30x30x30,500";
        break;
    case "cs1-pedal":
        $nom="Pedal CS1 Compressor";
        $desc="Pedal CS1 Compresor listo para tocar.";
        $precio=1999;	
            $dimension="30x30x30,500";
        break;
            
    case "ff-kit":
        $nom="Kit FF Fuzz";
        $desc="Kit FF Fuzz para armar.";
        $precio=799;	
        $dimension="30x30x30,500";
        break;
    case "ff-pedal":
        $nom="Pedal FF Fuzz";
        $desc="Pedal FF Fuzz listo para tocar.";
        $precio=1799;	
        $dimension="30x30x30,500";
        break;
            
    case "bb-kit":
        $nom="Kit BB Overdrive";
        $desc="Kit BB Overdrive para armar.";
        $precio=600;	
        $dimension="30x30x30,500";
        break;
    case "bb-pedal":
        $nom="Pedal BB Overdrive";
        $desc="Pedal BB Overdrive listo para tocar.";
        $precio=1600;	
        $dimension="30x30x30,500";
        break;
            
            
    }
    
    return $prod = new Producto($producto, $nom, $desc, $precio, $dimension);
    
    
}