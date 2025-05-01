<?php

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
            return "Error al agregar el Pókemon";
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
                    return "Error al agregar el Tipo al Pokemon";
                }
            }
        }
        return "Pókemon agregado correctamente";
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

        if ($imagenNueva != null) {
//            echo "Borro la imagen de la carpeta, genero un nuevo nombre, guardo la imagen en la carpeta y lo subo a la BD";

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
            return "Pokemon actualizado correctamente";
        } else {
            return "Error al evolucionar el Pokemon :(: " . $inyeccion->error;
        }
    }

    public function eliminarPokemon($id)
    {

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