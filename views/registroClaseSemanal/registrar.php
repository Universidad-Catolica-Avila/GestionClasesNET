<?php
	use admin\gestionDeClases\Config\Parameters;
	use admin\gestionDeClases\Helpers\Authentication;
	use admin\gestionDeClases\Helpers\ErrorHelpers;

    $dataPOST = $data["dataPOST"]??NULL;
    $validationsError = $data["validationsError"]??NULL;
    $roles = $data["roles"]??NULL;
    
    if (isset($_SESSION['errores'])) {
        echo "<div class='errores-container-centradoForm'>";
        foreach ($_SESSION['errores'] as $error) {
            echo "<p class='error'>$error</p>";
        }
        echo "</div>";
        unset($_SESSION['errores']);
    }

    if (isset($_SESSION['mensaje'])) {
        echo "<div id='mensaje-temporal' >{$_SESSION['mensaje']}</div>";
        unset($_SESSION['mensaje']);
    }
?>
    <div class="contenido container-iniciar-sesion">
       
        <form class="formulario-iniciar-sesion" id="formRegistrarClase" action="<?= Parameters::$BASE_URL ?>RegistroClaseSemanal/registrarClaseSemanal" method="post">

            <h2>Registrar Nueva clase semanal</h2>
            <p>
                <label for="idCurso">Curso:</label>
                <select name="curso" id="idCurso">
                    <option value="2024">2024</option>
                </select>
            </p>

            <p>
                <label for="idSemestre">Semestre:</label>
                <select name="semestre" id="idSemestre">
                    <option value="1º semestre">1º Semestre</option>
                    <option value="2º semestre">2º Semestre</option>
                </select>
            </p>
            <p>
                <label for="idFechaInicio">Fecha de inicio (Lunes):</label>
                <input type="date" name="fechaInicio" id="idFechaInicio" value=""/>
                <span id="errorFechaInicio" class="error-register-span" style="display:none;"></span>
            </p>

            <p>
                <label for="idFechaFin">Fecha de fin (Domingo):</label>
                <input type="date" name="fechaFin" id="idFechaFin" />
                <span id="errorFechaFin" class="error-register-span" style="display:none;"></span>
            </p>         
            <button type="submit" name="btnRegistro" value="Registrar">Registrar</button>
        </form>
    </div>
<?php
	ErrorHelpers::clearAll();
?>
