<?php

class ValidacionFormulario
{

    public function __construct()
    {

    }

    public function obtenerErrores($campos, $archivos)
    {

        $errores = [];

        $numeroIdentificador = $campos["numeroIdentificador"];
        $nombre = $campos["nombre"];
        $tipo = $campos["tipo"];
        $descripcion = $campos["descripcion"];

        if ($numeroIdentificador == "") {
            $errores[] = "El numero de identificador es obligatorio";
        }

        if ($nombre == "") {
            $errores[] = "El nombre del Pókemon es obligatorio";
        }

        if ($tipo == "") {
            $errores[] = "El tipo de Pókemon es obligatorio";
        }

        if ($descripcion == "") {
            $errores[] = "La descripción es obligatoria";
        }

        if($archivos == "" || $archivos == UPLOAD_ERR_NO_FILE ){
            $errores[] = "La imagen del pokemon es obligatoria";
        }

        return $errores;

    }


}