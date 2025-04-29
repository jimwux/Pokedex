<?php
include './database/MyDatabase.php';

class Usuario
{
    public $id;
    public $nombre;
    public $email;
    public $password;

    public function __construct($id, $nombre, $email, $password) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->password = $password;
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->nombre;
    }

    public function getEmail() {
        return $this->email;
    }

    public static function registrar($username, $email, $password)
    {
        if (empty($password)) {
            return 'password_empty';
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $db = new MyDatabase();

        // Verificar si ya existe un usuario con ese email
        $sql_check = "SELECT id FROM usuario WHERE email = '$email'";
        $resultado = $db->query($sql_check);

        if (is_array($resultado) && count($resultado) > 0) {
            return 'email_exists'; // El email ya está registrado
        }

        // Insertar nuevo usuario
        $sql = "INSERT INTO usuario (nombre, email, password) 
            VALUES ('$username', '$email', '$password_hash')";
        $resultado = $db->query($sql);

        return $resultado ? true : 'insert_error';
    }
    public static function verificarLogin($email, $password)
    {
        $db = new MyDatabase();

        $sql = "SELECT * FROM usuario WHERE email = '$email'";
        $result = $db->query($sql);

        if ($result && count($result) > 0) {
            $usuario_data = $result[0];
            if (password_verify($password, $usuario_data['password'])) {
                // Ahora se pasa el id del usuario correctamente al objeto Usuario
                return new Usuario(
                    $usuario_data['id'],       // id autoincrementado
                    $usuario_data['username'], // nombre
                    $usuario_data['email'],    // email
                    $usuario_data['password']  // password
                );
            }
        }

        return false;
    }


}