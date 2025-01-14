<?php

use admin\gestionDeClases\Helpers\Authentication;
use admin\gestionDeClases\Config\Parameters;

$usuarios = $data['usuarios'];

if (isset($_SESSION['errores'])) {
    echo "<div class='errores-container'>";
    foreach ($_SESSION['errores'] as $error) {
        echo "<p class='error'>$error</p>"; // Muestra cada mensaje de error
    }
    echo "</div>";
    unset($_SESSION['errores']); // Limpiar los errores después de mostrarlos
}

if (isset($_SESSION['mensaje'])) {
    echo "<div id='mensaje-temporal' class='mensaje-temporal-centradoForm' >{$_SESSION['mensaje']}</div>";
    unset($_SESSION['mensaje']);
}

?>

<section class="contenido">
    <article id="header-contenido">
        <h2>Usuarios</h2>
        <div class="botones-header-contenido">
            <?php if (Authentication::isAdmin()) { ?>
                <a href="<?= Parameters::$BASE_URL . "Usuario/registrar" ?>">
                    <button class="btn-header-contenido" id="registrarUsuariosBtn"><span class="material-symbols-outlined">add</span>Registrar Usuarios</button>
                </a>
            <?php } ?>
        </div>
    </article>
    <article class="tabla tabla-todosUsuarios">
        <div class="fila fila1">
            <div class="celda">Nombre</div>
            <div class="celda">Email</div>
            <div class="celda">Rol</div>
            <div class="celda">Estado</div>
            <div class="celda">Fecha Baja</div>
            <div class="celda">Observaciones</div>
            <div class="celda">Acciones</div>
        </div> 
    <?php foreach ($usuarios as $usuario) { ?>
        <div class="fila fila2 <?php if($usuario->estado == 0){echo 'eliminado';} ?>">
            <div class="celda"><?=$usuario->nombre?></div>
            <div class="celda"><?=$usuario->email?></div>
            <div class="celda"><?=$usuario->rol?></div>
            <?php if ($usuario->estado == 1) {
                echo '<div class="celda activo">Activo</div>';  
            } else {
                echo '<div class="celda baja">Baja</div>';  
            } ?>
            <?php if ($usuario->fecha_baja == NULL) {
                echo '<div class="celda">-</div>';  
            } else {
                echo '<div class="celda">' . $usuario->fecha_baja . '</div>';
            } ?>
            <?php if ($usuario->observaciones == NULL) {
                echo '<div class="celda">-</div>';  
            } else {
                echo '<div class="celda">' . $usuario->observaciones . '</div>';
            } 

            ?>
            <div class="celda acciones">
                <a href="<?=Parameters::$BASE_URL . "Usuario/editarUsuario?idUsuario=" . $usuario->idUsuario?>">
                    <span class="material-symbols-outlined">edit</span>
                </a>
                <?php if ($usuario->estado == 1) { ?>
                    <a href="<?=Parameters::$BASE_URL . "Usuario/cambiarEstadoUsuario?idUsuario=" . $usuario->idUsuario?>">
                        <span class="material-symbols-outlined activo">toggle_on</span>
                    </a>
                <?php } ?>
                <?php if ($usuario->estado == 0) { ?>
                    <a href="<?=Parameters::$BASE_URL . "Usuario/cambiarEstadoUsuario?idUsuario=" . $usuario->idUsuario?>">
                        <span class="material-symbols-outlined baja">toggle_off</span>
                    </a>
                <?php } ?>
            </div>
        </div>        
    <?php } ?>
    
    </article>
</section>
