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
    }

    public function __destruct()
    {
        $this->conection->close();
    }

    public function getConection(){
        return $this->conection;
    }

    public function query($sql)
    {
        $resultado = $this->conection->query($sql);

        if ($resultado === true) { // INSERT, UPDATE o DELETE
            return true;
        } elseif ($resultado instanceof mysqli_result) { // SELECT
            return $resultado->fetch_all(MYSQLI_ASSOC);
        } else {
            return false;
        }
    }

}