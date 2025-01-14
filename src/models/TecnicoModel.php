<?php
namespace admin\gestionDeClases\Models;
use admin\gestionDeClases\Entities\UserEntity;
use admin\gestionDeClases\Config\Parameters;


class TecnicoModel extends Model{

    public function __construct(){
        parent::__construct();
        $this->tabla = "tecnico";
    }
    
    public function nuevoTecnico($id, $nombre){
        $consulta ="INSERT INTO {$this->tabla} (idTecnico, nombre)
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

    public function getTecnico($id){

        $consulta = "SELECT * FROM {$this->tabla}
                     WHERE idTecnico = :id";

        try{
            $sentencia = $this->conn->prepare($consulta);
            $sentencia->bindParam(':id', $id, \PDO::PARAM_INT);
            $sentencia->execute();
            
            return $sentencia->fetch(\PDO::FETCH_OBJ);
            
        }catch(\PDOException $e){
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }    

    public function getAllTecnicos(){

        $consulta = "SELECT * FROM {$this->tabla}";

        try{
            $sentencia = $this->conn->prepare($consulta);
            $sentencia->execute();
            
            return $sentencia->fetchAll(\PDO::FETCH_OBJ);
            
        }catch(\PDOException $e){
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }    
}