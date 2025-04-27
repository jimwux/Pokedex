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

    $pokemonExistente = $admin->obtenerPokemon($id);
    if (!$pokemonExistente) {
        header("location: ../index.php");
        return "No existe el Pókemon a eliminar";
    }

    $resultado = $admin->eliminarPokemon($id);
    // En caso de que se elimine el Pokemon correctamente se elimina la imagen de la carpeta tambien
    if($resultado == "Pokemon eliminado correctamente"){
        $imagenPokemon = $pokemonExistente["imagen"];
        $rutaPokemon = "../img/" .  $imagenPokemon;
        unlink($rutaPokemon);
        header("location: ../index.php");
    }
    return $resultado; // Retorna un mensaje, ya sea exitoso o de error

}