<?php
session_start();
require_once "../database/MyDatabase.php";
require_once "../clases/Admin.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/Pokedex/clases/Mensaje.php";

//if (!isset($_SESSION['usuario_id'])) {
//    header("Location: /Pokedex/index.php");
//    exit;
//}

$db = new MyDatabase();
$admin = new Admin($db->getConection());

if (isset($_GET["id"])) {
    $id = filter_var($_GET["id"], FILTER_VALIDATE_INT);

    if (!$id) {
        Mensaje::guardar("El ID del Pokémon no es válido.", "danger");
        header("location: ../index.php");
        exit;
    }

    $pokemonExistente = $admin->obtenerPokemon($id);
    if (!$pokemonExistente) {
        Mensaje::guardar("No existe el Pokémon que intentas eliminar.", "warning");
        header("location: ../index.php");
        exit;
    }

    $resultado = $admin->eliminarPokemon($id);

    if ($resultado) { // Si la eliminación en la base de datos fue exitosa
        $imagenPokemon = $pokemonExistente["imagen"];
        $rutaPokemon = "../img/" . $imagenPokemon;
        if (file_exists($rutaPokemon)) {
            unlink($rutaPokemon);
        }
        Mensaje::guardar("Pokémon eliminado correctamente.", "success");
    } else {
        Mensaje::guardar("Error al eliminar el Pokémon de la base de datos: ", "danger");
    }

    header("location: ../index.php");
    exit;

} else {
    Mensaje::guardar("No se proporcionó un ID de Pokémon para eliminar.", "warning");
    header("location: ../index.php");
    exit;
}