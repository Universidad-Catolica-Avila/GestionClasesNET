<?php

use admin\gestionDeClases\Config\Parameters;
use admin\gestionDeClases\Helpers\Authentication;

$semanaActual = $data['semanaActual'];
$tecnicos = $data['tecnicos'];
$editores = $data['editores'];
$trimadores = $data['trimadores'];

$total_paginas = $data['total_paginas'] ?? NULL;
$actual = $data['actual'] ?? NULL;
$Alltipos = $data['Alltipos'] ?? NULL;
$dias = $data['dias'] ?? NULL;
$AlltiposPAD = $data['AlltiposPAD'] ?? NULL;
$filtroTipo = $data['filtroTipo'] ?? NULL;
$filtroDia = $data['filtroDia'] ?? NULL;
$filtroTipoPAD = $data['filtroTipoPAD'] ?? NULL;
$filtroCodigo = $data['filtroCodigo'] ?? NULL;
$filtroGrabado = $data['filtroGrabado'] ?? NULL;

$semanas = $data['semanas'] ?? NULL;

$userentity = $_SESSION['user'];

$semanaInicio = $_SESSION['filtros']['fechaInicio'];

if (isset($_SESSION['errores'])) {
    echo "<div class='errores-container'>";
    foreach ($_SESSION['errores'] as $error) {
        echo "<p class='error'>$error</p>"; // Muestra cada mensaje de error
    }
    echo "</div>";
    unset($_SESSION['errores']); // Limpiar los errores después de mostrarlos
}

if (isset($_SESSION['mensaje'])) {
    echo "<div id='mensaje-temporal' >{$_SESSION['mensaje']}</div>";
    unset($_SESSION['mensaje']);
}

?>
<section class="contenido">
    <article id="header-contenido-semanaActual">
        <h2>Semana Actual</h2>
        <form action="<?= Parameters::$BASE_URL . "RegistroClaseSemanal/getSemanaActual" ?>" method="post"
            id="seleccionarSemanaForm">
            <select name="fechaInicio" id="seleccionarSemana">
                <option value="" disabled selected>Selecciona una semana</option>
                <?php foreach ($semanas as $semana) {
                    // Verificar si la semana actual coincide con la opción
                    $selected = ($semana->semanaInicio === $semanaInicio) ? 'selected' : '';
                    echo '<option value="' . htmlspecialchars($semana->semanaInicio) . '" ' . $selected . '>' . htmlspecialchars($semana->semanaInicio) . '</option>';
                } ?>
            </select>
        </form>
    </article>
    <article class="filtro">
        <form id="filtro-formulario" action="<?= Parameters::$BASE_URL . "RegistroClaseSemanal/getSemanaActual" ?>"
            method="POST">
            <select name="filtro-tipo" id="filtro-tipo">
                <option value="" disabled selected hidden>Buscar por tipo</option>
                <?php foreach ($Alltipos as $tipo) { ?>
                    <option value="<?= $tipo->tipoTitulacion ?>"><?= $tipo->tipoTitulacion ?></option>
                <?php } ?>
            </select>
            <select name="filtro-dia" id="filtro-dia">
                <option value="" disabled selected hidden>Buscar por dia</option>
                <?php foreach ($dias as $dia) { ?>
                    <option value="<?= $dia->dia ?>"><?= $dia->dia ?></option>
                <?php } ?>
            </select>
            <select name="filtro-tipoPAD" id="filtro-tipoPAD">
                <option value="" disabled selected hidden>Buscar tipoPAD</option>
                <?php foreach ($AlltiposPAD as $tipoPAD) { ?>
                    <option value="<?= $tipoPAD->tipoPAD ?>"><?= $tipoPAD->tipoPAD ?></option>
                <?php } ?>
            </select>
            <input type="text" name="filtro-codigo" id="filtro-codigo" value="<?= htmlspecialchars($filtroCodigo) ?>"
                placeholder="Buscar por Codigo">
            <select name="filtro-Grabado" id="filtro-Grabado">
                <option value="" disabled selected hidden>Buscar por grabado</option>
                <option value="1">TRUE</option>
                <option value="0">FALSE</option>
            </select>
            <button type="submit" id="reset-filtro" name="reset-filtro">Reiniciar Filtros</button>
        </form>
    </article>
    <?php if (Authentication::isAdmin()) { ?>
        <article class="tabla tabla-semanaActual">
            <div class="fila fila1">
                <div class="celda-fixed">Tipo</div>
                <div class="celda2-fixed">Fecha</div>
                <div class="celda">Día</div>
                <div class="celda">Hora</div>
                <div class="celda">Tipo PAD</div>
                <div class="celda">Código</div>
                <div class="celda">Asignatura</div>
                <div class="celda">Profesor</div>
                <div class="celda">Aula</div>
                <div class="celda">Técnico</div>
                <div class="celda">Grabado</div>
                <div class="celda">Bruto</div>
                <div class="celda">Observaciones</div>
                <div class="celda">Editado</div>
                <div class="celda">Grabación</br>Bruto Borrado</div>
                <div class="celda">Duración</br>Bruto</div>
                <div class="celda">Editor</div>
                <div class="celda">Trimador</div>
                <div class="celda">Observaciones</div>
                <div class="celda">Estado</div>
                <div class="celda">Acciones</div>
            </div>
            <?php foreach ($semanaActual as $dato) { ?>

                <div class="fila fila2
                 <?php
                 if ($dato->estado == "No se edita") {
                     echo " estado-no-editado";
                 } elseif ($dato->estado == "Pendiente") {
                     echo " estado-pendiente";
                 } elseif ($dato->estado == "Tramitando") {
                     echo " estado-tramitando";
                 } elseif ($dato->estado == "Editada") {
                     echo " estado-editado";
                 } else {
                     echo " estado-pendiente";
                 }
                 ?>">
                    <div class="celda-fixed "><?= $dato->tipoTitulacion ?></div>
                    <div class="celda2-fixed "><?= $dato->fecha ?></div>
                    <div class="celda"><?= $dato->dia ?></div>
                    <div class="celda"><?= $dato->hora ?></div>
                    <div class="celda"><?= $dato->tipoPAD ?></div>
                    <div class="celda codigo"><?= $dato->codigoPAD ?></div>
                    <div class="celda"><?= $dato->asignatura ?></div>
                    <div class="celda"><?= $dato->profesor ?></div>
                    <div class="celda"><?= $dato->aula ?></div>
                    <div class="celda celda-entera">
                        <select name="tecnico" id="idTecnico-<?= $dato->idRegistro ?>" class="idTecnico select">
                            <option value="">Seleccionar Técnico</option>
                            <?php foreach ($tecnicos as $tecnico) {
                                if ($tecnico->idTecnico == $dato->tecnico) {
                                    echo "<option selected value=" . $tecnico->idTecnico . "> $tecnico->nombre</option>";
                                } else {
                                    echo "<option value=" . $tecnico->idTecnico . "> $tecnico->nombre</option>";
                                }
                            } ?>
                        </select>
                    </div>
                    <div class="celda">
                        <?php if ($dato->grabado == 1) {
                            echo ("TRUE");
                        } else {
                            echo ("FALSE");
                        } ?>
                    </div>
                    <div class="celda">
                        <?php if ($dato->bruto == 1) {
                            echo ("HECHO");
                        } else {
                            echo ("----");
                        } ?>
                    </div>
                    <div class="celda">
                        <?php if (is_null($dato->observacionesTecnico) || $dato->observacionesTecnico == '-') {
                            echo '-';

                        } else {
                            echo ($dato->observacionesTecnico);
                        } ?>
                    </div>
                    <div class="celda">
                        <?php if ($dato->editado == 1) {
                            echo ("TRUE");
                        } else {
                            echo ("FALSE");
                        } ?>
                    </div>
                    <div class="celda">
                        <?php if ($dato->grabacionBrutoBorrado == 1) {
                            echo ("TRUE");
                        } else {
                            echo ("FALSE");
                        } ?>
                    </div>
                    <div class="celda">
                        <?php if (is_null($dato->duracionBruto)) {
                            echo '-';
                        } else {
                            echo ($dato->duracionBruto);
                        } ?>
                    </div>
                    <div class="celda celda-entera">
                        <form
                            action="<?= Parameters::$BASE_URL . "registroClaseSemanal/insertarEditor?idRegistro=" . $dato->idRegistro ?>"
                            id="editorForm" method="post">
                            <select name="idEditor" id="<?= $dato->idRegistro ?>" class="idEditor select">
                                <option value="0">Seleccionar Editor</option>
                                <?php foreach ($editores as $editor) {
                                    if ($editor->idPersonal == $dato->editor) {
                                        echo "<option selected value=" . $editor->idPersonal . "> $editor->nombre</option>";
                                    } else {
                                        echo "<option value=" . $editor->idPersonal . "> $editor->nombre</option>";
                                    }
                                } ?>
                            </select>
                        </form>
                    </div>
                    <div class="celda celda-entera">
                        <form
                            action="<?= Parameters::$BASE_URL . "registroClaseSemanal/insertarTrimador?idRegistro=" . $dato->idRegistro ?>"
                            id="trimadorForm" method="post">
                            <select name="idTrimador" id="idTrimador-<?= $dato->idRegistro ?>" class="idTrimador select">
                                <option value="0">Seleccionar Trimador</option>
                                <?php foreach ($trimadores as $trimador) {
                                    if ($trimador->idPersonal == $dato->trimador) {
                                        echo "<option selected value=" . $trimador->idPersonal . "> $trimador->nombre</option>";
                                    } else {
                                        echo "<option value=" . $trimador->idPersonal . "> $trimador->nombre</option>";
                                    }
                                } ?>
                            </select>
                        </form>
                    </div>

                    <?php if (is_null($dato->observacionesEditorTrimador)) {
                        echo '<div class="celda">-</div>';
                    } else {
                        echo '<div class="celda">' . $dato->observacionesEditorTrimador . '</div>';
                    } ?>
                    <div class="celda celda-entera">
                        <form
                            action="<?= Parameters::$BASE_URL . "registroClaseSemanal/cambiarEstado?idRegistro=" . $dato->idRegistro ?>"
                            id="estadoForm" method="post">
                            <select name="estado" id="" class="select">
                                <option value="" disabled <?php if (!$dato->estado) {
                                    echo 'selected';
                                } ?> hidden>Selecciona el
                                    estado</option>
                                <option value="No se edita" <?php if ($dato->estado == 'No se edita') {
                                    echo 'selected';
                                } ?>>No
                                    se edita</option>
                                <option value="Pendiente" <?php if ($dato->estado == 'Pendiente') {
                                    echo 'selected';
                                } ?>>
                                    Pendiente</option>
                                <option value="Tramitando" <?php if ($dato->estado == 'Tramitando') {
                                    echo 'selected';
                                } ?>>
                                    Tramitando</option>
                                <option value="Editada" <?php if ($dato->estado == 'Editada') {
                                    echo 'selected';
                                } ?>>Editada
                                </option>
                            </select>
                        </form>
                    </div>
                    <div class="celda acciones">
                        <a
                            href="<?= Parameters::$BASE_URL . "RegistroClaseSemanal/editarRegistroForm?idRegistro=" . $dato->idRegistro ?>">
                            <span class="material-symbols-outlined">edit</span>
                        </a>
                        <a
                            href="<?= Parameters::$BASE_URL . "RegistroClaseSemanal/borrarRegistro?idRegistro=" . $dato->idRegistro ?>">
                            <span class="fa fa-trash"> </span>
                        </a>
                    </div>
                </div>
            <?php } ?>
        </article>
    <?php } ?>
    <!-- VISTA POSTPRODUCCION Y ADMINPRODUCCION -->
    <?php if (Authentication::isAdmPospro() || Authentication::isPosproduccion()) { ?>
        <article class="tabla tabla-semanaActual-pospro">
            <div class="fila fila1">
                <div class="celda-fixed">Tipo</div>
                <div class="celda2-fixed">Fecha</div>
                <div class="celda">Día</div>
                <div class="celda">Hora</div>
                <div class="celda">Tipo PAD</div>
                <div class="celda">Código</div>
                <div class="celda">Asignatura</div>
                <div class="celda">Profesor</div>
                <div class="celda">Aula</div>
                <div class="celda">Técnico</div>
                <div class="celda">Grabado</div>
                <div class="celda">Bruto</div>
                <div class="celda">Observaciones</div>
                <div class="celda">Editado</div>
                <div class="celda">Grabación</br>Bruto Borrado</div>
                <div class="celda">Duración Bruto</div>
                <div class="celda">Editor</div>
                <div class="celda">Trimador</div>
                <div class="celda">Observaciones </div>
                <div class="celda">Estado </div>
            </div>
            <?php foreach ($semanaActual as $dato) { ?>
                <?php if (($dato->editor == $idUsuario = $userentity->getId()) || ($dato->trimador == $idUsuario = $userentity->getId()) || (Authentication::isAdmPospro())) { ?>
                    <div id="fila-<?= $dato->idRegistro ?>" class="fila fila2
                        <?php
                        if ($dato->estado == "No se edita") {
                            echo "estado-no-editado";
                        } elseif ($dato->estado == "Pendiente") {
                            echo "estado-pendiente";
                        } elseif ($dato->estado == "Tramitando") {
                            echo "estado-tramitando";
                        } elseif ($dato->estado == "Editada") {
                            echo "estado-editado";
                        } else {
                            echo "estado-pendiente";
                        }
                        ?>">
                        <div class="celda-fixed "><?= $dato->tipoTitulacion ?></div>
                        <div class="celda2-fixed "><?= $dato->fecha ?></div>
                        <div class="celda"><?= $dato->dia ?></div>
                        <div class="celda"><?= $dato->hora ?></div>
                        <div class="celda"><?= $dato->tipoPAD ?></div>
                        <div class="celda"><?= $dato->codigoPAD ?></div>
                        <div class="celda"><?= $dato->asignatura ?></div>
                        <div class="celda"><?= $dato->profesor ?></div>
                        <div class="celda"><?= $dato->aula ?></div>
                        <?php if (Authentication::isAdmPospro()) { ?>
                            <div class="celda celda-entera">
                                <select name="tecnico" id="idTecnico-<?= $dato->idRegistro ?>" class="idTecnico select">
                                    <option value="">Seleccionar Técnico</option>
                                    <?php foreach ($tecnicos as $tecnico) {
                                        if ($tecnico->idTecnico == $dato->tecnico) {
                                            echo "<option selected value=" . $tecnico->idTecnico . "> $tecnico->nombre</option>";
                                        } else {
                                            echo "<option value=" . $tecnico->idTecnico . "> $tecnico->nombre</option>";
                                        }
                                    } ?>
                                </select>
                            </div>
                        <?php } else {
                            foreach ($tecnicos as $tecnico) {
                                if (!$dato->tecnico) {
                                    echo '<div class="celda"> - </div>';
                                    break;
                                }
                                if ($dato->tecnico == $tecnico->idTecnico) {
                                    echo '<div class="celda">' . $tecnico->nombre . '</div>';
                                    break;
                                } ?>
                            <?php }
                        } ?>
                        <div class="celda">
                            <?php if ($dato->grabado == 1) {
                                echo ("TRUE");
                            } else {
                                echo ("FALSE");
                            } ?>
                        </div>
                        <div class="celda">
                            <?php if ($dato->bruto == 1) {
                                echo ("HECHO");
                            } else {
                                echo ("----");
                            } ?>
                        </div>
                        <div class="celda"><?= $dato->observacionesTecnico ?></div>

                        <!-- editado, grabacion/Bruto borrado -->
                        <?php if (($dato->editor == $idUsuario = $userentity->getId()) || ($dato->trimador == $idUsuario = $userentity->getId()) || (Authentication::isAdmPospro() && ($dato->editor != 0 || $dato->trimador != 0))) { ?>
                            <select class="celda editado" id="idEditado-<?= $dato->idRegistro ?>" name="editado">
                                <option value="" disabled selected hidden>Selecciona una opción</option>
                                <option value="1" <?php if ($dato->editado == '1') {
                                    echo 'selected';
                                } ?>>TRUE </option>
                                <option value="0" <?php if ($dato->editado == '0') {
                                    echo 'selected';
                                } ?>>FALSE </option>
                            </select>
                            <select class="celda grabacion" id="idgrabacion-<?= $dato->idRegistro ?>" name="grabacion">
                                <option value="" disabled selected hidden>Selecciona una opción</option>
                                <option value="1" <?php if ($dato->grabacionBrutoBorrado == '1') {
                                    echo 'selected';
                                } ?>>TRUE </option>
                                <option value="0" <?php if ($dato->grabacionBrutoBorrado == '0') {
                                    echo 'selected';
                                } ?>>FALSE </option>
                            </select>
                            <input type="text" class="celda duracionBruto" id="IdduracionBruto-<?= $dato->idRegistro ?>"
                                name="duracionBruto" placeholder="0:00:00" value="<?= $dato->duracionBruto ?>"></input>
                        <?php } else { ?>
                            <div class="celda editado">
                                <?php if ($dato->editado == 1) {
                                    echo ("TRUE");
                                } else {
                                    echo ("FALSE");
                                } ?>
                            </div>
                            <div class="celda">
                                <?php if ($dato->grabacionBrutoBorrado == 1) {
                                    echo ("TRUE");
                                } else {
                                    echo ("FALSE");
                                } ?>
                            </div>
                            <div class="celda">
                                <?php if (is_null($dato->duracionBruto)) {
                                    echo "00:00:00";
                                } else {
                                    $dato->duracionBruto;
                                } ?>
                            </div>
                        <?php } ?>
                        <?php if (Authentication::isAdmPospro()) { ?>
                            <div class="celda celda-entera">
                                <form
                                    action="<?= Parameters::$BASE_URL . "registroClaseSemanal/insertarEditor?idRegistro=" . $dato->idRegistro ?>"
                                    id="editorForm" method="post">
                                    <select name="idEditor" id="<?= $dato->idRegistro ?>" class="idEditor select">
                                        <option value="0">Seleccionar Editor</option>
                                        <?php foreach ($editores as $editor) {
                                            if ($editor->idPersonal == $dato->editor) {
                                                echo "<option selected value=" . $editor->idPersonal . "> $editor->nombre</option>";
                                            } else {
                                                echo "<option value=" . $editor->idPersonal . "> $editor->nombre</option>";
                                            }
                                        } ?>
                                    </select>
                                </form>
                            </div>
                            <div class="celda celda-entera">
                                <form
                                    action="<?= Parameters::$BASE_URL . "registroClaseSemanal/insertarTrimador?idRegistro=" . $dato->idRegistro ?>"
                                    id="trimadorForm" method="post">
                                    <select name="idTrimador" id="idTrimador-<?= $dato->idRegistro ?>" class="idTrimador select">
                                        <option value="0">Seleccionar Trimador</option>
                                        <?php foreach ($trimadores as $trimador) {
                                            if ($trimador->idPersonal == $dato->trimador) {
                                                echo "<option selected value=" . $trimador->idPersonal . "> $trimador->nombre</option>";
                                            } else {
                                                echo "<option value=" . $trimador->idPersonal . "> $trimador->nombre</option>";
                                            }
                                        } ?>
                                    </select>
                                </form>
                            </div>
                        <?php } else {
                            echo "<div class='celda'>";
                            $encontradoEditor = false;
                            foreach ($editores as $editor) {
                                if ($editor->idPersonal === $dato->editor) {
                                    echo $editor->nombre;
                                    $encontradoEditor = true;
                                    break;
                                }
                            }
                            if (!$encontradoEditor) {
                                echo "-";
                            }
                            echo "</div>";
                            echo "<div class='celda'>";
                            $encontradoTrimador = false;
                            foreach ($trimadores as $trimador) {
                                if ($trimador->idPersonal === $dato->trimador) {
                                    echo $trimador->nombre;
                                    $encontradoTrimador = true;
                                    break;
                                }
                            }
                            if (!$encontradoTrimador) {
                                echo "-";
                            }
                            echo "</div>";
                        } ?>
                        <?php if (($dato->editor == $idUsuario = $userentity->getId()) || ($dato->trimador == $idUsuario = $userentity->getId()) || (Authentication::isAdmPospro() && ($dato->editor != 0 || $dato->trimador != 0))) { ?>
                            <input type="text" class="celda observacionesEditor" id="idObservacionesEditor-<?= $dato->idRegistro ?>"
                                name="observacionesEditor" value="<?= $dato->observacionesEditorTrimador ?>"
                                placeholder="observaciones editor/trimador "></input>
                        <?php } else { ?>
                            <div class="celda Observaciones"><?= $dato->observacionesEditorTrimador ?></div>
                        <?php } ?>

                        <?php if (($dato->editor == $idUsuario = $userentity->getId()) || ($dato->trimador == $idUsuario = $userentity->getId()) || (Authentication::isAdmPospro() && ($dato->editor != 0 || $dato->trimador != 0))) { ?>
                            <div class="celda celda-entera">
                                <form
                                    action="<?= Parameters::$BASE_URL . "registroClaseSemanal/cambiarEstado?idRegistro=" . $dato->idRegistro ?>"
                                    id="estadoForm" method="post">
                                    <select name="estado" id="estadoSelect">
                                        <option value="" disabled <?php if (!$dato->estado) {
                                            echo 'selected';
                                        } ?> hidden>Selecciona el
                                            estado</option>
                                        <option value="No se edita" <?php if ($dato->estado == 'No se edita') {
                                            echo 'selected';
                                        } ?>>No
                                            se edita</option>
                                        <option value="Pendiente" <?php if ($dato->estado == 'Pendiente') {
                                            echo 'selected';
                                        } ?>>
                                            Pendiente</option>
                                        <option value="Tramitando" <?php if ($dato->estado == 'Tramitando') {
                                            echo 'selected';
                                        } ?>>
                                            Tramitando</option>
                                        <option value="Editada" <?php if ($dato->estado == 'Editada') {
                                            echo 'selected';
                                        } ?>>Editada
                                        </option>
                                    </select>
                                </form>
                            </div>
                        <?php } else { ?>
                            <div class="celda"><?= $dato->estado ?></div>
                        <?php } ?>
                    </div>
                <?php } ?>
            <?php } ?>
        </article>
    <?php } ?>

    <!--VISTA TECNICO  -->
    <?php if (Authentication::isAdmTecnico()) { ?>
        <article class="tabla tabla-semanaActual-Admtecnico">
        <?php } ?>
        <?php if (Authentication::isTecnico()) { ?>
            <article class="tabla tabla-semanaActual-tecnico">
            <?php } ?>
            <?php if (Authentication::isTecnico() || Authentication::isAdmTecnico()) { ?>
                <div class="fila fila1">
                    <div class="celda-fixed">Tipo</div>
                    <div class="celda2-fixed">Fecha</div>
                    <div class="celda">Día</div>
                    <div class="celda">Hora</div>
                    <div class="celda">Tipo PAD</div>
                    <div class="celda">Código</div>
                    <div class="celda">Asignatura</div>
                    <div class="celda">Profesor</div>
                    <div class="celda">Aula</div>
                    <div class="celda">Técnico</div>
                    <div class="celda">Grabado</div>
                    <div class="celda">Bruto</div>
                    <div class="celda">Observaciones</div>
                    <!-- <div class="celda">Estado</div> -->
                    <?php if (Authentication::isAdmTecnico()) {
                        echo '<div class="celda">Acciones</div>';
                    } ?>

                </div>
                <?php foreach ($semanaActual as $dato) { ?>
                    <?php if (($dato->tecnico == $idUsuario = $userentity->getId()) || (Authentication::isAdmTecnico())) { ?>
                        <div class="fila fila2
                    <?php
                    if ($dato->estado == "No se edita") {
                        echo "estado-no-editado";
                    } elseif ($dato->estado == "Pendiente") {
                        echo "estado-pendiente";
                    } elseif ($dato->estado == "Tramitando") {
                        echo "estado-tramitando";
                    } elseif ($dato->estado == "Editada") {
                        echo "estado-editado";
                    } else {
                        echo "estado-pendiente";
                    }
                    ?>">
                            <div class="celda celda-fixed"><?= $dato->tipoTitulacion ?></div>
                            <div class="celda celda2-fixed"><?= $dato->fecha ?></div>
                            <div class="celda"><?= $dato->dia ?></div>
                            <div class="celda"><?= $dato->hora ?></div>
                            <div class="celda"><?= $dato->tipoPAD ?></div>
                            <div class="celda"><?= $dato->codigoPAD ?></div>
                            <div class="celda"><?= $dato->asignatura ?></div>
                            <div class="celda"><?= $dato->profesor ?></div>
                            <div class="celda"><?= $dato->aula ?></div>
                            <?php if (Authentication::isAdmTecnico()) { ?>
                                <div class="celda">
                                    <select name="tecnico" id="idTecnico-<?= $dato->idRegistro ?>" class="idTecnico select">
                                        <option value="">Seleccionar Técnico</option>
                                        <?php foreach ($tecnicos as $tecnico) {
                                            if ($tecnico->idTecnico == $dato->tecnico) {
                                                echo "<option selected value=" . $tecnico->idTecnico . "> $tecnico->nombre</option>";
                                            } else {
                                                echo "<option value=" . $tecnico->idTecnico . "> $tecnico->nombre</option>";
                                            }
                                        } ?>
                                    </select>
                                </div>
                            <?php } else {
                                foreach ($tecnicos as $tecnico) {
                                    if (!$dato->tecnico) {
                                        echo '<div class="celda"> - </div>';
                                        break;
                                    }
                                    if ($dato->tecnico == $tecnico->idTecnico) {
                                        echo '<div class="celda">' . $tecnico->nombre . '</div>';
                                        break;
                                    }
                                } ?>

                            <?php } ?>
                            <?php if (($dato->tecnico == $idUsuario = $userentity->getId()) || (Authentication::isAdmTecnico() && $dato->tecnico != 0)) { ?>
                                <div class="celda">
                                    <select class="grabado" id="idGrabado-<?= $dato->idRegistro ?>" name="grabado">
                                        <?php if ($dato->grabado == 1) { ?>
                                            <option value="1" selected>TRUE</option>
                                            <option value="0">FALSE</option>
                                        <?php } else { ?>
                                            <option value="1">TRUE</option>
                                            <option value="0" selected>FALSE</option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="celda">
                                    <select class="bruto" id="idBruto-<?= $dato->idRegistro ?>" name="bruto">
                                        <option value="" disabled selected>Selecciona una opción</option>
                                        <?php if ($dato->bruto == 1) { ?>
                                            <option value="0">----</option>
                                            <option value="1" selected>Hecho</option>
                                        <?php } else { ?>
                                            <option value="0" selected>----</option>
                                            <option value="1">Hecho</option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <input type="text" class="celda observacionesTecnico"
                                    id="idObservacionesTecnico-<?= $dato->idRegistro ?>" name="observacionesTecnico"
                                    value="<?= $dato->observacionesTecnico ?>"></input>
                                <!-- <div class='celda'><?= $dato->estado ?></div> -->
                                <?php if (Authentication::isAdmTecnico()) { ?>
                                    <div class="celda acciones">
                                        <a
                                            href="<?= Parameters::$BASE_URL . "RegistroClaseSemanal/editarRegistroForm?idRegistro=" . $dato->idRegistro ?>">
                                            <span class="material-symbols-outlined">edit</span>
                                        </a>
                                        <a href="<?= Parameters::$BASE_URL . "RegistroClaseSemanal/borrarRegistro?idRegistro=" . $dato->idRegistro ?>"
                                            id="aBorrarRegistro">
                                            <span class="fa fa-trash"> </span>
                                        </a>
                                    </div>
                                <?php } ?>
                            <?php } else { ?>
                                <div class="celda">
                                    <?php if ($dato->grabado == 1) {
                                        echo ("TRUE");
                                    } else {
                                        echo ("FALSE");
                                    } ?>
                                </div>
                                <div class="celda">
                                    <?php if ($dato->bruto == 1) {
                                        echo ("HECHO");
                                    } else {
                                        echo ("----");
                                    } ?>
                                </div>
                                <div class="celda"><?= $dato->observacionesTecnico ?></div>

                                <!-- <?php if ($dato->estado === NULL) {
                                    echo "<div class='celda'>-</div>";
                                } else {
                                    echo "<div class='celda'>$dato->estado</div>";
                                } ?> -->

                                <?php if (Authentication::isAdmTecnico()) { ?>
                                    <div class="celda acciones">
                                        <a
                                            href="<?= Parameters::$BASE_URL . "RegistroClaseSemanal/editarRegistroForm?idRegistro=" . $dato->idRegistro ?>">
                                            <span class="material-symbols-outlined">edit</span>
                                        </a>
                                        <a href="<?= Parameters::$BASE_URL . "RegistroClaseSemanal/borrarRegistro?idRegistro=" . $dato->idRegistro ?>"
                                            id="aBorrarRegistro">
                                            <span class="fa fa-trash"> </span>
                                        </a>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php } ?>
            </article>
        <?php }
            //Paginación
            $pintar = "enlace";
            $anterior = $actual == 1 ? "desactivar" : "enlace";
            $siguiente = $actual == $total_paginas ? "desactivar" : "enlace";

            ?>
        <article class="leyenda">
            <div>
                <p>Pendiente:</p>
                <span class="leyenda-pendiente material-symbols-outlined">radio_button_unchecked</span>
            </div>
            <div>
                <p>No se edita:</p>
                <span class="leyenda-noEditado material-symbols-outlined">radio_button_unchecked</span>
            </div>
            <div>
                <p>Tramitando:</p>
                <span class="leyenda-tramitando material-symbols-outlined">radio_button_unchecked</span>
            </div>
            <div>
                <p>Editado:</p>
                <span class="leyenda-editado material-symbols-outlined">radio_button_unchecked</span>
            </div>
        </article>
        <nav class="nav-pagination" aria-label="Page navigation example">
            <div class="pagination">
                <!-- Botón de primera página -->
                <p class="page-item">
                    <a class="page-link <?= $anterior ?>"
                        href="<?= Parameters::$BASE_URL ?>RegistroClaseSemanal/getSemanaActual?n=1"
                        aria-label="Previous">
                        <span class="material-symbols-outlined" aria-hidden="true">keyboard_double_arrow_left</span>
                    </a>
                </p>

                <!-- Botón de página anterior -->
                <p class="page-item prev">
                    <a class="page-link <?= $anterior ?>"
                        href="<?= Parameters::$BASE_URL ?>RegistroClaseSemanal/getSemanaActual?n=<?= max(1, $actual - 1) ?>"
                        aria-label="Previous">
                        <span class="material-symbols-outlined" aria-hidden="true">chevron_left</span>
                    </a>
                </p>

                <?php
                // Limitar las páginas mostradas alrededor de la página actual
                $start_page = max(1, $actual - 5);
                $end_page = min($total_paginas, $actual + 5);

                for ($i = $start_page; $i <= $end_page; $i++) {
                    $activeClass = ($i == $actual) ? 'active' : ''; // Resaltar la página actual
                    ?>
                    <p class="page-item page-number <?= $activeClass ?>">
                        <a class="page-link"
                            href="<?= Parameters::$BASE_URL ?>RegistroClaseSemanal/getSemanaActual?n=<?= $i ?>"><?= $i ?></a>
                    </p>
                    <?php
                }
                ?>

                <!-- Botón de página siguiente -->
                <p class="page-item next">
                    <a class="page-link <?= $siguiente ?>"
                        href="<?= Parameters::$BASE_URL ?>RegistroClaseSemanal/getSemanaActual?n=<?= min($total_paginas, $actual + 1) ?>"
                        aria-label="Next">
                        <span class="material-symbols-outlined" aria-hidden="true">chevron_right</span>
                    </a>
                </p>

                <!-- Botón de última página -->
                <p class="page-item">
                    <a class="page-link <?= $siguiente ?>"
                        href="<?= Parameters::$BASE_URL ?>RegistroClaseSemanal/getSemanaActual?n=<?= $total_paginas ?>"
                        aria-label="Next">
                        <span class="material-symbols-outlined" aria-hidden="true">keyboard_double_arrow_right</span>
                    </a>
                </p>
            </div>
        </nav>

</section>