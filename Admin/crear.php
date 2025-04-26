<?php

include_once "../clases/Pokemon.php";
include_once "../database/MyDatabase.php";
include_once "../clases/Admin.php";
include_once "../clases/ValidacionFormulario.php";

$numeroIdentificador = $_POST["numeroIdentificador"] ?? "";
$nombre = $_POST["nombre"] ?? "";
$tipo = $_POST["tipo"] ?? "";
$descripcion = $_POST["descripcion"] ?? "";

$errores = [];

// Verifica si el metodo es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Instancia la BD y el Admin
    $db = new MyDatabase();
    $admin = new Admin($db->getConection());

    $validadorDeFormulario = new ValidacionFormulario();
    $errores = $validadorDeFormulario->obtenerErrores($_POST, $_FILES["imagen"]["error"]);;

    $verificarNumeroIdentificador = $admin->obtenerPokemon($numeroIdentificador);

    if (isset($verificarNumeroIdentificador["numero_identificador"]) == $numeroIdentificador) {
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
                $pokemon = new Pokemon($numeroIdentificador, $nombre, $tipo, $descripcion, $nombreImagen);


                $admin->agregarPokemon($pokemon);
                header("Location: ../index.php");
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Pokémon</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f0f0; /* Un fondo grisáceo suave */
        }

        .pokemon-form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        .pokemon-header {
            color: #ffcb05; /* Amarillo Pikachu */
            text-shadow: 2px 2px #3b4cca; /* Azul oscuro Pokémon */
            margin-bottom: 30px;
        }

        .form-group label {
            color: #3b4cca; /* Azul oscuro Pokémon */
            font-weight: bold;
        }

        .form-control {
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-control:focus {
            border-color: #ffcb05; /* Amarillo Pikachu al enfocar */
            box-shadow: 0 0 0 0.2rem rgba(255, 203, 5, 0.25);
        }

        .btn-primary {
            background-color: #4CAF50; /* Verde tipo Planta */
            border-color: #4CAF50;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            padding: 10px 20px;
        }

        .btn-primary:hover {
            background-color: #45a049;
            border-color: #45a049;
        }

        .form-group select {
            appearance: none; /* Elimina la apariencia predeterminada del select */
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg fill="%233b4cca" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>');
            background-repeat: no-repeat;
            background-position-x: 95%;
            background-position-y: 50%;
            padding-right: 25px;
        }
    </style>
</head>
<body>

<div class="container">
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
                    <select class="form-control" id="tipo" name="tipo">
                        <option value="normal">Normal</option>
                        <option value="hierba">Hierba</option>
                        <option value="fuego">Fuego</option>
                        <option value="agua">Agua</option>
                        <option value="planta">Planta</option>
                        <option value="eléctrico">Eléctrico</option>
                        <option value="hielo">Hielo</option>
                        <option value="lucha">Lucha</option>
                        <option value="veneno">Veneno</option>
                        <option value="tierra">Tierra</option>
                        <option value="volador">Volador</option>
                        <option value="psíquico">Psíquico</option>
                        <option value="bicho">Bicho</option>
                        <option value="roca">Roca</option>
                        <option value="fantasma">Fantasma</option>
                        <option value="dragón">Dragón</option>
                        <option value="siniestro">Siniestro</option>
                        <option value="acero">Acero</option>
                        <option value="hada">Hada</option>
                    </select>
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

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
