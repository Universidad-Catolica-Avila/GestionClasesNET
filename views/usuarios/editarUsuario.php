<?php
	use admin\gestionDeClases\Config\Parameters;
	use admin\gestionDeClases\Helpers\Authentication;
	use admin\gestionDeClases\Helpers\ErrorHelpers;

    $dataPOST = $data["dataPOST"]??NULL;
    $validationsError = $data["validationsError"]??NULL;
    $roles = $data["roles"]??NULL;
    $usuario = $data["usuario"]??NULL;
    
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
    <div class="contenido container-iniciar-sesion">
        <?php if(Authentication::isAdmin()){ ?>
            <form class="formulario-iniciar-sesion" action="<?=Parameters::$BASE_URL . "Usuario/modificarUsuario?idUsuario=" . $usuario->idUsuario?>" method="post">
        <?php } ?>

        <h2>Editar Usuario</h2>
        <p>
            <label for="idNombre">Nombre*</label>
            <input type="text" name="nombre" value="<?=$usuario->nombre?>" id="idNombre" <?php if(isset($_SESSION['errores-span']) && isset($_SESSION['errores-span']['nombre'])){echo "class='error-register'";} ?>/>
            <span class="error-register-span">
            <?php if(isset($_SESSION['errores-span']) && isset($_SESSION['errores-span']['nombre'])) {
                echo $_SESSION['errores-span']['nombre'];
            } ?>
            </span>
        </p>

        <p>
            <label for="idEmail">Email*</label>
            <input type="email" name="email" value="<?=$usuario->email?>" id="idEmail" <?php if(isset($_SESSION['errores-span']) && isset($_SESSION['errores-span']['email'])){echo "class='error-register'";} ?> />
            <span class="error-register-span">
            <?php if(isset($_SESSION['errores-span']) && isset($_SESSION['errores-span']['email'])) {
                echo $_SESSION['errores-span']['email'];
            } ?>
            </span>
        </p>
        <p>
            <label for='idPassword'>Contraseña*</label>
            <input type='password' name='password' id='idPassword' <?php if(isset($_SESSION['errores-span']) && isset($_SESSION['errores-span']['contraseña'])){echo "class='error-register'";} ?> />
        </p>
        <p>
            <label for='idPassword2'>Repetir Contraseña*</label>
            <input type='password' name='password2' id='idPassword2' <?php if(isset($_SESSION['errores-span']) && isset($_SESSION['errores-span']['contraseña'])){echo "class='error-register'";} ?> />
            <span class="error-register-span">
            <?php if(isset($_SESSION['errores-span']) && isset($_SESSION['errores-span']['contraseña'])) {
                echo $_SESSION['errores-span']['contraseña'];
            } ?>
            </span>
        </p>
        <p>
        <label for="idRol">Rol*</label> 
            <select name="rol" id="" <?php if(isset($_SESSION['errores-span']) && isset($_SESSION['errores-span']['rol'])){echo "class='error-register'";} ?> >
                <option value="" disabled <?= empty($usuario->rol) ? 'selected' : '' ?>>Selecciona una opción</option>
                <option value="Técnico" <?= $usuario->rol == "Técnico" ? 'selected' : '' ?>>Técnico</option>
                <option value="Posproducción" <?= $usuario->rol == "Posproducción" ? 'selected' : '' ?>>Posproducción</option>
                <option value="AdmTecnico" <?= $usuario->rol == "AdmTecnico" ? 'selected' : '' ?>>AdmTecnico</option>
                <option value="AdmPospro" <?= $usuario->rol == "AdmPospro" ? 'selected' : '' ?>>AdmPospro</option>
            </select>
            <span class="error-register-span">
            <?php if(isset($_SESSION['errores-span']) && isset($_SESSION['errores-span']['rol'])) {
                echo $_SESSION['errores-span']['rol'];
            }?>
            </span>
        </p>
        <p>
            <label for='observaciones'>Observaciones</label>
            <input type='text' name='observaciones' value="<?=$usuario->observaciones?>" id='observaciones' <?php if(isset($_SESSION['errores-span']) && isset($_SESSION['errores-span']['observaciones'])){echo "class='error-register'";} ?>/>
            <span class="error-register-span">
            <?php if(isset($_SESSION['errores-span']) && isset($_SESSION['errores-span']['observaciones'])) {
                echo $_SESSION['errores-span']['observaciones'];
            } unset($_SESSION['errores-span']); ?>
            </span>
        </p>
            
        <button type='submit' name='btnRegistro' value='Confirmar'>Confirmar</button>
        </form>
    </div>
<?php
		ErrorHelpers::clearAll();
?>