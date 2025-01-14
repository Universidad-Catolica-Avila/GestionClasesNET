<?php

namespace admin\gestionDeClases\Models;

use admin\gestionDeClases\Entities\UserEntity;
use admin\gestionDeClases\Config\Parameters;


class RegistroClaseSemanalModel extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->tabla = "registroclasesemanal";
    }
    public function registrosCoincidentes($idHorario, $fechaInicio, $fechaFin, $nuevaFecha)
    {
        try {
            $sql = "INSERT INTO [{$this->tabla}] (idHorario, semanaInicio, semanaFin, fecha, grabado, editado, bruto, estado)
          VALUES (:idHorario, :semanaInicio, :semanaFin, :fecha, '0', '0', '0', 'Pendiente')";
            $consulta = $this->conn->prepare($sql);
            $consulta->bindParam(":idHorario", $idHorario);
            $consulta->bindParam(":semanaInicio", $fechaInicio);
            $consulta->bindParam(":semanaFin", $fechaFin);
            $consulta->bindParam(":fecha", $nuevaFecha);

            return  $consulta->execute();
        } catch (\PDOException $e) {
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }

    public function getAllRegistrosPorSemana($semanaInicio){
        try {

            $consulta = "SELECT * FROM {$this->tabla}
                         WHERE semanaInicio = :semanaInicio";

            $sentencia = $this->conn->prepare($consulta);
            $sentencia->bindParam(":semanaInicio", $semanaInicio, \PDO::PARAM_STR);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
            return $resultado;

        } catch (\PDOException $e) {
            echo '<p>Fallo en la conexion:' . $e->getMessage() . '</p>';
        }        
    }

    public function insertarTecnicoUnaClase($idRegistro, $idTecnico)
    {
        try {
            $sql = "UPDATE registroclasesemanal
                    SET tecnico = :idTecnico
                    WHERE idRegistro = :idRegistro; ";
            $consulta = $this->conn->prepare($sql);
            $consulta->bindParam(":idTecnico", $idTecnico, \PDO::PARAM_INT);
            $consulta->bindParam(":idRegistro", $idRegistro, \PDO::PARAM_INT);
            return  $consulta->execute();
        } catch (\PDOException $e) {
            echo '<p>Fallo en la conexion:' . $e->getMessage() . '</p>';
        }
    }
    public function insertarEditor($idEditor, $idRegistro)
    {
        try {
            $sql = "UPDATE registroclasesemanal SET editor=:idEditor WHERE registroclasesemanal.idRegistro=:idRegistro ";
            $consulta = $this->conn->prepare($sql);
            $consulta->bindParam(":idEditor", $idEditor, \PDO::PARAM_INT);
            $consulta->bindParam(":idRegistro", $idRegistro, \PDO::PARAM_INT);
            return  $consulta->execute();
        } catch (\PDOException $e) {
            return NULL;
        }
    }

    public function insertarTrimador($idTrimador, $idRegistro)
    {
        try {
            $sql = "UPDATE registroclasesemanal SET trimador=:idTrimador WHERE registroclasesemanal.idRegistro=:idRegistro ";
            $consulta = $this->conn->prepare($sql);
            $consulta->bindParam(":idTrimador", $idTrimador, \PDO::PARAM_INT);
            $consulta->bindParam(":idRegistro", $idRegistro, \PDO::PARAM_INT);
            return  $consulta->execute();
        } catch (\PDOException $e) {
            return NULL;
        }
    }

    public function getDistinctSemanas()
    {
        try {
            $consulta = "SELECT DISTINCT semanaInicio FROM registroclasesemanal
                         ORDER BY semanaInicio DESC;";

            $sentencia = $this->conn->prepare($consulta);
            $sentencia->setFetchMode(\PDO::FETCH_OBJ);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll();
            return $resultado;
        } catch (\PDOException $e) {
            return NULL;
        }
    }

    public function getUltimaFechaInicio()
    {
        try {
            $consulta = "SELECT MAX(semanaInicio) 
                        FROM registroclasesemanal;";

            $sentencia = $this->conn->prepare($consulta);
            $sentencia->setFetchMode(\PDO::FETCH_ASSOC);
            $sentencia->execute();
            $resultado = $sentencia->fetch();
            return $resultado;
        } catch (\PDOException $e) {
            return NULL;
        }
    }
    public function getCountByFechaInicio($fechaInicio)
    {
        try {
            $consulta = "SELECT * FROM registroclasesemanal r
                         JOIN horarioclases h ON r.idHorario = h.idHorario
                         WHERE r.semanaInicio = :fechaInicio;";

            $sentencia = $this->conn->prepare($consulta);
            $sentencia->bindValue(':fechaInicio', $fechaInicio, \PDO::PARAM_STR);
            $sentencia->setFetchMode(\PDO::FETCH_OBJ);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll();
            return $resultado;
        } catch (\PDOException $e) {
            return NULL;
        }
    }
    public function getSemanaActualByFechaInicio($fechaInicio, $inicio, $n_registro)
    {
        try {
            $consulta = "SELECT * 
            FROM registroclasesemanal r
            JOIN horarioclases h ON r.idHorario = h.idHorario
            WHERE r.semanaInicio = :fechaInicio
            ORDER BY     
            CASE h.dia
                    WHEN 'Lunes' THEN 1
                    WHEN 'Martes' THEN 2
                    WHEN 'Miércoles' THEN 3
                    WHEN 'Jueves' THEN 4
                    WHEN 'Viernes' THEN 5
                    WHEN 'Sábado' THEN 6
                    WHEN 'Domingo' THEN 7
                    ELSE 8  
                END ASC,
                 h.hora ASC
            OFFSET :inicio ROWS
            FETCH NEXT :n_registro ROWS ONLY;";

            $sentencia = $this->conn->prepare($consulta);

            $sentencia->bindParam(':fechaInicio', $fechaInicio, \PDO::PARAM_STR);
            $sentencia->bindParam(':inicio', $inicio, \PDO::PARAM_INT);
            $sentencia->bindParam(':n_registro', $n_registro, \PDO::PARAM_INT);

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

    public function getSemanaActualFiltered($filtroTipo, $filtroDia, $filtroTipoPAD, $filtroCodigo, $filtroGrabado, $inicio, $n_registro, $fechaInicio)
    {
        try {
            // Iniciar la consulta SQL
            $consulta = "SELECT * 
                     FROM registroclasesemanal r
                     JOIN horarioclases h ON r.idHorario = h.idHorario
                     WHERE r.semanaInicio = :fechaInicio";

            // Agregar filtros a la consulta si no están vacíos
            if (!empty($filtroTipo)) {
                $consulta .= " AND h.tipoTitulacion = :filtroTipo";
            }
            if (!empty($filtroDia)) {
                $consulta .= " AND h.dia = :filtroDia";
            }
            if (!empty($filtroTipoPAD)) {
                $consulta .= " AND h.tipoPAD = :filtroTipoPAD";
            }
            if (!empty($filtroCodigo)) {
                $consulta .= " AND h.codigoPAD LIKE :filtroCodigo";
            }
            if ($filtroGrabado !== '') {
                $consulta .= " AND r.grabado = :filtroGrabado";
            }

            // Añadir paginación con OFFSET y FETCH NEXT (SQL Server)
            $consulta .= " ORDER BY 
            CASE h.dia
                    WHEN 'Lunes' THEN 1
                    WHEN 'Martes' THEN 2
                    WHEN 'Miércoles' THEN 3
                    WHEN 'Jueves' THEN 4
                    WHEN 'Viernes' THEN 5
                    WHEN 'Sábado' THEN 6
                    WHEN 'Domingo' THEN 7
                    ELSE 8  
                END ASC,
                 h.hora ASC
                       OFFSET :inicio ROWS
                       FETCH NEXT :n_registro ROWS ONLY";

            // Preparar la consulta
            $sentencia = $this->conn->prepare($consulta);

            // Asignar los valores de los filtros y parámetros de paginación
            if (!empty($filtroTipo)) {
                $sentencia->bindValue(':filtroTipo', $filtroTipo, \PDO::PARAM_STR);
            }
            if (!empty($filtroDia)) {
                $sentencia->bindValue(':filtroDia', $filtroDia, \PDO::PARAM_STR);
            }
            if (!empty($filtroTipoPAD)) {
                $sentencia->bindValue(':filtroTipoPAD', $filtroTipoPAD, \PDO::PARAM_STR);
            }
            if (!empty($filtroCodigo)) {
                $sentencia->bindValue(':filtroCodigo', $filtroCodigo . "%", \PDO::PARAM_STR); // LIKE con comodines
            }
            if ($filtroGrabado !== '') {
                $sentencia->bindValue(':filtroGrabado', $filtroGrabado, \PDO::PARAM_INT);
            }

            // Paginación
            $sentencia->bindValue(':inicio', $inicio, \PDO::PARAM_INT);
            $sentencia->bindValue(':n_registro', $n_registro, \PDO::PARAM_INT);

            // Parámetro para fecha
            $sentencia->bindValue(':fechaInicio', $fechaInicio, \PDO::PARAM_STR);

            // Ejecutar la consulta
            $sentencia->execute();

            // Establecer el modo de recuperación de datos
            $sentencia->setFetchMode(\PDO::FETCH_OBJ);

            // Obtener los resultados
            $resultado = $sentencia->fetchAll();

            return $resultado;
        } catch (\PDOException $e) {
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }
    public function resultadosFiltros($filtroTipo, $filtroDia, $filtroTipoPAD, $filtroCodigo, $filtroGrabado, $fechaInicio)
{
    try {
        // Iniciar la consulta SQL
        $consulta = "SELECT * 
                     FROM horarioClases h
                     JOIN registroClaseSemanal r
                     ON h.idHorario = r.idHorario
                     WHERE r.semanaInicio = :fechaInicio";

        // Inicializar el array de parámetros
        $parametros = [':fechaInicio' => $fechaInicio];

        // Agregar filtros a la consulta si no están vacíos
        if (!empty($filtroTipo)) {
            $consulta .= " AND h.tipoTitulacion = :filtroTipo";
            $parametros[':filtroTipo'] = $filtroTipo;
        }
        if (!empty($filtroDia)) {
            $consulta .= " AND h.dia = :filtroDia";
            $parametros[':filtroDia'] = $filtroDia;
        }
        if (!empty($filtroTipoPAD)) {
            $consulta .= " AND h.tipoPAD = :filtroTipoPAD";
            $parametros[':filtroTipoPAD'] = $filtroTipoPAD;
        }
        if (!empty($filtroCodigo)) {
            $consulta .= " AND h.codigoPAD LIKE :filtroCodigo";
            $parametros[':filtroCodigo'] = $filtroCodigo . "%";
        }
        if ($filtroGrabado !== '') {
            $consulta .= " AND r.grabado = :filtroGrabado";
            $parametros[':filtroGrabado'] = $filtroGrabado;
        }

        // Preparar la consulta
        $sentencia = $this->conn->prepare($consulta);

        // Ejecutar la consulta con los parámetros
        $sentencia->execute($parametros);

        // Obtener los resultados
        return $sentencia->fetchAll(\PDO::FETCH_OBJ);
    } catch (\PDOException $e) {
        // Manejar errores
        echo "Error en la consulta: " . $e->getMessage();
        return [];
    }
}

    public function actualizarGrabado($idRegistro, $grabado)
    { //actualizar campo grabado 
        try {
            $consulta = "UPDATE registroclasesemanal SET grabado=:grabada WHERE idRegistro=:idRegistro;";
            $sentencia = $this->conn->prepare($consulta);
            $sentencia->bindParam(":grabada", $grabado, \pdo::PARAM_INT);
            $sentencia->bindParam(":idRegistro", $idRegistro, \PDO::PARAM_INT);
            return  $sentencia->execute();;
        } catch (\PDOException $e) {
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }
    public function actualizarCampoBruto($idRegistro, $bruto)
    { //actualizar campo bruto 
        try {
            $consulta = "UPDATE registroclasesemanal SET bruto=:bruto WHERE idRegistro=:idRegistro;";
            $sentencia = $this->conn->prepare($consulta);
            $sentencia->bindParam(":bruto", $bruto, \PDO::PARAM_INT);
            $sentencia->bindParam(":idRegistro", $idRegistro, \PDO::PARAM_INT);
            return  $sentencia->execute();;
        } catch (\PDOException $e) {
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }
    public function actualizarCampoObservaciones($idRegistro, $observaciones)
    { //actualizar campo observaciones 
        try {
            $consulta = "UPDATE registroclasesemanal SET observacionesTecnico=:observaciones WHERE idRegistro=:idRegistro;";
            $sentencia = $this->conn->prepare($consulta);
            $sentencia->bindParam(":observaciones", $observaciones);
            $sentencia->bindParam(":idRegistro", $idRegistro, \PDO::PARAM_INT);
            return  $sentencia->execute();;
        } catch (\PDOException $e) {
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }
    public function actualizarCampoEditado($idRegistro, $editado)
    { //actualizar campo editado 
        try {
            $consulta = "UPDATE registroclasesemanal SET editado=:editado WHERE idRegistro=:idRegistro;";
            $sentencia = $this->conn->prepare($consulta);
            $sentencia->bindParam(":editado", $editado);
            $sentencia->bindParam(":idRegistro", $idRegistro, \PDO::PARAM_INT);
            return  $sentencia->execute();;
        } catch (\PDOException $e) {
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }
    public function actualizarCampoGrabacionBrutoBorrado($idRegistro, $grabacionBrutoBorrado)
    { //actualizar campo grabacionBrutoBorrado 
        try {
            $consulta = "UPDATE registroclasesemanal SET grabacionBrutoBorrado=:grabacionBrutoBorrado WHERE idRegistro=:idRegistro;";
            $sentencia = $this->conn->prepare($consulta);
            $sentencia->bindParam(":grabacionBrutoBorrado", $grabacionBrutoBorrado);
            $sentencia->bindParam(":idRegistro", $idRegistro, \PDO::PARAM_INT);
            return  $sentencia->execute();;
        } catch (\PDOException $e) {
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }
    public function actualizarCampoDuracionBruto($idRegistro, $duracionBruto)
    { //actualizar campo duracionBruto 
        try {
            $consulta = "UPDATE registroclasesemanal SET duracionBruto=:duracionBruto WHERE idRegistro=:idRegistro;";
            $sentencia = $this->conn->prepare($consulta);
            $sentencia->bindParam(":duracionBruto", $duracionBruto);
            $sentencia->bindParam(":idRegistro", $idRegistro, \PDO::PARAM_INT);
            return  $sentencia->execute();
        } catch (\PDOException $e) {
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }
    public function actualizarCampoObservacionesEditor($idRegistro, $observaciones)
    { //actualizar campo observaciones 
        try {
            $consulta = "UPDATE registroclasesemanal SET observacionesEditorTrimador=:observaciones WHERE idRegistro=:idRegistro;";
            $sentencia = $this->conn->prepare($consulta);
            $sentencia->bindParam(":observaciones", $observaciones);
            $sentencia->bindParam(":idRegistro", $idRegistro, \PDO::PARAM_INT);
            return  $sentencia->execute();
        } catch (\PDOException $e) {
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }
    public function cambiarEstado($idRegistro, $estado)
    {
        try {
            $consulta = "UPDATE registroclasesemanal SET estado=:estado WHERE idRegistro=:idRegistro;";
            $sentencia = $this->conn->prepare($consulta);
            $sentencia->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $sentencia->bindParam(":idRegistro", $idRegistro, \PDO::PARAM_INT);
            return  $sentencia->execute();
        } catch (\PDOException $e) {
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }
    public function getOneRegistro($id)
    {
        try {

            $consulta = "SELECT * FROM {$this->tabla} r
                         JOIN horarioClases h ON r.idHorario = h.idHorario 
                         WHERE r.idRegistro = :id";

            $sentencia = $this->conn->prepare($consulta);
            $sentencia->bindParam(':id', $id);
            $sentencia->setFetchMode(\PDO::FETCH_OBJ);
            $sentencia->execute();

            $resultado = $sentencia->fetch();
            return $resultado;
        } catch (\PDOException $e) {
            echo '<p>Fallo en la conexion:' . $e->getMessage() . '</p>';
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }

    public function borrarRegistro($idRegistro)
    {
        try {
            $consulta = "DELETE FROM $this->tabla WHERE idRegistro=:idRegistro";

            $sentencia = $this->conn->prepare($consulta);
            $sentencia->bindParam(":idRegistro", $idRegistro);

            return  $sentencia->execute();
        } catch (\PDOException $e) {
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }

    public function modificarRegistro($idRegistro, $tecnico, $grabado, $bruto, $observacionesTecnico, $duracionBruto, $editor, $trimador, $observacionesEditorTrimador, $estado)
    {
        try {
            //Actualizamos solo los campos que han sido enviados a través del formulario.
            $consulta = "UPDATE registroClaseSemanal 
                         SET 
                             tecnico = :tecnico,
                             grabado = :grabado,
                             bruto = :bruto,
                             observacionesTecnico = :observacionesTecnico,
                             duracionBruto = :duracionBruto,
                             editor = :editor,
                             trimador = :trimador,
                             observacionesEditorTrimador = :observacionesEditorTrimador,
                             estado = :estado
                         WHERE idRegistro = :idRegistro";

            // Preparar la sentencia
            $sentencia = $this->conn->prepare($consulta);

            // Vincular los parámetros con la consulta SQL
            $sentencia->bindParam(":idRegistro", $idRegistro);
            $sentencia->bindParam(":tecnico", $tecnico);
            $sentencia->bindParam(":grabado", $grabado);
            $sentencia->bindParam(":bruto", $bruto);
            $sentencia->bindParam(":observacionesTecnico", $observacionesTecnico);
            $sentencia->bindParam(":duracionBruto", $duracionBruto);
            $sentencia->bindParam(":editor", $editor);
            $sentencia->bindParam(":trimador", $trimador);
            $sentencia->bindParam(":observacionesEditorTrimador", $observacionesEditorTrimador);
            $sentencia->bindParam(":estado", $estado);

            // Ejecutar la sentencia
            return $sentencia->execute();
        } catch (\PDOException $e) {
            // En caso de error, mostrar mensaje
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }
    public function getDISTINCTEstado()
    {
        try {
            $consulta = "SELECT DISTINCT estado FROM registroclasesemanal";

            $sentencia = $this->conn->prepare($consulta);
            $sentencia->execute();
            $sentencia->setFetchMode(\PDO::FETCH_OBJ);

            $resultado = $sentencia->fetchAll();
            return $resultado;
        } catch (\PDOException $e) {
            echo "<h1><br>Fichero: " . $e->getFile();
            echo "<br>Linea: " . $e->getLine();
            exit("<br>Error: " . $e->getMessage());
        }
    }
}
