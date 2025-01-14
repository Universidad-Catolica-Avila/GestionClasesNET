<?php
	use admin\gestionDeClases\Config\Parameters;
use admin\gestionDeClases\Helpers\Authentication;
use admin\gestionDeClases\Helpers\ErrorHelpers;

    $dataPOST = $data["dataPOST"]??NULL;
    $validationsError = $data["validationsError"]??NULL;
    
    if (isset($_SESSION['errores'])) {
        foreach ($_SESSION['errores'] as $error) {
            echo "<p class='error'>$error</p>"; // Muestra cada mensaje de error
        }
        unset($_SESSION['errores']); // Limpiar los errores después de mostrarlos
    }
?>

<div class="contenido container-iniciar-sesion">
    <?php
        if (Authentication::isAdmin()) {
            // Se concatena la URL correctamente usando PHP
            echo '<button><a href="' . Parameters::$BASE_URL . 'Usuario/registrarUsuarios">Registrar Usuario</a></button>';
        }
    ?>

</div>
</main>
    <script src="<?=Parameters::$BASE_URL . "assets/js/script.js" ?>"></script>
</body>
</html>      
<?php
		ErrorHelpers::clearAll();
?>