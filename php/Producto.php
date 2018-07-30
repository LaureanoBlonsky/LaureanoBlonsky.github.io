<?php
class Producto
{
    
    public $cod;
    public $nombre;
    public $descripcion;
    public $precio;
    public $dimension;

    public function __construct($cod, $nombre, $descripcion, $precio, $dimension) {
        $this->cod = $cod;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->dimension = $dimension;
    }
    
}
?>