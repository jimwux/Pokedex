<?php

class Mensaje
{

    public static function mostrar()
    {
        if (isset($_SESSION["mensaje"])) {
            $mensaje = $_SESSION["mensaje"]["texto"];
            $tipo = $_SESSION["mensaje"]["tipo"];
            // Saca el mensaje de la sesión para que no se vuelva a mostrar
            unset($_SESSION["mensaje"]);

            $claseAlerta = "";

            switch ($tipo) {
                case "success":
                    $claseAlerta = "alert-success";
                    break;
                case "warning":
                    $claseAlerta = "alert-warning";
                    break;
                case "danger":
                    $claseAlerta = "alert-danger";
                    break;
                default:
                    $claseAlerta = "alert-info";
                    break;
            }

            echo '<div class="alert ' . $claseAlerta . ' alert-dismissible fade show" role="alert">';
            echo $mensaje;
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            echo '</div>';

            // setTimeOut para que solo se muestre 5 segundos el mensaje/alerta
            echo '<script>
                        setTimeout(() => {
                            const alerta = document.querySelector(".alert");
                            if(alerta){
                                alerta.remove();
                            }
                        }, 5000)
                    </script>';
        }
    }

    public static function guardar($mensaje, $tipo)
    {
        $_SESSION["mensaje"] = [
            "texto" => $mensaje,
            "tipo" => $tipo
        ];
    }

}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}