<?php
namespace admin\gestionDeClases\Models;
use admin\gestionDeClases\Entities\UserEntity;
use admin\gestionDeClases\Config\Parameters;


class UsuarioModel extends Model{

    public function __construct(){
        parent::__construct();
        $this->tabla = "usuarios";
    }

    public function getUsuarioPorEmail($email){
        $consulta = "SELECT * FROM $this->tabla WHERE email = :email";

        try {
            $sentencia = $this->conn->prepare($consulta);
            $sentencia->bindParam(":email", $email, \PDO::PARAM_STR);
            $sentencia->execute();

            return $sentencia->fetch(\PDO::FETCH_OBJ);

        } catch (\PDOException $e) {
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }
    
    public function login($username){
        $consulta ="SELECT * FROM usuarios WHERE nombre = :usuario";
        try{
            $sentencia = $this->conn->prepare($consulta);
            $sentencia->bindParam(':usuario', $username);
            $sentencia->execute();
            // Se retorna el objeto:
            return $sentencia->fetch(\PDO::FETCH_OBJ);
        }catch(\PDOException $e){
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }

    public function register(UserEntity $userEntity) {
        try {
            $consulta = "INSERT INTO {$this->tabla}(nombre, email, password, rol, estado, fecha_baja, observaciones) 
                         VALUES(:usuario, :email, :password, :rol, '1', NULL, NULL)";
    
            $usuario = $userEntity->getNombre();
            $email = $userEntity->getEmail();
            $passwordSecure = password_hash($userEntity->getPassword(), PASSWORD_DEFAULT);
            $rol = $userEntity->getRol();
    
            $sentencia = $this->conn->prepare($consulta);
            $sentencia->bindParam(':usuario', $usuario);
            $sentencia->bindParam(':email', $email);
            $sentencia->bindParam(':password', $passwordSecure);
            $sentencia->bindParam(':rol', $rol);
    
            return $sentencia->execute();

        } catch (\PDOException $e) {
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }

    public function getAllUsuariosMenosAdmin($idUsuario){
        $consulta = "SELECT * FROM {$this->tabla} WHERE idUsuario != :idUsuario";

        try {
            $sentencia = $this->conn->prepare($consulta);
            $sentencia->bindParam(":idUsuario", $idUsuario, \PDO::PARAM_INT);
            $sentencia->execute();

            return $sentencia->fetchAll(\PDO::FETCH_OBJ);

        } catch (\PDOException $e) {
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }

    public function modUsuario($idUsuario, $userEntity) {
        try {
            // Consulta SQL con el marcador de parámetros para idUsuario
            $consulta = "UPDATE usuarios 
                         SET nombre = :nombre, email = :email, password = :password, rol = :rol, estado = :estado, fecha_baja = NULL, observaciones = :observaciones 
                         WHERE idUsuario = :idUsuario";
            
            // Preparar la sentencia SQL
            $sentencia = $this->conn->prepare($consulta);
    
            // Asignar valores de la entidad a variables
            $usuario = $userEntity->getNombre();
            $email = $userEntity->getEmail();
            $passwordSecure = $userEntity->getPassword();
            $rol = $userEntity->getRol();
            $estado = $userEntity->getEstado();
            $observaciones = $userEntity->getobservaciones();
            
            // Vincular los parámetros con los valores correspondientes
            $sentencia->bindValue(':nombre', $usuario, \PDO::PARAM_STR);
            $sentencia->bindValue(':email', $email, \PDO::PARAM_STR);
            $sentencia->bindValue(':password', $passwordSecure, \PDO::PARAM_STR);
            $sentencia->bindValue(':rol', $rol, \PDO::PARAM_STR);
            $sentencia->bindValue(':estado', $estado, \PDO::PARAM_INT);
            $sentencia->bindValue(':observaciones', $observaciones, \PDO::PARAM_STR);
            $sentencia->bindValue(':idUsuario', $idUsuario, \PDO::PARAM_INT);
            
            // Ejecutar la consulta
            return $sentencia->execute();
            
        } catch (\PDOException $e) {
            // Capturar el error y mostrarlo
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }

    public function cambiarEstadoUsuario($idUsuario, $estado) {
        try {

            $fechaBaja = ($estado === 1) ? NULL : date("Y-m-d");

            $consulta = "UPDATE {$this->tabla}
                         SET estado = :estado, fecha_baja = :fecha_baja
                         WHERE idUsuario = :idUsuario";

            $sentencia = $this->conn->prepare($consulta);
            $sentencia->bindParam("idUsuario", $idUsuario, \PDO::PARAM_INT);
            $sentencia->bindParam("estado", $estado, \PDO::PARAM_INT);
            
            if ($fechaBaja === NULL) {
                $sentencia->bindValue("fecha_baja", NULL, \PDO::PARAM_NULL);
            } else {
                $sentencia->bindValue("fecha_baja", $fechaBaja, \PDO::PARAM_STR);
            }

            return $sentencia->execute();

        } catch (\PDOException $e) {
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }    

    public function borrarRol($idUsuario, $tablaRol, $idname){
        $consulta = "DELETE FROM $tablaRol WHERE $idname = :idUsuario";

        try {
            $sentencia = $this->conn->prepare($consulta);
            $sentencia->bindParam(":idUsuario", $idUsuario, \PDO::PARAM_INT);
            
         //   var_dump($sentencia, $tablaRol, $idname);exit;
            $resultado = $sentencia->execute();
           
            return $resultado;


        } catch (\PDOException $e) {
            return NULL;
        }
    }
}