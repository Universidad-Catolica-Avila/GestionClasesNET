<?php

namespace admin\gestionDeClases\Controllers;



use admin\gestionDeClases\Helpers\Authentication;
use PhpOffice\PhpSpreadsheet\IOFactory;
use admin\gestionDeClases\Models\HorarioClasesModel;
use admin\gestionDeClases\Config\Parameters;

class HorarioClasesController
{

    public function index()
    {
    }

    public function inicio()
    {
        if (Authentication::isAdmin()) {
            ViewController::show("views/HorarioClases/subirExcel.php");
            exit();
        } else {
            ViewController::showError(403);
        }
    }

    public function cargarArchivoExcel()
    {
        $errores = [];

        // Verificar si el archivo Excel ha sido subido
        if (isset($_FILES['excelFile'])) {
            $filePath = $_FILES['excelFile']['tmp_name'];

            // Comprobar si el archivo tiene una extensión válida antes de intentar cargarlo
            $fileExtension = pathinfo($_FILES['excelFile']['name'], PATHINFO_EXTENSION);
            if ($fileExtension != 'xlsx' && $fileExtension != 'xls') {
                $errores[] = "El archivo debe ser un archivo de Excel (.xlsx o .xls)";
            }

            // Verificar que el archivo sea accesible
            if (!is_readable($filePath)) {
                $errores[] = "No se puede leer el archivo";
            }

            // Si hay errores, redirigir con los mensajes de error
            if (!empty($errores)) {
                $_SESSION['errores'] = $errores;
                header('Location: ' . Parameters::$BASE_URL . 'HorarioClases/inicio');
                exit();
            }

            // Intentar cargar el archivo de Excel
            try {
                $spreadsheet = IOFactory::load($filePath);
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray();
                return $data;
            } catch (\Exception $e) {
                $errores[] = "Error al cargar el archivo de Excel: " . $e->getMessage();
                $_SESSION['errores'] = $errores;
                header('Location: ' . Parameters::$BASE_URL . 'HorarioClases/inicio');
                exit();
            }
        } else {
            ViewController::showError(404);
        }
    }

    public function procesarYRegistrarDatos($data) {
        $errores = [];
        $batchSize = 100; // Procesar en bloques de 100 registros
    
        // Instanciar el modelo
        $horarioClasesModel = new HorarioClasesModel();
    
        // Obtener la conexión desde el modelo
        $conn = $horarioClasesModel->getConnection();
    
        // Obtener todos los registros existentes (por códigoPAD)
        $registrosExistentes = $horarioClasesModel->getRegistros();
    
        // Dividir los datos en bloques de 100 registros
        $chunks = array_chunk($data, $batchSize);
        foreach ($chunks as $chunk) {
            $conn->beginTransaction();
            try {
                foreach ($chunk as $row) {
                    if (!in_array($row[4], $registrosExistentes)) { // Solo procesar si no existe el registro
                        // Validar y normalizar los campos
                        $valoresPermitidos = Parameters::$TIPO_PAD_ADMITIDO;
                        $erroresRegistro = [];

                        // Normalizar y validar campos
                        $row[2] = isset($row[2]) ? date('H:i:s', strtotime($row[2])) : '-'; // Hora
                        $row[3] = isset($row[3]) ? trim($row[3]) : '-'; // tipoPAD

                        if (!in_array($row[3], $valoresPermitidos)) {
                            $erroresRegistro[] = "Valor no permitido en tipoPAD: " . $row[3];
                        }

                        // Reemplazar valores vacíos con "-"
                        foreach ($row as $key => $value) {
                            if (empty(trim($value)) && $key !== 2) { // Hora ya se normalizó
                                $row[$key] = '-';
                            }
                        }

                        // Registrar errores de validación
                        if (!empty($erroresRegistro)) {
                            $errores[] = "Errores en el registro: " . implode(', ', $erroresRegistro);
                            continue; // Salta al siguiente registro
                        }

                        if (!empty($row[3])) {
                            // Registrar el horario usando el modelo
                            $horarioClasesModel->registrarHorario(
                                $row[0],
                                $row[1],
                                $row[2],
                                $row[3],
                                $row[4],
                                $row[5],
                                $row[6],
                                $row[7],
                                $row[8],
                                $row[9],
                                $row[10]
                            );
                        }
                    }
                }
                // Commit de la transacción después de procesar el bloque
                $conn->commit();
            } catch (\Exception $e) {
                // Si hay un error, hacer rollback y continuar con el siguiente bloque
                $conn->rollBack();
                $errores[] = "Error al procesar el bloque de registros: " . $e->getMessage();
                continue;
            }
        }
    
        // Redirigir según si hubo errores o no
        if (!empty($_SESSION['errores'])) {
            header('Location: ' . Parameters::$BASE_URL . 'HorarioClases/inicio');
            exit();
        }
    
        // Si no hay errores, redirigir a la vista de la tabla
        $_SESSION['mensaje'] = "Se ha subido correctamente";
        header('Location: ' . Parameters::$BASE_URL . 'HorarioClases/verTabla');
        exit();
    }



    public function subirExcel(){
        if (Authentication::isAdmin()) {
            // Cargar el archivo y obtener los datos
            $data = $this->cargarArchivoExcel();

            // Obtener la cabecera del archivo Excel
            $cabeceraEntrante = implode(', ', $data[0]);

            // Validar la cabecera contra la esperada
            $cabeceraEsperada = "Tipo, Día, Hora, Tipo PAD, Código, Asignatura, Profesor, Aula, Titulación, Semestre, Curso";
            $cabeceraEsperada = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $cabeceraEsperada));
            $cabeceraEntrante = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $cabeceraEntrante));

            // Limpiar comillas simples para evitar discrepancias
            $cabeceraEsperada = str_replace("'", "", $cabeceraEsperada);
            $cabeceraEntrante = str_replace("'", "", $cabeceraEntrante);

            // Validar la cabecera
            if ($cabeceraEntrante != $cabeceraEsperada) {
                $errores[] = "La cabecera no coincide con la esperada.";
                $_SESSION['errores'] = $errores;
                header('Location: ' . Parameters::$BASE_URL . 'HorarioClases/inicio');
                exit();
            }

            array_shift($data);

            // Procesar los datos y registrar
            $this->procesarYRegistrarDatos($data);
        } else {
            ViewController::showError(403);
        }
    }

    public function verTabla()
    {
        if (Authentication::isAdmin()) {
            $horarioClasesModel = new HorarioClasesModel();
            $n_registros = 10; // Número de registros por página

            // Validación de la página actual
            $actual = isset($_REQUEST['n']) && is_numeric($_REQUEST['n']) && $_REQUEST['n'] > 0
                ? (int) $_REQUEST['n']
                : 1; // Página actual
            // Calcular el inicio para la paginación
            $inicio = ($actual - 1) * $n_registros;

            $inicio = intval($inicio);

            $semanaActual = $horarioClasesModel->verUltimaTablaInsertada($inicio, $n_registros);

            $total_registros = count($horarioClasesModel->numeroDatosUltimaTabla());

            $total_paginas = ceil($total_registros / $n_registros);
            $actual = max(1, min($total_paginas, $actual));

            if (!$total_registros) {
                $_SESSION['errores'][] = "No hay registros para mostrar.";
                header('Location: ' . Parameters::$BASE_URL . 'HorarioClases/inicio');
                exit();
            }

            ViewController::show('views/horarioClases/verUltimaTabla.php', [
                'semanaActual' => $semanaActual,
                "total_paginas" => $total_paginas,
                "actual" => $actual
            ]);
        } else {
            ViewController::showError(403);
            exit();
        }
    }

    public function cambiarEstadoHorario()
    {
        if (Authentication::isAdmin()) {
            $errores = [];

            $horarioClasesModel = new HorarioClasesModel();
            $idHorario = $_GET['idHorario'];
            $horario = $horarioClasesModel->getOneRegistro($idHorario);

            if (!empty($errores)) {
                $_SESSION['errores'] = $errores;
                header("Location: " . Parameters::$BASE_URL . "HorarioClases/verTabla");
                exit();
            }

            if ($horario) {
                if ($horario->estadoHorario == 1) {
                    $estado = 0;
                    $horarioClasesModel->cambiarEstadoHorario($idHorario, $estado);
                    $_SESSION['mensaje'] = "El registro esta de baja";
                    header("Location: " . Parameters::$BASE_URL . "HorarioClases/verTabla");
                    exit();
                }
                if ($horario->estadoHorario == 0) {
                    $estado = 1;
                    $horarioClasesModel->cambiarEstadoHorario($idHorario, $estado);
                    $_SESSION['mensaje'] = "El registro esta de alta";
                    header("Location: " . Parameters::$BASE_URL . "HorarioClases/verTabla");
                    exit();
                } else {
                    $errores[] = 'Error al cambiar el estado';
                }
            } else {
                $errores[] = 'El registro no existe';
            }
        } else {
            ViewController::showError(403);
        }
    }
}
