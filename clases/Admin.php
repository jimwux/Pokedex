<?php

class Admin
{

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // AGREGA
    public function agregarPokemon(Pokemon $pokemon)
    {

        $query = "INSERT INTO pokemon (numero_identificador, nombre, tipo, descripcion, imagen) VALUES (?, ?, ?, ?, ?)";

        // Preparo la consulta para errores de inyecciones sql we
        $inyeccion = $this->conexion->prepare($query);
        $inyeccion->bind_param("issss", $pokemon->numero_identificador, $pokemon->nombre, $pokemon->tipo, $pokemon->descripcion, $pokemon->imagen);

        if ($inyeccion->execute()) {
            return "Pokemon agregado correctamente";
        } else {
            return "Error al agregar Pókemon";
        }

    }

    // OBTIENE
    public function obtenerPokemon($id)
    {
        $query = "SELECT * FROM pokemon WHERE numero_identificador = ? LIMIT 1";

        $inyeccion = $this->conexion->prepare($query);
        $inyeccion->bind_param("i", $id);
        $inyeccion->execute();

        $pokemonObtenido = $inyeccion->get_result();
        return $pokemonObtenido->fetch_assoc();
    }

    // ACTUALIZA
    public function actualizarPokemon($id, $pokemonActualizado, $imagenNueva)
    {
        $numeroIdentificadorDB = $pokemonActualizado["numeroIdentificador"] ?? "";
        $nombreDB = $pokemonActualizado["nombre"] ?? "";
        $tipoDB = $pokemonActualizado["tipo"] ?? "";
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
                  tipo = ?,
                  descripcion = ?,
                  imagen = ?
                  WHERE id = ?";

        $inyeccion = $this->conexion->prepare($query);
        $inyeccion->bind_param("issssi",
            $numeroIdentificadorDB,
            $nombreDB,
            $tipoDB,
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

        $query = "DELETE FROM pokemon WHERE numero_identificador = ?";

        $inyeccion = $this->conexion->prepare($query);
        $inyeccion->bind_param("i", $id);

        if ($inyeccion->execute()) {
            return "Pokemon eliminado correctamente";
        } else {
            return "Error al eliminar la Pókemon";
        }

    }

}