<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/Pokedex/clases/Mensaje.php";

class Admin
{

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // AGREGA
    public function agregarPokemon(Pokemon $pokemon, $tipos)
    {

        // Agrega la información a la tabla Pokemon
        $query = "INSERT INTO pokemon (numero_identificador, nombre, descripcion, imagen) VALUES (?, ?, ?, ?)";
        // Preparo la consulta para errores de inyecciones sql we
        $inyeccion = $this->conexion->prepare($query);
        $inyeccion->bind_param("isss", $pokemon->numero_identificador, $pokemon->nombre, $pokemon->descripcion, $pokemon->imagen);

        if (!$inyeccion->execute()) {
            Mensaje::guardar("Error al agregar el Pókemon", "danger");
            return false;
        }

        // Agrega la información a la tabla pokemon_tipo (N a N)
        $query2 = "INSERT INTO pokemon_tipo (pokemon_id, tipo_id) VALUES (?, ?)";
        $inyeccion2 = $this->conexion->prepare($query2);

        foreach ($tipos as $tipo) {
            if (!empty($tipo)) {
                // Creo variables porque no me permite pasarla por valor sino por referencia
                $pokemonId = intval($pokemon->numero_identificador);
                $tipoId = intval($tipo);
                $inyeccion2->bind_param("ii", $pokemonId, $tipoId);
                if (!$inyeccion2->execute()) {
                    Mensaje::guardar("Error al agregar el Tipo al Pokemon", "danger");
                    return false;
                }
            }
        }
        Mensaje::guardar("Pókemon agregado correctamente", "success");
    }

    // OBTIENE
    public function obtenerPokemon($id)
    {
        $query = "SELECT * FROM pokemon WHERE id = ? LIMIT 1";

        $inyeccion = $this->conexion->prepare($query);
        $inyeccion->bind_param("i", $id);
        $inyeccion->execute();

        $pokemonObtenido = $inyeccion->get_result();
        return $pokemonObtenido->fetch_assoc();
    }

    public function obtenerTiposPokemon()
    {
        $query = "SELECT * FROM tipo";
        $inyeccion = $this->conexion->prepare($query);
        $inyeccion->execute();
        $pokemonTipos = $inyeccion->get_result();
        return $pokemonTipos;
    }

    //Metodo para iterar en actualizar.php
    public function obtenerTiposDeUnPokemonIndividual($idPokemon)
    {
        $query = "SELECT t.nombre AS tipo_nombre, t.id AS tipo_id 
                  FROM tipo t
                  JOIN pokemon_tipo pt ON pt.tipo_id = t.id
                  JOIN pokemon p ON p.numero_identificador = pt.pokemon_id
                  WHERE p.id = ?";

        $inyeccion = $this->conexion->prepare($query);
        $inyeccion->bind_param("i", $idPokemon);
        $inyeccion->execute();
        $pokemonObtenido = $inyeccion->get_result();
        return $pokemonObtenido;
    }

    // ACTUALIZA
    public function actualizarPokemon($id, $pokemonActualizado, $imagenNueva)
    {
        $numeroIdentificadorDB = intval($pokemonActualizado["numeroIdentificador"] ?? "");
        $nombreDB = $pokemonActualizado["nombre"] ?? "";
        $descripcionDB = $pokemonActualizado["descripcion"] ?? "";
        $imagenDB = $pokemonActualizado["imagen"] ?? "";

        // Paso 1: Extraer los id de los tipos nuevos
        $tiposNuevos = $pokemonActualizado['tipo']; // array con ids de tipo nuevos

        // Paso 2: Obtener los tipos actuales desde la base de datos
        $queryTiposActuales = "SELECT tipo_id FROM pokemon_tipo WHERE pokemon_id = ?";
        $stmtTiposActuales = $this->conexion->prepare($queryTiposActuales);
        $stmtTiposActuales->bind_param("i", $numeroIdentificadorDB);
        $stmtTiposActuales->execute();
        $resultTipos = $stmtTiposActuales->get_result();

        $tiposActuales = [];
        while ($fila = $resultTipos->fetch_assoc()) {
            $tiposActuales[] = $fila['tipo_id'];
        }

        // Paso 3: Comparar tipos actuales vs nuevos
        sort($tiposActuales);
        $tiposNuevosFiltrados = array_filter($tiposNuevos, fn($id) => !empty($id));
        sort($tiposNuevosFiltrados);

        if ($tiposActuales !== $tiposNuevosFiltrados) {
            // Paso 4: Actualizar si hay al menos 1 tipo diferente
            // Primero borramos los tipos actuales
            $queryDelete = "DELETE FROM pokemon_tipo WHERE pokemon_id = ?";
            $stmtDelete = $this->conexion->prepare($queryDelete);
            $stmtDelete->bind_param("i", $numeroIdentificadorDB);
            $stmtDelete->execute();

            // Insertamos los nuevos
            $queryInsert = "INSERT INTO pokemon_tipo (pokemon_id, tipo_id) VALUES (?, ?)";
            $stmtInsert = $this->conexion->prepare($queryInsert);
            foreach ($tiposNuevosFiltrados as $tipoId) {
                $tipoIdInt = intval($tipoId);
                $stmtInsert->bind_param("ii", $numeroIdentificadorDB, $tipoIdInt);
                $stmtInsert->execute();
            }
        }



        if ($imagenNueva != null) {
            // Borra la imagen de la carpeta img
            if (file_exists("../img/" . $imagenDB)) {
                unlink("../img/" . $imagenDB);
            } else {
                return "Error al subir la imagen.";
            }
            $nombreImagenNueva = md5(uniqid(rand(), true)) . ".jpg";
            $rutaImagenNueva = "../img/" . $nombreImagenNueva;

            // Guarda la nueva imagen en la carpeta
            if (move_uploaded_file($imagenNueva["imagen"]["tmp_name"], $rutaImagenNueva)) {
                $imagenDB = $nombreImagenNueva;
            } else {
                return "Error al subir la nueva imagen.";
            }
        }

        $query = "UPDATE pokemon SET
                  numero_identificador = ?,
                  nombre = ?,
                  descripcion = ?,
                  imagen = ?
                  WHERE id = ?";

        $inyeccion = $this->conexion->prepare($query);
        $inyeccion->bind_param("ssssi",
            $numeroIdentificadorDB,
            $nombreDB,
            $descripcionDB,
            $imagenDB,
            $id
        );

        if ($inyeccion->execute()) {
            Mensaje::guardar("Pokemon actualizado correctamente", "success");
            return true;
        } else {
            Mensaje::guardar("Error al evolucionar el Pokemon :(: " . $inyeccion->error, "danger");
            return false;
        }
    }

    public function eliminarPokemon($id)
    {

        $pokemonObtenido = $this->obtenerPokemon($id);

        $queryNaN = "DELETE FROM pokemon_tipo WHERE pokemon_id = ?";
        $inyeccionNaN = $this->conexion->prepare($queryNaN);
        $inyeccionNaN->bind_param("i", $pokemonObtenido['numero_identificador']);
        $inyeccionNaN->execute();

        $query = "DELETE FROM pokemon WHERE id = ?";

        $inyeccion = $this->conexion->prepare($query);
        $inyeccion->bind_param("i", $id);

        if ($inyeccion->execute()) {
            return "Pokemon eliminado correctamente";
        } else {
            return "Error al eliminar la Pókemon";
        }

    }

}