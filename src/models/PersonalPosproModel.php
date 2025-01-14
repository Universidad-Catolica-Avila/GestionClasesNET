<?php
namespace admin\gestionDeClases\Models;
use admin\gestionDeClases\Entities\UserEntity;
use admin\gestionDeClases\Config\Parameters;


class PersonalPosproModel extends Model{

    public function __construct(){
        parent::__construct();
        $this->tabla = "personalpospro";
    }
    
    public function nuevoPospro($id, $nombre){
        $consulta ="INSERT INTO {$this->tabla} (idPersonal, nombre)
                    VALUES (:id, :nombre)";
        try{
            $sentencia = $this->conn->prepare($consulta);
            $sentencia->bindParam(':id', $id, \PDO::PARAM_INT);
            $sentencia->bindParam(':nombre', $nombre, \PDO::PARAM_STR);


           return  $sentencia->execute();   
            // Se retorna el objeto:
        }catch(\PDOException $e){
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }
    public function getAllPorId($id){
        $consulta ="SELECT * FROM {$this->tabla} WHERE idPersonal = :id";
        try{
            $sentencia = $this->conn->prepare($consulta);

            $sentencia->bindParam(':id', $id);

            $sentencia->execute();
            // Se retorna el objeto:
            return $sentencia->fetch(\PDO::FETCH_OBJ);
        }catch(\PDOException $e){
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }


}