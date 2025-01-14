<?php

namespace admin\gestionDeClases\Models;

use admin\gestionDeClases\Entities\UserEntity;
use admin\gestionDeClases\Config\Parameters;


class SemanaModel extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->tabla = "registroclasesemanal";
    }

    public function getSemanaActual() {
        try {
            $consulta = "SELECT * FROM $this->tabla";

            $sentencia = $this->conn->prepare($consulta);
            $sentencia->setFetchMode(\PDO::FETCH_OBJ);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll();
            return $resultado;

        } catch (\PDOException $e) {
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }
   
}
