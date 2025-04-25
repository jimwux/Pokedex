<?php

class MyDatabase
{
    private $conection;

    public function __construct()
    {
        $config = parse_ini_file("config.ini");
        $this->conection = new MySQLi(
            $config['host'],
            $config['user'],
            $config['pass'],
            $config['db']);

        if ($this->conection->select_db($config['db']) === false) {
            die("Error al seleccionar la base de datos: " . $this->conection->error);
        }
    }

    public function __destruct()
    {
        $this->conection->close();
    }

    public function query($sql)
    {
        $datos = $this->conection->query($sql);
        return $datos->fetch_all(MYSQLI_ASSOC);
    }

}