<?php
class Pokemon
{
    public $id;
    public $numero_identificador;
    public $nombre;
    public $descripcion;
    public $imagen;

    public function __construct($numero_identificador, $nombre, $descripcion, $imagen, $id = null) {
        $this->id = $id;
        $this->numero_identificador = $numero_identificador;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->imagen = $imagen;
    }

}