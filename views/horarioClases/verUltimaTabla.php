<?php

use admin\gestionDeClases\Helpers\Authentication;
use admin\gestionDeClases\Config\Parameters;

$semanaActual = $data['semanaActual'];
$total_paginas = $data['total_paginas'] ?? NULL;
$actual = $data['actual'] ?? NULL;;

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
    <article id="header-contenido">
        <h2>Horario Clases</h2>
        <div class="botones-header-contenido">
            <?php if (Authentication::isAdmin()) { ?>

                <a href="<?= Parameters::$BASE_URL . "RegistroClaseSemanal/mostrarFormularioClaseSemanal" ?>">
                    <button class="btn-header-contenido" id="registrarUsuariosBtn"><span class="material-symbols-outlined">add</span>Crear Nueva Semana </button>
                </a>
            <?php } ?>
        </div>
    </article>
    <article class="tabla-horarioClases">
        <div class="fila fila1">
            <div class="celda">Tipo Titulacion</div>
            <div class="celda">Dia</div>
            <div class="celda">Hora</div>
            <div class="celda">Tipo PAD</div>
            <div class="celda">Codigo</div>
            <div class="celda">Asignatura</div>
            <div class="celda">Profesor</div>
            <div class="celda">Aula</div>
            <div class="celda">Titulacion</div>
            <div class="celda">Semestre</div>
            <div class="celda">Curso</div>
            <div class="celda">Acciones</div>
        </div>
        <?php foreach ($semanaActual as $row) { ?>
            <div class="fila fila2 <?php if ($row->estadoHorario == 0) {
                                        echo 'eliminado';
                                    } ?>">
                <div class="celda"><?= $row->tipoTitulacion ?></div>
                <div class="celda"><?= $row->dia ?></div>
                <div class="celda"><?= $row->hora ?></div>
                <div class="celda"><?= $row->tipoPAD ?></div>
                <div class="celda"><?= $row->codigoPAD ?></div>
                <div class="celda"><?= $row->asignatura ?></div>
                <div class="celda"><?= $row->profesor ?></div>
                <div class="celda"><?= $row->aula ?></div>
                <div class="celda"><?= $row->titulacion ?></div>
                <div class="celda"><?= $row->semestre ?></div>
                <div class="celda"><?= $row->curso ?></div>
                <div class="celda acciones">
                    <?php if ($row->estadoHorario == 1) { ?>
                        <a href="<?= Parameters::$BASE_URL . "HorarioClases/cambiarEstadoHorario?idHorario=" . $row->idHorario ?>">
                            <span class="material-symbols-outlined activo">toggle_on</span>
                        </a>
                    <?php } ?>
                    <?php if ($row->estadoHorario == 0) { ?>
                        <a href="<?= Parameters::$BASE_URL . "HorarioClases/cambiarEstadoHorario?idHorario=" . $row->idHorario ?>">
                            <span class="material-symbols-outlined baja">toggle_off</span>
                        </a>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </article>

    <?php
    // Paginación
    $pintar = "enlace";
    $anterior = ($actual == 1) ? "desactivar" : "enlace";
    $siguiente = ($actual == $total_paginas) ? "desactivar" : "enlace";

    ?>
    <nav class="nav-pagination" aria-label="Page navigation example">
        <div class="pagination">
            <!-- Enlace de página anterior (con doble flecha) -->
            <p class="page-item">
                <a class="page-link <?= $anterior ?>" href='<?= Parameters::$BASE_URL ?>HorarioClases/verTabla?n=1' aria-label="Previous">
                    <span class="material-symbols-outlined" aria-hidden="true">keyboard_double_arrow_left</span>
                </a>
            </p>

            <!-- Enlace de página anterior (con una sola flecha) -->
            <p class="page-item">
                <a class="page-link <?= $anterior ?>" href='<?= Parameters::$BASE_URL ?>HorarioClases/verTabla?n=<?= max(1, $actual - 1) ?>' aria-label="Previous">
                    <span class="material-symbols-outlined" aria-hidden="true">chevron_left</span>
                </a>
            </p>

            <?php
            // Limitar las páginas mostradas
            $start_page = max(1, $actual - 5);  // Mostrar 5 páginas antes de la página actual
            $end_page = min($total_paginas, $actual + 5);  // Mostrar 5 páginas después de la página actual

            for ($i = $start_page; $i <= $end_page; $i++) {
                $activeClass = ($i == $actual) ? 'active' : ''; // Resaltar la página actual
            ?>
                <p class="page-item page-number <?= $activeClass ?>">
                    <a class="page-link" href='<?= Parameters::$BASE_URL ?>HorarioClases/verTabla?n=<?= $i ?>'><?= $i ?></a>
                </p>
            <?php
            }
            ?>

            <!-- Enlace de página siguiente (con una sola flecha) -->
            <p class="page-item">
                <a class="page-link <?= $siguiente ?>" href='<?= Parameters::$BASE_URL ?>HorarioClases/verTabla?n=<?= min($total_paginas, $actual + 1) ?>' aria-label="Next">
                    <span class="material-symbols-outlined" aria-hidden="true">chevron_right</span>
                </a>
            </p>

            <!-- Enlace de página siguiente (con doble flecha) -->
            <p class="page-item">
                <a class="page-link <?= $siguiente ?>" href='<?= Parameters::$BASE_URL ?>HorarioClases/verTabla?n=<?= $total_paginas ?>' aria-label="Next">
                    <span class="material-symbols-outlined" aria-hidden="true">keyboard_double_arrow_right</span>
                </a>
            </p>
        </div>
    </nav>

</section>