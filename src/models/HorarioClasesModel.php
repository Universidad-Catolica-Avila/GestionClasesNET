<?php

namespace admin\gestionDeClases\Models;

use admin\gestionDeClases\Entities\UserEntity;
use admin\gestionDeClases\Config\Parameters;

class HorarioClasesModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tabla = "horarioclases";
    }

    public function getConnection() {
        return $this->conn;
    }

    public function registrarHorario($tipoTitulacion, $dia, $hora, $tipoPAD, $codigoPAD, $asignatura, $profesor, $aula, $titulacion, $semestre, $curso)
    {
        try {
            $consulta = "INSERT INTO {$this->tabla} (tipoTitulacion, dia, hora, tipoPAD, codigoPAD, asignatura, profesor, aula, titulacion, semestre, curso, FechaDeInserccion, estadoHorario) 
                         VALUES (:tipoTitulacion, :dia, :hora, :tipoPAD, :codigoPAD, :asignatura, :profesor, :aula, :titulacion, :semestre, :curso, GETDATE(), '1')";
    
            $sentencia = $this->conn->prepare($consulta);
            return $sentencia->execute([
                ':tipoTitulacion' => $tipoTitulacion,
                ':dia'            => $dia,
                ':hora'           => $hora,
                ':tipoPAD'        => $tipoPAD,
                ':codigoPAD'      => $codigoPAD,
                ':asignatura'     => $asignatura,
                ':profesor'       => $profesor,
                ':aula'           => $aula,
                ':titulacion'     => $titulacion,
                ':semestre'       => $semestre,
                ':curso'          => $curso
            ]);

        } catch (\PDOException $e) {
            throw new \Exception("Error al registrar el horario: " . $e->getMessage());
        }
    }

    public function numeroDatosUltimaTabla()
    {
        try {
            $consulta = "DECLARE @maxFecha DATETIME;
                         SET @maxFecha = (SELECT MAX(FechaDeInserccion) FROM horarioclases);

                         SELECT idHorario, tipoTitulacion, dia, hora, tipoPAD, codigoPAD, asignatura, profesor, aula, titulacion, semestre, curso, estadoHorario
                         FROM horarioclases
                         WHERE FechaDeInserccion BETWEEN DATEADD(MINUTE, -3, @maxFecha) AND @maxFecha
                         ORDER BY idHorario";

            $sentencia = $this->conn->prepare($consulta);
            $sentencia->setFetchMode(\PDO::FETCH_OBJ);
            $sentencia->execute();
            return $sentencia->fetchAll();
        } catch (\PDOException $e) {
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }
    public function verUltimaTablaInsertada($inicio, $n_registro)
    {
        try {
            $consulta = "DECLARE @maxFecha DATETIME;
                         SET @maxFecha = (SELECT MAX(FechaDeInserccion) FROM horarioclases);
            
                         SELECT idHorario, tipoTitulacion, dia, hora, tipoPAD, codigoPAD, asignatura, profesor, aula, titulacion, semestre, curso, estadoHorario
                         FROM horarioclases
                         WHERE FechaDeInserccion BETWEEN DATEADD(MINUTE, -10, @maxFecha) AND @maxFecha
                         ORDER BY 
                            CASE dia
                                WHEN 'Lunes' THEN 1
                                WHEN 'Martes' THEN 2
                                WHEN 'Miércoles' THEN 3
                                WHEN 'Jueves' THEN 4
                                WHEN 'Viernes' THEN 5
                                WHEN 'Sábado' THEN 6
                                WHEN 'Domingo' THEN 7
                                ELSE 8
                            END, hora ASC
                         OFFSET :inicio ROWS FETCH NEXT :n_registro ROWS ONLY";

            $sentencia = $this->conn->prepare($consulta);
            $sentencia->bindValue(':inicio', $inicio, \PDO::PARAM_INT);
            $sentencia->bindValue(':n_registro', $n_registro, \PDO::PARAM_INT);
            $sentencia->setFetchMode(\PDO::FETCH_OBJ);
            $sentencia->execute();
            return $sentencia->fetchAll();
        } catch (\PDOException $e) {
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }

    public function comprobarHorarioClase($curso, $semestre)
    {
        try {
            $consulta = "SELECT * FROM {$this->tabla} WHERE curso = :curso AND semestre = :semestre AND estadoHorario = '1'";
            $sentencia = $this->conn->prepare($consulta);
            $sentencia->bindParam(':curso', $curso);
            $sentencia->bindParam(':semestre', $semestre);
            $sentencia->execute();
            return $sentencia->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "<p>Falló la conexión: {$e->getMessage()}</p>";
            return false;
        }
    }

    public function getAllDistinct($columna)
    {
        try {
            $consulta = "SELECT DISTINCT $columna 
                         FROM {$this->tabla}
                         WHERE $columna != ''
                         ORDER BY $columna ASC";
            $sentencia = $this->conn->prepare($consulta);
            $sentencia->execute();
            return $sentencia->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            echo "<p>Falló la conexión: {$e->getMessage()}</p>";
            return false;
        }
    }

    public function getOneRegistro($id)
    {
        try {
            $consulta = "SELECT * FROM {$this->tabla} WHERE idHorario = :id";
            $sentencia = $this->conn->prepare($consulta);
            $sentencia->bindParam(':id', $id, \PDO::PARAM_INT);
            $sentencia->setFetchMode(\PDO::FETCH_OBJ);
            $sentencia->execute();
            return $sentencia->fetch();
        } catch (\PDOException $e) {
            echo '<p>Fallo en la conexión: ' . $e->getMessage() . '</p>';
            return null;
        }
    }

    public function cambiarEstadoHorario($idHorario, $estado)
    {
        try {
            $consulta = "UPDATE {$this->tabla}
                         SET estadoHorario = :estado
                         WHERE idHorario = :idHorario";

            $sentencia = $this->conn->prepare($consulta);
            $sentencia->bindParam(':idHorario', $idHorario, \PDO::PARAM_INT);
            $sentencia->bindParam(':estado', $estado, \PDO::PARAM_INT);
            return $sentencia->execute();
        } catch (\PDOException $e) {
            echo "<p>Falló la conexión: {$e->getMessage()}</p>";
            return false;
        }
    }

    public function getRegistros() {
        try {
            $consulta = "SELECT codigoPAD FROM {$this->tabla}";
    
            $sentencia = $this->conn->prepare($consulta);
            $sentencia->execute();

            return $sentencia->fetchAll(\PDO::FETCH_COLUMN, 0);


        } catch (\PDOException $e) {
            echo "Error al consultar la base de datos: " . $e->getMessage();
            exit();
        }
    }
}
