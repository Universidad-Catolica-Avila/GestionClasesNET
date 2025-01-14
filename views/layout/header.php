
<?php
	use admin\gestionDeClases\Config\Parameters;
    use admin\gestionDeClases\Helpers\Authentication;
    use cesar\gestionDeClases\Helpers\Validations;
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clases</title>
    <link rel="shortcut icon" href="<?= Parameters::$BASE_URL . "assets/img/logo_ucav.png" ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= Parameters::$BASE_URL . "assets/css/style.css" ?>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <!-- Link para FontAwesome 6.0 via CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="<?= Parameters::$BASE_URL . "assets/js/script_menu_y_nav.js" ?>"></script>
</head>

<body>
<?php if (Authentication::isUserLogged()) { ?>


    <header id="header">
        <span id="menu-hamburguesa" class="material-symbols-outlined">menu</span>
        <span id="boton-pantalla-completa" class="material-symbols-outlined">fullscreen</span>
    </header>
    <?php } ?>
    <main class="container">