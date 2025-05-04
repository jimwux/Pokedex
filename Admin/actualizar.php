<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Pokedex/head.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Pokedex/navbar.php';

require_once $_SERVER['DOCUMENT_ROOT'] . "/Pokedex/clases/Pokemon.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/Pokedex/database/MyDatabase.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/Pokedex/clases/ValidacionFormulario.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/Pokedex/clases/Admin.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: /Pokedex/index.php");
    exit;
}

$numeroIdentificador = "";
$nombre = "";
$tipo = "";
$descripcion = "";
$imagen = "";
$mensaje = "";

$db = new MyDatabase();
$admin = new Admin($db->getConection());


// Obtener los tipos del Pokémon en un array
$tipos = $admin->obtenerTiposDeUnPokemonIndividual($_GET["id"]);
$tiposSeleccionados = [];
if ($tipos->num_rows > 0) {
    while ($row = $tipos->fetch_assoc()) {
        $tiposSeleccionados[] = $row["tipo_id"];
    }
}

$errores = [];

// Obtener tipos de Pokemons
$tiposPokemon = $admin->obtenerTiposPokemon();

if (isset($_GET["id"])) {
    $id = ($_GET["id"]);
    $id = filter_var($id, FILTER_VALIDATE_INT);

    $pokemonObtenido = $admin->obtenerPokemon($id);

    if (!$pokemonObtenido) {
        header("Location: ../index.php");
        $mensaje = "El pokemon no fue encontrado. <a href='../index.php'>Volver a inicio</a>";
    } else {
        $numeroIdentificador = $pokemonObtenido["numero_identificador"];
        $nombre = $pokemonObtenido["nombre"];
        $descripcion = $pokemonObtenido["descripcion"];
        $imagen = $pokemonObtenido["imagen"];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $pokemonActualizado = [
                "numeroIdentificador" => $_POST['numeroIdentificador'] ?? "",
                "nombre" => $_POST['nombre'] ?? "",
                "tipo" => $_POST['tipo'] ?? "",
                "descripcion" => $_POST['descripcion'] ?? "",
                "imagen" => $imagen,
            ];

            // Validar errores
            $validadorDeFormulario = new ValidacionFormulario();
            $errores = $validadorDeFormulario->obtenerErrores($_POST, $imagen);;

            if (empty($errores)) {

                if (empty($_FILES["imagen"]["name"])) {
                    // En caso de que se deje la misma imagen
                    $mensaje = $admin->actualizarPokemon($id, $pokemonActualizado, null);
                    header("Location: ../index.php");
                } else {
                    // En caso de se cambie la imagen
                    header("Location: ../index.php");
                    $mensaje = $admin->actualizarPokemon($id, $pokemonActualizado, $_FILES);
                }
            }
        }
    }
} else {
    header("Location: ../index.php");
    exit();
}

?>


    <div class="container-fluid my-5">

        <a class="btn btn-secondary mt-3 ms-4 mb-2 rounded-pill shadow-sm" href="../index.php"><i class="fas fa-arrow-left me-2"></i> Volver atrás</a>

        <div class="row justify-content-center">
            <div class="col-md-8 pokemon-form-container">
                <h1 class="pokemon-header text-center">¡Evolucionar Pokémon!</h1>
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
                               value="<?php echo htmlspecialchars($numeroIdentificador); ?>">
                    </div>

                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                               placeholder="Ingrese el nombre"
                               value="<?php echo htmlspecialchars($nombre); ?>">
                    </div>

                    <div class="form-group">
                        <label for="tipo">Tipo de Pokémon:</label>
                        <div class="row">
                            <?php foreach ($tiposPokemon as $tipo) { ?>
                            <div class="col-md-4">
                                <label class="text-black fw-normal">
                                    <input type="checkbox" value="<?php echo $tipo["id"]; ?>"
                                           name="tipo[]" class="me-2"
                                        <?php echo in_array($tipo["id"], $tiposSeleccionados) ? "checked" : ""; ?> >
                                    <?php echo $tipo["nombre"]; ?>
                                </label><br>
                            </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción:</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4"
                                  placeholder="Ingrese la descripción del Pokémon"><?php echo htmlspecialchars($descripcion); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="imagen">Imagen:</label>
                        <input type="file" class="form-control" id="imagen" name="imagen">
                        <?php if (!empty($imagen)): ?>
                            <div style="display:flex; justify-content: center" class="my-4">
                                <img src="<?php echo "../img/" . htmlspecialchars($imagen); ?>" alt="Imagen Pókemon"
                                     style="width: 30%;" ">
                            </div>

                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">¡Evolucionar Pókemon!</button>
                    <input type="hidden" name="id_pokemon" value="<?php echo htmlspecialchars($id); ?>">
                </form>
            </div>
        </div>
    </div>

<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Pokedex/footer.php';

?>