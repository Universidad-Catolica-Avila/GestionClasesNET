<?php

namespace admin\gestionDeClases\Controllers;

use admin\gestionDeClases\Helpers\Validations;
use admin\gestionDeClases\Models\PersonalPosproModel;
use admin\gestionDeClases\Models\UsuarioModel;
use admin\gestionDeClases\Entities\UserEntity;
use admin\gestionDeClases\Config\Parameters;
use admin\gestionDeClases\Helpers\Authentication;
use admin\gestionDeClases\Models\TecnicoModel;

class UsuarioController {

    public function index() {
        if (!Authentication::isUserLogged()) {
            header("Location: " . Parameters::$BASE_URL . "Usuario/login");
            exit();
        }
        if (Authentication::isAdmin()) {
            header("Location: " . Parameters::$BASE_URL . "HorarioClases/inicio");
            exit();
        }
        if (!Authentication::isAdmin()) {
            header("Location: " . Parameters::$BASE_URL . "RegistroClaseSemanal/getSemanaActual");
            exit();
        }
    }
   
    public function login() {
        if (Authentication::isUserLogged()) {
            header("Location: " . Parameters::$BASE_URL . "Usuario/index");
            exit();
        }else{
            ViewController::show('views/usuarios/login.php');
        }
    }

    public function loginsave(): void {
      
        if (isset($_POST['btnLogin'])) {

            $username = isset($_POST["usuario"]) ? trim($_POST["usuario"]) : "";
            $password = $_POST["password"] ?? "";
            $erroresSpan = [];
    
            if (!Validations::validateName($username)) {
                $erroresSpan['username'] = "Nombre de usuario invalido.";
            }

            if (empty($username)) {
                $erroresSpan['username'] = "Por favor, ingrese su nombre de usuario.";
            }

            if (!Validations::validateFormatPassword($password)) {
                $erroresSpan['contraseña'] = "Contraseña incorrecta (Minimo 6 caracteres).";
            }
    
            if (!empty($erroresSpan)) {
                $_SESSION['errores-span'] = $erroresSpan;
                header('Location: ' . Parameters::$BASE_URL . 'Usuario/login');
                exit();
            }
           
            // Cargar modelo de usuario
            $usuarioModel = new UsuarioModel();
            $usuario = $usuarioModel->login($username);
          
           // var_dump($usuario);exit;
          
            // Verificar si el usuario existe
            if ($usuario && isset($usuario->password)) {

                if ($usuario->estado == 0) {
                    $_SESSION['errores'][] = 'El usuario esta dado de baja, Por favor contacta con un administrador.';
                    header("Location: " . Parameters::$BASE_URL . "Usuario/login");
                    exit();
                }
                
                // Comprobar la contraseña  
                if (password_verify($password, $usuario->password)) { 

                    $userEntity = new UserEntity();
                    $userEntity->setId($usuario->idUsuario)
                               ->setNombre($usuario->nombre)
                               ->setEmail($usuario->email)
                               ->setRol($usuario->rol);
                               
    
                    // Almacenar usuario en sesión
                    $_SESSION['user'] = $userEntity;

                    // Redirigir al dashboard si el login es exitoso
                    header("Location: " . Parameters::$BASE_URL . "Usuario/index");
                    exit();
                } else {
                    // password incorrecta
                    $_SESSION['errores'][] = "Usuario o Contraseña incorrectos";
                    header("Location: " . Parameters::$BASE_URL . "Usuario/login");
                    exit();
                }
            } else {
                // Usuario no encontrado
                $_SESSION['errores'][] = "Usuario no encontrado";
                header("Location: " . Parameters::$BASE_URL . "Usuario/login");
                exit();
            }
        }
    }

    public function registrar() {
        if (!Authentication::isUserLogged() || Authentication::isAdmin()) {

            ViewController::show('views/usuarios/registrar.php');

        } else {
            ViewController::showError(403);
        }
    }

    public function registrarSave() {
        if (!Authentication::isUserLogged()) {

            $username = isset($_POST["nombre"]) ? trim($_POST["nombre"]) : "";
            $email = isset($_POST['email']) ? trim($_POST['email']) : "";
            $password = $_POST["password"] ?? "";
            $password2 = $_POST["password2"] ?? "";
            $rol = $_POST['rol'] ?? "";
            $erroresSpan = [];
            
            $rolesAdmitidos = Parameters::$ROLES_ADMITIDOS;
            
            if (!Validations::validateName($username)) {
                $erroresSpan['nombre'] = "Formato del nombre invalido.";
            }
            if (empty($username)) {
                $erroresSpan['nombre'] = "Por favor, ingrese su nombre de usuario.";
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erroresSpan['email'] = "El email no es válido.";
            }
            
            if (empty($password)) {
                $erroresSpan['contraseña'] = "Inserte la contraseña.";
            }

            if ($password !== $password2) {
                $erroresSpan['contraseña'] = "Las contraseñas no coinciden.";
            }

            $rolValido = true;
            foreach ($rolesAdmitidos as $rolAdmitido) {            
                if ($rol == $rolAdmitido) {
                    $rolValido = false;
                    break;
                }
            }
            
            if ($rolValido) {
                $erroresSpan['rol'] = "Introduce un rol válido.";
            }
            
            if (!empty($erroresSpan)) {
                $_SESSION['errores-span'] = $erroresSpan;
                header('Location: ' . Parameters::$BASE_URL . 'Usuario/registrar');
                exit();
            }
            
            $usuarioModel = new UsuarioModel();
            $usuario = $usuarioModel->getUsuarioPorEmail($email);
            
            if (empty($usuario)) {
                if ($password === $password2) {

                    $userEntity = new UserEntity();
                    $userEntity -> setNombre($username)
                                -> setEmail($email)
                                -> setPassword($password)
                                -> setRol($rol);

                    $resultado = $usuarioModel->register($userEntity);
                    
                    $id = intval($usuarioModel->getUsuarioPorEmail($email)->idUsuario);


                    if (($rol == 'Técnico') || ($rol == 'AdmTecnico')) {
                        $tecnicoModel = new TecnicoModel;
                        $tecnicoModel->nuevoTecnico($id, $username);
                    }
                    if (($rol == 'Posproducción') || ($rol == 'AdmPospro')) {
                        $posproModel = new PersonalPosproModel();
                        $posproModel->nuevoPospro($id, $username);
                    }

                    if ($resultado) {
                        $_SESSION['mensaje'] = 'Registro exitoso.';
                        header("Location: " . Parameters::$BASE_URL . "Usuario/registrar");
                        exit();
                        //INICIAR SESION
                    } else {
                        $_SESSION['errores'][] = "Error al registrar el usuario.";
                        header("Location: " . Parameters::$BASE_URL . "Usuario/registrar");
                        exit();
                    }

                } else {
                    $_SESSION['errores'][] = "Las contraseñas no coinciden";
                    header("Location: " . Parameters::$BASE_URL . "Usuario/registrar");
                    exit();
                }
            } else {
                $_SESSION['errores'][] = "Este usuario ya existe";
                header("Location: " . Parameters::$BASE_URL . "Usuario/registrar");
                exit();
            }
        } else {
            ViewController::showError(403);
        }
    }

    public function registrarUsuariosAdmin() {
        if (Authentication::isAdmin()) {

            // Asignación de variables con valores por defecto
            $username = isset($_POST["nombre"]) ? trim($_POST["nombre"]) : "";
            $email = isset($_POST['email']) ? trim($_POST['email']) : "";
            $password = $_POST["password"] ?? "";
            $password2 = $_POST["password2"] ?? "";
            $rol = $_POST['rol'] ?? "";
            $erroresSpan = [];
            
            $rolesAdmitidos = Parameters::$ROLES_ADMITIDOS;
            
            if (!Validations::validateName($username)) {
                $erroresSpan['nombre'] = "Formato del nombre invalido.";
            }
            
            // Validación del correo electrónico
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erroresSpan['email'] = "El email no es válido.";
            }
            
            if (empty($password)) {
                $erroresSpan['contraseña'] = "Inserte la contraseña.";
            }

            if ($password !== $password2) {
                $erroresSpan['contraseña'] = "Las contraseñas no coinciden.";
            }

            $rolValido = true;
            foreach ($rolesAdmitidos as $rolAdmitido) {            
                if ($rol == $rolAdmitido) {
                    $rolValido = false;
                    break;
                }
            }
            
            if ($rolValido) {
                $erroresSpan['rol'] = "Introduce un rol válido.";
            }

            
            if (!empty($erroresSpan)) {
                $_SESSION['errores-span'] = $erroresSpan;
                header('Location: ' . Parameters::$BASE_URL . 'Usuario/registrar');
                exit();
            }

            $usuarioModel = new UsuarioModel();
            $usuario = $usuarioModel->getUsuarioPorEmail($email);
            
            if (empty($usuario)) {
                if ($password === $password2) {

                    $userEntity = new UserEntity();
                    $userEntity -> setNombre($username)
                                -> setEmail($email)
                                -> setPassword($password)
                                -> setRol($rol);

                    $resultado = $usuarioModel->register($userEntity);

                    $id = intval($usuarioModel->getUsuarioPorEmail($email)->idUsuario);

                    if (($rol == 'Técnico') || ($rol == 'AdmTecnico')) {
                        $tecnicoModel = new TecnicoModel;
                        $tecnicoModel->nuevoTecnico($id, $username);
                    }
                    if (($rol == 'Posproducción') || ($rol == 'AdmPospro')) {
                        $posproModel = new PersonalPosproModel();
                        $posproModel->nuevoPospro($id, $username);
                    }
                    
                    if ($resultado) {
                        $_SESSION['mensaje'] = 'Registro exitoso.';
                        header("Location: " . Parameters::$BASE_URL . "Usuario/registrar");
                        exit();
                    } else {
                        $_SESSION['errores'][] = "Error al registrar el usuario.";
                        header("Location: " . Parameters::$BASE_URL . "Usuario/registrar");
                        exit();
                    }

                } else {
                    $_SESSION['errores'][] = "Las passwords no coinciden!!";
                    header("Location: " . Parameters::$BASE_URL . "Usuario/registrar");
                    exit();
                }
            } else {
                $_SESSION['errores'][] = "Este usuario ya existe!!";
                header("Location: " . Parameters::$BASE_URL . "Usuario/registrar");
                exit();
            }
        } else {
            ViewController::showError(403);
        }
    }

    public function verTodosUsuarios() {
        if (Authentication::isAdmin()) {
            
            $usuarioModel = new UsuarioModel;
            
            $idUsuario = $_SESSION["user"]->getId();
           
            $usuarios = $usuarioModel->getAllUsuariosMenosAdmin($idUsuario);

            ViewController::show("views/usuarios/verTodosUsuarios.php", ["usuarios"=> $usuarios]);

        } else {
            ViewController::showError(403);
        }
    }

    public function editarUsuario() {
        if (Authentication::isAdmin()) {
            
            if (isset($_GET["idUsuario"])) {

                $id = $_GET["idUsuario"];
                
                $usuarioModel = new UsuarioModel;
                $existe = $usuarioModel->getOne($id);

                if ($existe) {
                    
                    ViewController::show("views/usuarios/editarUsuario.php", ["usuario"=> $existe]);
                    exit();

                } else {
                    $_SESSION['errores'][] = "El usuario no existe";
                    header('Location: ' . Parameters::$BASE_URL . "Usuario/verTodosUsuarios");
                    exit();
                }
            
            } else {
                $_SESSION['errores'][] = "ERROR EN EL IDUSUARIO";
                header('Location: ' . Parameters::$BASE_URL . "Usuario/verTodosUsuarios");
                exit();
            }

        } else {
            ViewController::showError(403);
        }

    }

    public function modificarUsuario(){
        if (Authentication::isAdmin()) {

            $usuarioModel = new UsuarioModel();
            $idUsuario = intval($_GET['idUsuario']);

            $usuario = $usuarioModel->getOne($idUsuario);

            
            if ($usuario) {
                $username = $_POST["nombre"];
                $email = $_POST["email"];
                $password = $_POST["password"];
                $password2 = $_POST["password2"];
                $rol = $_POST["rol"];
                $observaciones = $_POST["observaciones"];
                $erroresSpan = [];
            
                $rolesAdmitidos = Parameters::$ROLES_ADMITIDOS;
               
                
                if (!Validations::validateName($username)) {
                    $erroresSpan['nombre'] = "Formato del nombre invalido.";
                }
                
                if (empty($username)) {
                    $erroresSpan['nombre'] = "Por favor, ingrese el nombre de usuario.";
                }
                
                // Validación del correo electrónico
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $erroresSpan['email'] = "El email no es válido.";
                }
                    
                if ($password !== $password2) {
                    $erroresSpan['contraseña'] = "Las contraseñas no coinciden.";
                }
    
                $rolValido = true;
                foreach ($rolesAdmitidos as $rolAdmitido) {            
                    if ($rol == $rolAdmitido) {
                        $rolValido = false;
                        break;
                    }
                }

                if ($rolValido) {
                    $erroresSpan['rol'] = "Introduce un rol válido.";
                }

                if (!Validations::validateObservaciones($observaciones)) {
                    $erroresSpan['observaciones'] = 'Formato Incorrecto.';
                }

                if (!empty($erroresSpan)) {
                    $_SESSION['errores-span'] = $erroresSpan;
                    header('Location: ' . Parameters::$BASE_URL . "Usuario/editarUsuario?idUsuario=$idUsuario");
                    exit();
                }

                
                if ($rol != $usuario->rol) {
                    if (($rol == 'Técnico') || ($rol == 'AdmTecnico')) {
                        $tablaRol = "personalpospro";
                        $idname = "idPersonal";
                        $borrar = $usuarioModel->borrarRol($idUsuario, $tablaRol, $idname);

                        if (is_null($borrar)) {
                            $_SESSION['errores'][] = "El usuario esta asignado a algun registro.";
                            header('Location: ' . Parameters::$BASE_URL . "Usuario/verTodosUsuarios");
                            exit();
                        }

                        $tecnicoModel = new TecnicoModel;
                        $comprobar = $tecnicoModel->getTecnico($idUsuario);
            
                        if ($comprobar->idTecnico != $idUsuario) {
                            $tecnicoModel->nuevoTecnico($idUsuario, $username);
                        }
                    }
                    if (($rol == 'Posproducción') || ($rol == 'AdmPospro')) {

                        $tablaRol = "tecnico";
                        $idname = "idTecnico";
                        $borrar = $usuarioModel->borrarRol($idUsuario, $tablaRol, $idname);


                        if (is_null($borrar)) {
                            $_SESSION['errores'][] = "El usuario: " . $username ." esta asignado a algun registro";
                            header('Location: ' . Parameters::$BASE_URL . "Usuario/verTodosUsuarios");
                            exit();
                        }

                        $posproModel = new PersonalPosproModel();
                        $comprobar = $posproModel->getAllPorId($idUsuario);
            
                        if ($comprobar->idPersonal != $idUsuario) {
                            $posproModel->nuevoPospro($idUsuario, $username);
                        }
                    }
                }
        
                $userEntity = new UserEntity();
                if (empty($password)) {
                    $userEntity ->setId($idUsuario)
                                -> setNombre($username)
                                -> setEmail($email)
                                -> setPassword($usuario->password)
                                -> setRol($rol)
                                -> setEstado($usuario->estado)
                                -> setfechaBaja($usuario->fecha_baja)
                                -> setobservaciones($observaciones);

                    $resultado = $usuarioModel->modUsuario($idUsuario, $userEntity);

                } else {
                    $userEntity ->setId($idUsuario)
                                -> setNombre($username)
                                -> setEmail($email)
                                -> setPassword($password = password_hash($password, PASSWORD_DEFAULT))
                                -> setRol($rol)
                                -> setEstado($usuario->estado)
                                -> setfechaBaja($usuario->fecha_baja)
                                -> setobservaciones($observaciones);

                    $resultado = $usuarioModel->modUsuario($idUsuario, $userEntity);
                }

                if ($resultado) {
                    $_SESSION['mensaje'] = 'Usuario actualizado correctamente.';
                    header("Location: " . Parameters::$BASE_URL . "Usuario/verTodosUsuarios");
                    exit();
                } else {
                    $_SESSION['errores'][] = "Error al actualizar el usuario.";
                    header("Location: " . Parameters::$BASE_URL . "Usuario/editarUsuario?idUsuario=$idUsuario");
                    exit();
                }
            } else {
                $_SESSION['errores'][] = "El usuario no existe.";
                header("Location: " . Parameters::$BASE_URL . "Usuario/verTodosUsuarios");
                exit();
            }
            
        } else {
            ViewController::showError(403);
        }
    }

    public function cambiarEstadoUsuario() {
        if (Authentication::isAdmin()) {
            $errores = [];
    
            $usuarioModel = new UsuarioModel();
            $idUsuario = $_GET['idUsuario'];
            $usuario = $usuarioModel->getOne($idUsuario);

            if (!empty($errores)) {
                $_SESSION['errores'] = $errores;
                header("Location: " . Parameters::$BASE_URL . "Usuario/verTodosUsuarios");
                exit();
            }
    
            if ($usuario) {
                if ($usuario->estado == 1) {
                    $estado = 0;
                    $usuarioModel->cambiarEstadoUsuario($idUsuario, $estado);
                    $_SESSION['mensaje'] = "El usuario esta de baja";
                    header("Location: " . Parameters::$BASE_URL . "Usuario/verTodosUsuarios");
                    exit();
                } if ($usuario->estado == 0) {
                    $estado = 1;
                    $usuarioModel->cambiarEstadoUsuario($idUsuario, $estado);
                    $_SESSION['mensaje'] = "El usuario esta de alta";
                    header("Location: " . Parameters::$BASE_URL . "Usuario/verTodosUsuarios");
                    exit();
                } else {
                    $errores[] = 'Error al cambiar el estado';
                }
            } else {
                $errores[] = 'El usuario no existe';
            }
        } else {
            ViewController::showError(403);
        }
    }

    

    public function closeSession()
    {
        if (Authentication::isUserLogged()) unset($_SESSION['user']);   
        if (Authentication::isAdmin()) unset($_SESSION['admin']);   
        
        header("Location: " . PARAMETERS::$BASE_URL);
        exit();
    }

}