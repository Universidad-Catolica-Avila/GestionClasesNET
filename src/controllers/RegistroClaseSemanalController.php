<?php

namespace admin\gestionDeClases\Controllers;

use admin\gestionDeClases\Config\Parameters;
use admin\gestionDeClases\Models\HorarioClasesModel;
use admin\gestionDeClases\Models\PersonalPosPro;
use admin\gestionDeClases\Models\RegistroClaseSemanalModel;
use admin\gestionDeClases\Models\TecnicoModel;
use DateTime;
use admin\gestionDeClases\Helpers\Authentication;
use admin\gestionDeClases\Models\PersonalPosproModel;
use admin\gestionDeClases\Entities\UserEntity;

class RegistroClaseSemanalController

{
    public function mostrarFormularioClaseSemanal()
    { //muestra el formulario de claseSemanal

        ViewController::show("views/registroClaseSemanal/registrar.php");
    }

    public function registrarClaseSemanal()
    {
        if (Authentication::isAdmin()) {
            try {
                $errores = [];
                $semestre = $_POST['semestre'];
                $curso = $_POST['curso'];
                $fechaInicio = $_POST['fechaInicio'];
                $fechaFin = $_POST['fechaFin'];

                // Validar que la fecha de inicio es un lunes
                $fechaInicioDate = new DateTime($fechaInicio);
                if ($fechaInicioDate->format('N') != 1) { // 1 representa lunes
                    $errores[] = "La fecha de inicio debe ser un lunes.";
                }

                // Calcular el domingo correspondiente a la fecha de inicio
                $domingoEsperado = clone $fechaInicioDate;
                $domingoEsperado->modify('+6 days'); // Sumar 6 días desde el lunes

                // Validar que la fecha de fin es el domingo correspondiente
                if ($fechaFin !== $domingoEsperado->format('Y-m-d')) {
                    $errores[] = "La fecha de fin debe ser el domingo de la misma semana que la fecha de inicio.";
                }

                // Procesar los horarios
                $registroClaseSemanalModel = new RegistroClaseSemanalModel();
                $horariosClaseModel = new HorarioClasesModel();
                $horariosClases = $horariosClaseModel->comprobarHorarioClase($curso, $semestre);
                
                if (empty($horariosClases)) {
                    $errores[] = "No hay ningun registro coincidente.";
                }

                if (!empty($errores)) {
                    $_SESSION['errores'] = $errores;
                    header('Location: ' . Parameters::$BASE_URL . 'registroClaseSemanal/mostrarFormularioClaseSemanal');
                    exit();
                }

                $idsHorariosExistentes = array_column($registroClaseSemanalModel->getAllRegistrosPorSemana($fechaInicio), 'idHorario');

                $diasMap = [
                    'Lunes' => 0,
                    'Martes' => 1,
                    'Miercoles' => 2,
                    'Jueves' => 3,
                    'Viernes' => 4,
                    'Sabado' => 5,
                    'Domingo' => 6,
                ];

                foreach ($horariosClases as $contenido) {
                    if (in_array($contenido['idHorario'], $idsHorariosExistentes)) {
                        $errores[] = "Error, el registro ya existe para el horario {$contenido['idHorario']}.";
                        continue;
                    }

                    // Calcular la fecha correspondiente al día de la semana
                    $desplazamiento = $diasMap[$contenido['dia']] ?? 0;
                    $nuevaFecha = date('Y-m-d', strtotime($fechaInicio . "+$desplazamiento day"));

                    $registroClaseSemanalModel->registrosCoincidentes($contenido['idHorario'], $fechaInicio, $fechaFin, $nuevaFecha);
                }

                if (empty($errores)) {
                    header('Location: ' . Parameters::$BASE_URL . 'registroClaseSemanal/getSemanaActual');
                    exit();
                } else {
                    $_SESSION['errores'] = $errores;
                    header('Location: ' . Parameters::$BASE_URL . 'registroClaseSemanal/mostrarFormularioClaseSemanal');
                    exit();
                }
            } catch (\Exception $e) {
                $_SESSION['errores'] = ["Ocurrió un error inesperado: " . $e->getMessage()];
                header('Location: ' . Parameters::$BASE_URL . 'registroClaseSemanal/mostrarFormularioClaseSemanal');
                exit();
            }
        } else {
            ViewController::showError(403);
        }
    }

    public function getSemanaActual(){
        if (Authentication::isUserLogged()) {

            if (isset($_POST['reset-filtro'])) {
                unset($_SESSION['filtros']);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                foreach (['filtro-tipo', 'filtro-dia', 'filtro-tipoPAD', 'filtro-codigo', 'filtro-Grabado', 'fechaInicio'] as $filtro) {
                    if (isset($_POST[$filtro])) {
                        $_SESSION['filtros'][$filtro] = $_POST[$filtro];
                    }
                }
            }

            $filtros = $_SESSION['filtros'] ?? [];
            $filtroTipo = $filtros['filtro-tipo'] ?? '';
            $filtroDia = $filtros['filtro-dia'] ?? '';
            $filtroTipoPAD = $filtros['filtro-tipoPAD'] ?? '';
            $filtroCodigo = $filtros['filtro-codigo'] ?? '';
            $filtroGrabado = $filtros['filtro-Grabado'] ?? '';
            $fechaInicio = $filtros['fechaInicio'] ?? null;

            $registroClaseSemanalModel = new RegistroClaseSemanalModel();

            // Obtener la última fechaInicio si no se seleccionó ninguna
            if (!$fechaInicio) {
                $fechaInicio = $registroClaseSemanalModel->getUltimaFechaInicio();
                $fechaInicio = end($fechaInicio); // Método que retorna la última fecha
                $_SESSION['filtros']['fechaInicio'] = $fechaInicio; // Guardar en sesión
            }

            if (empty($fechaInicio)) {
                $_SESSION['errores'][] = "No hay semanas creadas.";
                header('Location: ' . Parameters::$BASE_URL . 'HorarioClases/inicio');
                exit();
            }

            $n_registros = 10;

            if (empty($filtroTipo) && empty($filtroDia) && empty($filtroTipoPAD) && empty($filtroCodigo) && empty($filtroGrabado)) {
                $total_registros = count($registroClaseSemanalModel->getCountByFechaInicio($fechaInicio));
            } else {
                $total_registros = count($registroClaseSemanalModel->resultadosFiltros($filtroTipo, $filtroDia, $filtroTipoPAD, $filtroCodigo, $filtroGrabado, $fechaInicio));
            }

            $total_paginas = ceil($total_registros / $n_registros);

            // Validación de la página actual
            $actual = isset($_GET['n']) ? $_GET['n'] : 1;
            $actual = max(1, min($total_paginas, $actual));

            // Calcular el inicio para la paginación
            $inicio = ($actual - 1) * $n_registros;
            $inicio = intval($inicio);
            if (empty($filtroTipo) && empty($filtroDia) && empty($filtroTipoPAD) && empty($filtroCodigo) && empty($filtroGrabado)) {
                $semanaActual = $registroClaseSemanalModel->getSemanaActualByFechaInicio($fechaInicio, $inicio, $n_registros);
            } else {
                $semanaActual = $registroClaseSemanalModel->getSemanaActualFiltered($filtroTipo, $filtroDia, $filtroTipoPAD, $filtroCodigo, $filtroGrabado, $inicio, $n_registros, $fechaInicio);
            }

            $tecnicoModel = new TecnicoModel();
            $personalPosproModel = new PersonalPosproModel();

            $editores = $personalPosproModel->getAll();
            $trimadores = $personalPosproModel->getAll();
            $tecnicos = $tecnicoModel->getAll();

            $semanas = $registroClaseSemanalModel->getDistinctSemanas();

            $tipoTitulacion = "tipoTitulacion";
            $dias = "dia";
            $tipoPAD = "tipoPAD";

            $horarioClasesModel = new HorarioClasesModel();
            $Alltipos = $horarioClasesModel->getAllDistinct($tipoTitulacion);
            $Alldias = $horarioClasesModel->getAllDistinct($dias);
            $AlltiposPAD = $horarioClasesModel->getAllDistinct($tipoPAD);

            ViewController::show("views/registroClaseSemanal/semanaActual.php", [
                'semanaActual' => $semanaActual,
                'tecnicos' => $tecnicos,
                'editores' => $editores,
                'trimadores' => $trimadores,
                "total_paginas" => $total_paginas,
                "actual" => $actual,
                "Alltipos" => $Alltipos,
                "dias" => $Alldias,
                "AlltiposPAD" => $AlltiposPAD,
                "filtroTipo" => $filtroTipo,
                "filtroDia" => $filtroDia,
                "filtroTipoPAD" => $filtroTipoPAD,
                "filtroCodigo" => $filtroCodigo,
                "filtroGrabado" => $filtroGrabado,
                "semanas" => $semanas,
                "fechaInicio" => $fechaInicio,
            ]);
        } else {
            ViewController::showError(403);
            exit();
        }
    }

    public function registrarTecnicoEnClase()
    {
        if (Authentication::isAdmin() || Authentication::isAdmPospro() || Authentication::isAdmTecnico()) {

            $errores = [];
            // Obtener los datos enviados en el body (en formato JSON)
            $data = json_decode(file_get_contents('php://input'), true);
            $idRegistro = $data['idRegistro'];
            $idTecnico = $data['idTecnico'];
            $registroClaseSemanalModel = new RegistroClaseSemanalModel();

            if (!empty($errores)) {
                $_SESSION['errores'] = $errores;
                // No rediriges en caso de errores, solo devuelves un JSON con el error.
                echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
                exit();
            }
            $comprobacion = $registroClaseSemanalModel->insertarTecnicoUnaClase($idRegistro, $idTecnico);

            if ($comprobacion) {
                // Respuesta JSON de éxito
                if($idTecnico==NULL){
                    echo json_encode(['success' => true, 'message' => 'Técnico designado correctamente.']);

                }else{
                    echo json_encode(['success' => true, 'message' => 'Técnico asignado correctamente.']);
                }
            } else {
                // Respuesta JSON de error
                echo json_encode(['success' => false, 'message' => 'Error al asignar el técnico.']);
            }
        } else {
            ViewController::showError(403);
        }
    }
    public function insertarEditor()
    {
        if (Authentication::isAdmin() || Authentication::isAdmPospro()) {
            $idRegistro = $_GET['idRegistro'] ?? NULL;
            $idEditor = $_POST['idEditor'] ?? NULL;

            $personalPosproModel = new PersonalPosPro();
            $editorExiste = $personalPosproModel->getEditor($idEditor);

            if ($editorExiste) {
                $registroClaseSemanalModel = new RegistroClaseSemanalModel();
                $registroClaseSemanalModel->insertarEditor($idEditor, $idRegistro);

                $_SESSION['mensaje'] = "Se ha asignado un editor";

                header("Location: " . PARAMETERS::$BASE_URL . "registroClaseSemanal/getSemanaActual");
                exit();
            }
            if ($idEditor == '0') {
                $idEditor = NULL;

                $registroClaseSemanalModel = new RegistroClaseSemanalModel();
                $registroClaseSemanalModel->insertarEditor($idEditor, $idRegistro);

                if (is_null($idEditor)) {
                    $_SESSION['mensaje'] = "Se ha desasignado correctamente";
                }

                header("Location: " . PARAMETERS::$BASE_URL . "registroClaseSemanal/getSemanaActual");
                exit();
            } else {
                $_SESSION['errores'][] = "El Editor no existe";
                header("Location: " . PARAMETERS::$BASE_URL . "registroClaseSemanal/getSemanaActual");
                exit();
            }
        } else {
            ViewController::showError(403);
        }
    }

    public function insertarTrimador()
    {
        if (Authentication::isAdmin() || Authentication::isAdmPospro()) {
            $idRegistro = $_GET['idRegistro'];
            $idTrimador = $_POST['idTrimador'];

            $personalPosproModel = new PersonalPosPro();
            $trimadorExiste = $personalPosproModel->getEditor($idTrimador);

            if ($trimadorExiste) {
                $registroClaseSemanalModel = new RegistroClaseSemanalModel();
                $registroClaseSemanalModel->insertarTrimador($idTrimador, $idRegistro);

                $_SESSION['mensaje'] = "Se ha asignado un trimador";

                header("Location: " . PARAMETERS::$BASE_URL . "registroClaseSemanal/getSemanaActual");
                exit();
            }
            if ($idTrimador == '0') {
                $idTrimador = NULL;

                $registroClaseSemanalModel = new RegistroClaseSemanalModel();
                $registroClaseSemanalModel->insertarTrimador($idTrimador, $idRegistro);

                if (is_null($idTrimador)) {
                    $_SESSION['mensaje'] = "Se ha desasignado correctamente";
                }
                header("Location: " . PARAMETERS::$BASE_URL . "registroClaseSemanal/getSemanaActual");
                exit();
            } else {
                $_SESSION['errores'][] = "El trimador no existe";
                header("Location: " . PARAMETERS::$BASE_URL . "registroClaseSemanal/getSemanaActual");
                exit();
            }
        } else {
            ViewController::showError(403);
        }
    }

    public function actualizarCampoGrabado() //actualizar campo grabado tecnico
    {
        if (Authentication::isAdmTecnico() || Authentication::isTecnico()) {
            // Obtener los datos enviados en el body (en formato JSON)
            $data = json_decode(file_get_contents('php://input'), true);
            $idRegistro = $data['idRegistro'];
            $grabado = $data['grabado'];
            $registroClaseSemanalModel = new RegistroClaseSemanalModel();

            //COMPROBAR NUMERO DE CARACTERES

            $comprobacion = $registroClaseSemanalModel->actualizarGrabado($idRegistro, $grabado);
            if ($comprobacion) {
                // Respuesta JSON de éxito
                echo json_encode(['success' => true, 'message' => 'Campo grabado actualizado.', 'estado' => "",]);
            } else {
                // Respuesta JSON de error
                echo json_encode(['success' => false, 'message' => 'Error al actualizar campo grabado.']);
            }
        }
    }
    public function actualizarCampoBruto() //actualizar campos bruto
    {
        if (Authentication::isAdmTecnico() || Authentication::isTecnico()) {
            // Obtener los datos enviados en el body (en formato JSON)
            $data = json_decode(file_get_contents('php://input'), true);
            $idRegistro = $data['idRegistro'];
            $bruto = $data['bruto'];

            $registroClaseSemanalModel = new RegistroClaseSemanalModel();
            $comprobacion = $registroClaseSemanalModel->actualizarCampoBruto($idRegistro, $bruto);
            if ($comprobacion) {
                // Respuesta JSON de éxito
                echo json_encode(['success' => true, 'message' => 'Campo bruto actualizado.', 'estado' => "",]);
            } else {
                // Respuesta JSON de error
                echo json_encode(['success' => false, 'message' => 'Error al actualizar campo bruto.']);
            }
        }
    }
    public function actualizarCampoObservaciones() //actualizar campo observaciones tecnico
    {
        if (Authentication::isAdmTecnico() || Authentication::isTecnico()) {
            // Obtener los datos enviados en el body (en formato JSON)
            $data = json_decode(file_get_contents('php://input'), true);
            $idRegistro = $data['idRegistro'];
            $observaciones = $data['observaciones'];
            $registroClaseSemanalModel = new RegistroClaseSemanalModel();
            $comprobacion = $registroClaseSemanalModel->actualizarCampoObservaciones($idRegistro, $observaciones);
            if ($comprobacion) {
                // Respuesta JSON de éxito
                echo json_encode(['success' => true, 'message' => 'Campo observacionesTecnico actualizado.', 'estado' => "",]);
            } else {
                // Respuesta JSON de error
                echo json_encode(['success' => false, 'message' => 'Error al actualizar campo observacionesTecnico.']);
            }
        } else {
            ViewController::showError(403);
        }
    }
    public function actualizarCampoEditado() //actualizar campo editado
    {
        if (Authentication::isAdmPospro() || Authentication::isPosproduccion()) {
            // Obtener los datos enviados en el body (en formato JSON)
            $data = json_decode(file_get_contents('php://input'), true);
            $idRegistro = $data['idRegistro'];
            $editado = $data['editado'];
            $registroClaseSemanalModel = new RegistroClaseSemanalModel();
            $comprobacion = $registroClaseSemanalModel->actualizarCampoEditado($idRegistro, $editado);
            if ($comprobacion) {
                // Respuesta JSON de éxito
                echo json_encode(['success' => true, 'message' => 'Campo editado actualizado.', 'estado' => "",]);
            } else {
                // Respuesta JSON de error
                echo json_encode(['success' => false, 'message' => 'Error al actualizar campo editado.']);
            }
        } else {
            ViewController::showError(403);
        }
    }
    public function actualizarCampoGrabacionEditor() //actualizar campo grabacion/bruto borrado
    {
        if (Authentication::isAdmPospro() || Authentication::isPosproduccion()) {
            // Obtener los datos enviados en el body (en formato JSON)
            $data = json_decode(file_get_contents('php://input'), true);
            $idRegistro = $data['idRegistro'];
            $grabacion = $data['grabacion'];
            $registroClaseSemanalModel = new RegistroClaseSemanalModel();
            $comprobacion = $registroClaseSemanalModel->actualizarCampoGrabacionBrutoBorrado($idRegistro, $grabacion);
            if ($comprobacion) {
                // Respuesta JSON de éxito
                echo json_encode(['success' => true, 'message' => 'Campo grabacion/bruto borrado actualizado.', 'estado' => "",]);
            } else {
                // Respuesta JSON de error
                echo json_encode(['success' => false, 'message' => 'Error al actualizar campo grabacion/bruto borrado.']);
            }
        } else {
            ViewController::showError(403);
        }
    }
    public function actualizarCampoDuracionBruto() //actualizar campo duracion bruto
    {
        if (Authentication::isAdmPospro() || Authentication::isPosproduccion()) {
            // Obtener los datos enviados en el body (en formato JSON)
            $data = json_decode(file_get_contents('php://input'), true);
            $idRegistro = $data['idRegistro'];
            $duracionBruto = $data['duracionBruto'];
            if (self::validarTiempo($duracionBruto)) { //validacion del timepo en HH:MM:SS
                $registroClaseSemanalModel = new RegistroClaseSemanalModel();
                $comprobacion = $registroClaseSemanalModel->actualizarCampoDuracionBruto($idRegistro, $duracionBruto);
                if ($comprobacion) {
                    // Respuesta JSON de éxito
                    echo json_encode(['success' => true, 'message' => 'Campo duracionBruto actualizado.', 'estado' => "",]);
                } else {
                    // Respuesta JSON de error
                    echo json_encode(['success' => false, 'message' => 'Error al actualizar campo duracionBruto.']);
                }
            } else {
                // Respuesta JSON de error
                echo json_encode(['success' => false, 'message' => 'El tiempo debe ser en formato 00:00:00']);
            }
        } else {
            ViewController::showError(403);
        }
    }
    public function actualizarCampoObservacionesEditor() //actualizar campo observaciones editor/trimador
    {
        if (Authentication::isAdmPospro() || Authentication::isPosproduccion()) {
            // Obtener los datos enviados en el body (en formato JSON)
            $data = json_decode(file_get_contents('php://input'), true);
            $idRegistro = $data['idRegistro'];
            $observaciones = $data['observacionesEditor'];
            $registroClaseSemanalModel = new RegistroClaseSemanalModel();
            $comprobacion = $registroClaseSemanalModel->actualizarCampoObservacionesEditor($idRegistro, $observaciones);
            if ($comprobacion) {
                // Respuesta JSON de éxito
                echo json_encode(['success' => true, 'message' => 'Campo observacionesEditor actualizado.', 'estado' => "",]);
            } else {
                // Respuesta JSON de error
                echo json_encode(['success' => false, 'message' => 'Error al actualizar campo observacionesEditor.']);
            }
        } else {
            ViewController::showError(403);
        }
    }
    public function cambiarEstado()
    {
        if (Authentication::isAdmPospro() || Authentication::isPosproduccion() || Authentication::isAdmin()) {
            $idRegistro = $_GET['idRegistro'];

            $estado = $_POST['estado'];

            $registroClaseSemanalModel = new RegistroClaseSemanalModel();
            $registroClaseSemanalModel->cambiarEstado($idRegistro, $estado);

            //ViewController::show("views/registroClaseSemanal/semanaActual");
            header("Location: " . PARAMETERS::$BASE_URL . "registroClaseSemanal/getSemanaActual");
            exit();
        } else {
            ViewController::showError(403);
        }
    }
    public static function validarTiempo($duracion)//hh:mm:ss
    {
        if (preg_match('/^(\d{1,2}):([0-5]?\d):([0-5]?\d)$/', $duracion, $matches)) {
            $minutos = (int)$matches[2];
            $segundos = (int)$matches[3];

            if ($minutos >= 0 && $minutos <= 59 && $segundos >= 0 && $segundos <= 59) {
                return true;
            }
        }
        return false;
    }
    public function borrarRegistro()
    {

        if (Authentication::isAdmin() || Authentication::isAdmTecnico()) {
            $idRegistro = $_GET["idRegistro"];
            $registroClaseSemanalModel = new RegistroClaseSemanalModel();

            $comprobacion = $registroClaseSemanalModel->getOneRegistro($idRegistro);
            if ($comprobacion) {
                $registroClaseSemanalModel->borrarRegistro($idRegistro);

                $_SESSION['mensaje'] = "Registro eliminado correctamente";
                header("Location: " . PARAMETERS::$BASE_URL . "registroClaseSemanal/getSemanaActual");
                exit();
            } else {
                $_SESSION['errores'][] = "El registro no existe";
                header("Location: " . PARAMETERS::$BASE_URL . "registroClaseSemanal/getSemanaActual");
                exit();
            }
        } else {
            ViewController::showError(403);
        }
    }
    public function editarRegistroForm()
    {
        if (Authentication::isAdmin() || Authentication::isAdmTecnico()) {
            $registroClaseSemanalModel = new RegistroClaseSemanalModel();

            if (isset($_GET["idRegistro"])) {

                $id = $_GET["idRegistro"];

                $existe = $registroClaseSemanalModel->getOneRegistro($id);

                $dia = $existe->dia;

                if ($existe) {
                    $tecnicoModel = new TecnicoModel;
                    $trimadoresModel = new PersonalPosproModel;
                    $estados = $registroClaseSemanalModel->getDISTINCTEstado();
                    $trimadores = $trimadoresModel->getAll();
                    $tecnicos = $tecnicoModel->getAll();

                    ViewController::show("views/registroClaseSemanal/editarRegistroForm.php", [
                        'registro' => $existe,
                        'tecnicos' => $tecnicos,
                        'trimadores' => $trimadores,
                        'estados' => $estados,
                        'dia' => $dia
                    ]);
                    exit();
                } else {
                    $_SESSION['errores'][] = "El registro no existe";
                    header("Location: " . PARAMETERS::$BASE_URL . "registroClaseSemanal/getSemanaActual");
                    exit();
                }
            } else {
                $_SESSION['errores'][] = "No se ha podido actualizar el registro";
                header("Location: " . PARAMETERS::$BASE_URL . "registroClaseSemanal/getSemanaActual");
                exit();
            }
        } else {
            ViewController::showError(403);
        }
    }
    public function editarRegistro()
    {
        if (Authentication::isAdmin() || Authentication::isAdmTecnico()) {
            $registroClaseSemanalModel = new RegistroClaseSemanalModel();
            if (isset($_GET["idRegistro"])) {
                $id = $_GET["idRegistro"];
               
                $tecnico = (empty($_POST['tecnico'])) ? null : $_POST['tecnico'];
                if ($tecnico == 0) {
                    $tecnico = null;
                }
              
                $trimador = (empty($_POST['trimador'])) ? null : $_POST['trimador'];
                $editor = (empty($_POST['editor'])) ? null : $_POST['editor'];
                $grabado = $_POST['grabado'] ?? null;
                $bruto = $_POST['bruto'] ?? null;
                $observacionesTecnico = $_POST['observacionesTecnico'] ?? null;
                $duracionBruto = $_POST['duracionBruto'] ?? null;
                $observacionesEditorTrimador = $_POST['observacionesEditorTrimador'] ?? null;
                $estado = $_POST['estado'] ?? null;
                if (preg_match("/^([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $duracionBruto) || empty($duracionBruto)) {
                    echo "La duración es válida: " . $duracionBruto;
                } else {
                    $_SESSION['errores'][] = "La duración no es válida. El formato debe ser HH:mm:ss.";
                    header("Location: " .Parameters::$BASE_URL . "RegistroClaseSemanal/editarRegistroForm?idRegistro=". $id);
                    exit();
                }

                $existe = $registroClaseSemanalModel->getOneRegistro($id);

                if ($existe) {
                    $resultado = $registroClaseSemanalModel->modificarRegistro($id, $tecnico, $grabado, $bruto, $observacionesTecnico, $duracionBruto, $editor, $trimador, $observacionesEditorTrimador, $estado);
                    if ($resultado) {
                        $_SESSION['mensaje'] = "Registro modificado correctamente";
                        header("Location: " . PARAMETERS::$BASE_URL . "registroClaseSemanal/getSemanaActual");
                        exit();
                    } else {
                        $_SESSION['errores'][] = "El registro no existe";
                        header("Location: " . PARAMETERS::$BASE_URL . "registroClaseSemanal/getSemanaActual");
                        exit();
                    }
                } else {
                    $_SESSION['errores'][] = "No se ha podido actualizar el registro";
                    header("Location: " . PARAMETERS::$BASE_URL . "registroClaseSemanal/getSemanaActual");
                    exit();
                }
            }
        } else {
            ViewController::showError(403);
        }
    }
}
