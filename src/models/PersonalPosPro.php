<?php
namespace admin\gestionDeClases\Models;
use admin\gestionDeClases\Entities\UserEntity;
use admin\gestionDeClases\Config\Parameters;


class PersonalPosPro extends Model{

    public function __construct(){
        parent::__construct();
        $this->tabla = "personalpospro";
    }
    
    public function getEditor($id){

        $consulta = "SELECT * FROM {$this->tabla}
                     WHERE idPersonal = :id";

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
}