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
        $tipo = $campos["tipo"] ?? [];
        $descripcion = $campos["descripcion"];


        if ($numeroIdentificador === "") {
            $errores[] = "El número de identificador es obligatorio";
        } elseif (!ctype_digit($numeroIdentificador)) {
            $errores[] = "El número de identificador debe ser un número entero positivo";
        }

        if ($nombre == "") {
            $errores[] = "El nombre del Pókemon es obligatorio";
        }

        if (empty($tipo)) {
            $errores[] = "El tipo de Pókemon es obligatorio, elige por lo menos uno";
        }

        if (count($tipo) > 2) {
            $errores[] = "Un Pokemón no puede tener más de dos tipos";
        }

        if ($descripcion == "") {
            $errores[] = "La descripción es obligatoria";
        }

        if ($archivos == "" || $archivos == UPLOAD_ERR_NO_FILE) {
            $errores[] = "La imagen del pokemon es obligatoria";
        }

        return $errores;

    }


}