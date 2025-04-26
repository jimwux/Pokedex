<?php

include_once "../database/MyDatabase.php";
include_once "../clases/Admin.php";
include_once "../clases/ValidacionFormulario.php";

$numeroIdentificador = "";
$nombre = "";
$tipo = "";
$descripcion = "";
$imagen = "";
$mensaje = "";

$errores = [];

if (isset($_GET["id"])) {
    $id = ($_GET["id"]);
    $id = filter_var($id, FILTER_VALIDATE_INT);

    $db = new MyDatabase();
    $admin = new Admin($db->getConection());

    $pokemonObtenido = $admin->obtenerPokemon($id);

    if (!$pokemonObtenido) {
        $mensaje = "El pokemon no fue encontrado. <a href='../index.php'>Volver a inicio</a>";
    } else {
        $numeroIdentificador = $pokemonObtenido["numero_identificador"];
        $nombre = $pokemonObtenido["nombre"];
        $tipo = $pokemonObtenido["tipo"];
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

            if (!isset($errores)) {
                return;
            }

            if (empty($_FILES["imagen"]["name"])) {
                // En caso de que se deje la misma imagen
                $resultado = $admin->actualizarPokemon($id, $pokemonActualizado, null);
            } else {
                // En caso de se cambie la imagen
                $resultado = $admin->actualizarPokemon($id, $pokemonActualizado, $_FILES);
            }

            $mensaje = $resultado;
        }
    }
} else {
    header("Location: /");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Pokémon</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f0f0;
        }

        .pokemon-form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        .pokemon-header {
            color: #ffcb05;
            text-shadow: 2px 2px #3b4cca;
            margin-bottom: 30px;
        }

        .form-group label {
            color: #3b4cca;
            font-weight: bold;
        }

        .form-control {
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-control:focus {
            border-color: #ffcb05;
            box-shadow: 0 0 0 0.2rem rgba(255, 203, 5, 0.25);
        }

        .btn-primary {
            background-color: #4CAF50;
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
            appearance: none;
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
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese el nombre"
                           value="<?php echo htmlspecialchars($nombre); ?>">
                </div>

                <div class="form-group">
                    <label for="tipo">Tipo de Pokémon:</label>
                    <select class="form-control" id="tipo" name="tipo">
                        <option value="normal" <?php if ($tipo === 'normal') echo 'selected'; ?>>Normal</option>
                        <option value="hierba" <?php if ($tipo === 'hierba') echo 'selected'; ?>>Hierba</option>
                        <option value="fuego" <?php if ($tipo === 'fuego') echo 'selected'; ?>>Fuego</option>
                        <option value="agua" <?php if ($tipo === 'agua') echo 'selected'; ?>>Agua</option>
                        <option value="planta" <?php if ($tipo === 'planta') echo 'selected'; ?>>Planta</option>
                        <option value="eléctrico" <?php if ($tipo === 'eléctrico') echo 'selected'; ?>>Eléctrico
                        </option>
                        <option value="hielo" <?php if ($tipo === 'hielo') echo 'selected'; ?>>Hielo</option>
                        <option value="lucha" <?php if ($tipo === 'lucha') echo 'selected'; ?>>Lucha</option>
                        <option value="veneno" <?php if ($tipo === 'veneno') echo 'selected'; ?>>Veneno</option>
                        <option value="tierra" <?php if ($tipo === 'tierra') echo 'selected'; ?>>Tierra</option>
                        <option value="volador" <?php if ($tipo === 'volador') echo 'selected'; ?>>Volador</option>
                        <option value="psíquico" <?php if ($tipo === 'psíquico') echo 'selected'; ?>>Psíquico</option>
                        <option value="bicho" <?php if ($tipo === 'bicho') echo 'selected'; ?>>Bicho</option>
                        <option value="roca" <?php if ($tipo === 'roca') echo 'selected'; ?>>Roca</option>
                        <option value="fantasma" <?php if ($tipo === 'fantasma') echo 'selected'; ?>>Fantasma</option>
                        <option value="dragón" <?php if ($tipo === 'dragón') echo 'selected'; ?>>Dragón</option>
                        <option value="siniestro" <?php if ($tipo === 'siniestro') echo 'selected'; ?>>Siniestro
                        </option>
                        <option value="acero" <?php if ($tipo === 'acero') echo 'selected'; ?>>Acero</option>
                        <option value="hada" <?php if ($tipo === 'hada') echo 'selected'; ?>>Hada</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="4"
                              placeholder="Ingrese la descripción del Pokémon"><?php echo htmlspecialchars($descripcion); ?></textarea>
                </div>

                <div class="form-group container">
                    <label for="imagen">Imagen:</label>
                    <input type="file" class="form-control-file" id="imagen" name="imagen">
                    <?php if (!empty($imagen)): ?>
                        <img src="<?php echo "../img/" . htmlspecialchars($imagen); ?>" alt="Imagen Pókemon"
                             style="width: 100%" ">

                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary btn-block">¡Evolucionar Pókemon!</button>
                <input type="hidden" name="id_pokemon" value="<?php echo htmlspecialchars($id); ?>">
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>