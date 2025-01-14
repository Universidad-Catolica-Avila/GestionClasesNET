<?php
	use admin\gestionDeClases\Config\Parameters;
	use admin\gestionDeClases\Helpers\Authentication;
	use admin\gestionDeClases\Helpers\ErrorHelpers;

    $dataPOST = $data["dataPOST"]??NULL;
    $validationsError = $data["validationsError"]??NULL;
    $registro = $data["registro"]??NULL;
    $tecnicos = $data["tecnicos"]??NULL;
    $trimadores = $data["trimadores"]??NULL;
    $dia = $data["dia"]??NULL;

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
    <div class="contenido container-editarRegistro">
        <?php if(Authentication::isAdmin() || Authentication::isAdmTecnico()){ ?>
            <form class="formulario-editarRegistro" id="formRegistrarClase" action="<?=Parameters::$BASE_URL . "RegistroClaseSemanal/editarRegistro?idRegistro=" . $registro->idRegistro?>" method="post">

        <h2>Editar Registro</h2>
        <p>
            <label for="tecnico">Tecnico:</label>
            <select name="tecnico" id="tecnico">
                <option value="0">Sin selectionar</option>
            <?php foreach ($tecnicos as $tecnico) { ?>    
                        <option value="<?=$tecnico->idTecnico ?>" <?php if ($registro->tecnico == $tecnico->idTecnico) { echo 'selected'; } ?>><?=$tecnico->nombre ?></option>
                        
                    <?php } ?>
            </select>
        </p>
        <p>
            <label for="grabado">Grabado:</label>
            <select name="grabado" id="grabado">
                <option value="0" <?php if($registro->grabado == "0") {echo 'selected';} ?>>FALSE</option>
                <option value="1" <?php if($registro->grabado == "1") {echo 'selected';} ?>>TRUE</option>
            </select>
        </p>
        <p>
        <label for="bruto">Bruto:</label>
            <select name="bruto" id="bruto">
            <option value="0" <?php if($registro->bruto == "0") {echo 'selected';} ?>>FALSE</option>
            <option value="1" <?php if($registro->bruto == "1") {echo 'selected';} ?>>TRUE</option>
            </select>
        </p>
        <p>
            <label for="observacionesTecnico">observacionesTecnico:</label>
            <input type="text" name="observacionesTecnico" id="observacionesTecnico" value="<?=$registro->observacionesTecnico?>" />
        </p>
        <p>
            <label for="duracionBruto">duracionBruto:</label>
            <input type="text" name="duracionBruto" id="duracionBruto" value="<?=$registro->duracionBruto?>" />
        </p>
        <p>
            <label for="editor">Editor:</label>
            <select name="editor" id="editor">
            <option value="">Sin selectionar</option>
            <?php foreach ($trimadores as $editor) { ?>    
                        <option value="<?=$editor->idPersonal ?>" <?php if ($registro->editor == $editor->idPersonal) { echo 'selected'; } ?>><?=$editor->nombre ?></option>
                    <?php } ?>

            </select>
        </p>
        <p>
            <label for="trimador">Trimador:</label>
            <select name="trimador" id="trimador">
            <option value="">Sin selectionar</option>
            <?php foreach ($trimadores as $trimador) { ?>    
                        <option value="<?=$trimador->idPersonal ?>"<?php if ($registro->trimador == $trimador->idPersonal) { echo 'selected'; } ?>><?=$trimador->nombre ?></option>
                    <?php } ?>

            </select>
        </p>
        <p>
            <label for="observacionesEditorTrimador">observacionesEditorTrimador:</label>
            <input type="text" name="observacionesEditorTrimador" id="observacionesEditorTrimador" value="<?=$registro->observacionesEditorTrimador?>" />
        </p>
        <p>
            <label for="estado">Estado:</label>
            <select name="estado" id="" class="select">
                <option value="No se edita" <?php if ($registro->estado == 'No se edita') { echo 'selected'; } ?>>No se edita</option>
                <option value="Pendiente" <?php if ($registro->estado == 'Pendiente') { echo 'selected'; } ?>>Pendiente</option>
                <option value="Tramitando" <?php if ($registro->estado == 'Tramitando') { echo 'selected'; } ?>>Tramitando</option>
                <option value="Editada" <?php if ($registro->estado == 'Editada') { echo 'selected'; } ?>>Editada</option>
            </select>
        </p>
            
        <button type='submit' name='btnRegistro' value='Confirmar'>Confirmar</button>
        </form>
        <?php } ?>
    </div>
<?php
		ErrorHelpers::clearAll();
?>