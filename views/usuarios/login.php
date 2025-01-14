<?php
use admin\gestionDeClases\Config\Parameters;
use admin\gestionDeClases\Helpers\ErrorHelpers;

$dataPOST = $data["dataPOST"] ?? NULL;

if (isset($_SESSION['errores'])) {
    echo "<div class='errores-container-centradoForm'>";
    foreach ($_SESSION['errores'] as $error) {
        echo "<p class='error'>$error</p>"; // Muestra cada mensaje de error
    }
    echo "</div>";
}
?>

<div class="contenido container-iniciar-sesion">
    <form class="formulario-iniciar-sesion" action="<?= Parameters::$BASE_URL ?>Usuario/loginsave" method="post">
        <nav id="navbar" class="navbarLogin">
            <div class="imagenes">
                <img src="<?= Parameters::$BASE_URL . "assets/img/logo_ucav.png" ?>" alt="Logo" class="logo">
                
            </div>
            <DIV><label><B>GESTIÓN DE CLASES</B></label></DIV>
        </nav>
       <p> <label>Bienvenidos a la aplicación de Gestión de Clases de la UCAV</label></p>
        <p>
       
            <label for="idLoginUsuario">Usuario*</label>
            <input type='text' name='usuario' id='idLoginUsuario' <?php if (isset($_SESSION['errores-span']) && isset($_SESSION['errores-span']['username'])) {
                echo "class='error-register'";
            } ?> />
            <span class="error-register-span">
                <?php if (isset($_SESSION['errores-span']) && isset($_SESSION['errores-span']['username'])) {
                    echo $_SESSION['errores-span']['username'];
                } ?>
            </span>
        </p>
        <p>
            <label for="idLoginPassword">Contraseña*</label>
            <input type='password' name='password' id='idLoginPassword' <?php if (isset($_SESSION['errores-span']) && isset($_SESSION['errores-span']['contraseña'])) {
                echo "class='error-register'";
            } ?> />
            <span class="error-register-span">
                <?php if (isset($_SESSION['errores-span']) && isset($_SESSION['errores-span']['contraseña'])) {
                    echo $_SESSION['errores-span']['contraseña'];
                }
                unset($_SESSION['errores-span']); ?>
            </span>
        </p>
        <button type="submit" name='btnLogin' value="Entrar">Iniciar Sesión</button>
        <?php if (isset($_SESSION['errores'])) { ?>
            <p id="enlace-registrar">
                ¿No puedes iniciar sesion? <a href="<?= Parameters::$BASE_URL . "Usuario/registrar" ?>">Registrar</a>
            </p>
            <?php unset($_SESSION['errores']);
        } ?>
    </form>
</div>
<?php
ErrorHelpers::clearAll();
?>