<?php

include_once "../database/MyDatabase.php";
include_once "../clases/Admin.php";

if (isset($_GET["id"])) {
    $db = new MyDatabase();
    $admin = new Admin($db->getConection());

    $id = filter_var($_GET["id"], FILTER_VALIDATE_INT);

    if (!$id) {
        header("location: ../index.php");
        return "Id incorrecto";
    }

    if (!$admin->obtenerPokemon($id)) {
        return "No existe el Pókemon a eliminar";;
    }

    $resultado = $admin->eliminarPokemon($id);
    return $resultado; // Retorna un mensaje, ya sea exitoso o de error

}