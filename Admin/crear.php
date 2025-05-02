<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/Pokedex/clases/Pokemon.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/Pokedex/database/MyDatabase.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/Pokedex/clases/Admin.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/Pokedex/clases/ValidacionFormulario.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/Pokedex/clases/Admin.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$numeroIdentificador = $_POST["numeroIdentificador"] ?? "";
$nombre = $_POST["nombre"] ?? "";
$tipo = $_POST["tipo"] ?? "";
$descripcion = $_POST["descripcion"] ?? "";

// Instancia la BD y el Admin
$db = new MyDatabase();
$admin = new Admin($db->getConection());

$errores = [];

// Obtener tipos de Pokemons
$tiposPokemon = $admin->obtenerTiposPokemon();

// Verifica si el metodo es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $validadorDeFormulario = new ValidacionFormulario();
    $errores = $validadorDeFormulario->obtenerErrores($_POST, $_FILES["imagen"]["error"]);;

    $verificarNumeroIdentificador = $admin->obtenerPokemon($numeroIdentificador);

    if (isset($verificarNumeroIdentificador["numero_identificador"]) == $numeroIdentificador && $numeroIdentificador != "") {
        $errores[] = "El Pókemon con ese numero de identificador ya existe";
    }

    // Si no hay errores sigue con el codigo
    if (empty($errores)) {
        // Verifica si hay una imagen
        if (isset($_FILES["imagen"])) {
            $imagenTmp = $_FILES["imagen"]["tmp_name"];

            // img/ es ruta raiz (CAMBIAR A FUTURO)
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
            $directorioDestino = "../img/";
            $directorioImagenes = $directorioDestino . $nombreImagen;

            // Verifica si hay una carpeta de imagenes, caso contrario la crea
            if (!is_dir("../img/")) {
                mkdir($directorioDestino, 0777, true);
            }

            // Guarda la imagen en la carpeta /img, y guarda la informacion del Pokemon en la BD
            if (move_uploaded_file($imagenTmp, $directorioImagenes)) {
                $pokemon = new Pokemon($numeroIdentificador, $nombre, $descripcion, $nombreImagen);

                $admin->agregarPokemon($pokemon, $tipo);

                header("Location: ../index.php");
            } else {
                Mensaje::guardar("Error al subir imagen", "danger");
                header("Location: ../index.php");
            }


        }
    }

}

require_once $_SERVER['DOCUMENT_ROOT'] . '/Pokedex/head.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Pokedex/navbar.php';

?>


<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 pokemon-form-container">
            <h1 class="pokemon-header text-center">¡Crea un Nuevo Pokémon!</h1>

            <?php
            foreach ($errores as $error) { ?>
                <div>
                    <p class="alert alert-danger"> <?php echo $error ?></p>
                </div>
            <?php } ?>

            <form method="POST" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="numeroIdentificador">Número Identificador:</label>
                    <input type="text" class="form-control" id="numeroIdentificador" name="numeroIdentificador"
                           placeholder="Ingrese el número identificador del Pokémon"
                           value="<?php echo $numeroIdentificador ?>">
                </div>

                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese el nombre"
                           value="<?php echo $nombre ?>">
                </div>

                <div class="form-group">
                    <label for="tipo">Tipo de Pokémon:</label>
                    <div>
                        <?php foreach ($tiposPokemon as $tipo) { ?>
                            <label class="text-black fw-normal">
                                <input type="checkbox" value="<?php echo $tipo["id"]; ?>"
                                       name="tipo[]"
                                    <?php echo (isset($_POST["tipo"]) && in_array($tipo["nombre"], $_POST["tipo"])) ? 'checked' : ''; ?>>
                                <?php echo $tipo["nombre"]; ?>
                            </label>
                        <?php } ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="4"
                              placeholder="Ingrese la descripción del Pokémon"><?php echo $descripcion ?></textarea>
                </div>

                <div class="form-group">
                    <label for="imagen">Imagen:</label>
                    <input type="file" class="form-control-file" id="imagen" name="imagen">
                </div>

                <button type="submit" class="btn btn-primary btn-block">¡Atrapar Nuevo Pokémon!</button>
            </form>
        </div>
    </div>
</div>

<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Pokedex/footer.php';

?>

