<?php
	use admin\gestionDeClases\Config\Parameters;
    use admin\gestionDeClases\Helpers\Authentication;
    use cesar\gestionDeClases\Helpers\Validations;
?>

<?php if (Authentication::isUserLogged()) { 

    $loggedUser = $_SESSION['user'];

    $userEntity = $_SESSION['user'];
    $userRol = $userEntity->getRol();

?>
<?php if(Authentication::isAdmin()) { ?>
    <nav id="navbar" class="navbar">
        <div class="imagenes">
            <img src="<?=Parameters::$BASE_URL . "assets/img/logo_ucav.png"?>" alt="Logo" class="logo">

            <?php if (Authentication::isUserLogged()) { ?>
            <li class="user-icon">
                <div class="tooltip-container" id="tooltip-container">
                    <div class="material-symbols-outlined">account_circle</div>
                    <div class="tooltip" id="tooltip">
                      
                        <span>Usuario: <?=$_SESSION['user']->getEmail()?></span>
                        <a class="nav-link" id="iniciar-sesion" href="<?= Parameters::$BASE_URL . "Usuario/closeSession" ?>">Cerrar Sesion</a>
                    </div>
                </div>
            </li>
        <?php }; ?>
        </div>
        <div class="user-info">
            <p>Rol: <?= $userRol ?></p>
        </div>
        <div class="menu" id="menu">
            <ul class="menu-section">
                <p>USUARIOS</p>
                <a href="<?=Parameters::$BASE_URL . "Usuario/verTodosUsuarios" ?>">
                    <li><span class="material-symbols-outlined">group</span><span>Ver Usuarios</span></li>
                </a>
            </ul>
            <ul class="menu-section">
                <p>ADMINISTRACIÓN</p>
                <a href="<?= Parameters::$BASE_URL . "HorarioClases/inicio"?>">
                    <li><span class="material-symbols-outlined">add</span><span>Subir Tabla</span></li>
                </a>   
                <a href="<?= Parameters::$BASE_URL . "HorarioClases/verTabla"?>">
                    <li><span class="material-symbols-outlined">edit</span><span>Gestionar Tabla</span></li>
                </a>   
            </ul>
            <ul class="menu-section">
                <p>SEMANAS</p>
                <a href="<?= Parameters::$BASE_URL . "RegistroClaseSemanal/getSemanaActual"?>">
                    <li><span class="material-symbols-outlined">calendar_month</span><span>Ver semana actual</span></li>
                </a>
            </ul>
        </div>
    </nav>
<?php } ?>
<?php if( Authentication::isAdmPospro() || Authentication::isAdmTecnico() ){ ?>
    <nav id="navbar" class="navbar">
    <div class="imagenes">
        <img src="<?=Parameters::$BASE_URL . "assets/img/logo_ucav.png"?>" alt="Logo" class="logo">
        <?php if (Authentication::isUserLogged()) { ?>
            <li class="user-icon">
                <div class="tooltip-container" id="tooltip-container">
                    <div class="material-symbols-outlined">account_circle</div>
                    <div class="tooltip" id="tooltip">
                        
                        <span>Usuario: <?=$_SESSION['user']->getEmail()?></span>
                        <a class="nav-link" id="iniciar-sesion" href="<?= Parameters::$BASE_URL . "Usuario/closeSession" ?>">Cerrar Sesion</a>
                    </div>
                </div>
            </li>
        <?php }; ?>
    </div>
    <div class="user-info">
        <p>Rol: <?= $userRol ?></p>
    </div>
        <div class="menu" id="menu">
            <ul class="menu-section">
                <p>SEMANAS</p>
                <a href="<?= Parameters::$BASE_URL . "RegistroClaseSemanal/getSemanaActual"?>">
                    <li><span class="material-symbols-outlined">calendar_month</span><span>Ver semana actual</span></li>
                </a>
            </ul>
        </div>
    </nav>
<?php } ?>
<?php if(Authentication::isPosproduccion() || Authentication::isTecnico() ){ ?>
    <nav id="navbar" class="navbar">
    <div class="imagenes">
        <img src="<?=Parameters::$BASE_URL . "assets/img/logo_ucav.png"?>" alt="Logo" class="logo">
        <?php if (Authentication::isUserLogged()) { ?>
            <li class="user-icon">
                <div class="tooltip-container" id="tooltip-container">
                    <div class="material-symbols-outlined">account_circle</div>
                    <div class="tooltip" id="tooltip">
                       
                        <span>Usuario:  <?=$_SESSION['user']->getEmail()?></span>
                        <a class="nav-link" id="iniciar-sesion" href="<?= Parameters::$BASE_URL . "Usuario/closeSession" ?>">Cerrar Sesion</a>
                    </div>
                </div>
            </li>
        <?php }; ?>
    </div>
    <div class="user-info">
        <p>Rol: <?= $userRol ?></p>
    </div>
        <div class="menu" id="menu">
            <ul class="menu-section">
                <p>SEMANAS</p>
               
                <a href="<?= Parameters::$BASE_URL . "RegistroClaseSemanal/getSemanaActual"?>">
                    <li><span class="material-symbols-outlined">calendar_month</span><span>Ver semana actual (datos)</span></li>
                </a>
            </ul>
        </div>
    </nav>
<?php } ?>
<?php } else { ?>
   <!-- <nav id="navbar" class="navbar">
            <div class="imagenes">
                <img src="<?=Parameters::$BASE_URL . "assets/img/logo_ucav.png"?>" alt="Logo" class="logo">
            </div>
          
            <div class="menu" id="menu">
                <ul class="menu-section">
                    <p>INICIAR SESION</p>
                    <a href="<?=Parameters::$BASE_URL . "Usuario/login" ?>">
                        <li><span class="material-symbols-outlined">login</span><span>Iniciar Sesión</span></li>
                    </a>
                </ul>
                <ul class="menu-section">
                    <p>REGISTRARSE</p>
                    <a href="<?=Parameters::$BASE_URL . "Usuario/registrar" ?>">
                        <li><span class="material-symbols-outlined">person_add</span><span>Registrarse</span></li>
                    </a>
                </ul>
            </div>
        </nav>-->
<?php } ?>