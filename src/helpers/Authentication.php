<?php
	namespace admin\gestionDeClases\Helpers;
    use admin\gestionDeClases\Config\Parameters;

	class Authentication {

        public static function isUserLogged(): bool {
            return isset($_SESSION['user']);
        }
    
        // Verifica si el usuario tiene un rol específico
        public static function hasRol(string $rol): bool {
            // Verifica que el usuario esté logueado
            if (!self::isUserLogged()) {
                return false;
            }
    
            // Obtiene el rol del usuario desde la sesión
            $userEntity = $_SESSION['user'];
            $userRol = $userEntity->getRol();

            // Compara el rol del usuario con el rol esperado
            return $userRol === $rol;
        }
    
        // Método para verificar si el usuario es un Administrador
        public static function isAdmin(): bool {
            return self::hasRol("Administrador");
        }
        // Método para verificar si el usuario es un colaborador
        public static function isTecnico(): bool {
            return self::hasRol("Técnico");
        }
    
        // Método para verificar si el usuario es una secretaria
        public static function isPosproduccion(): bool {
            return self::hasRol("Posproducción");
        }
        // Método para verificar si el usuario es una secretaria
        public static function isAdmTecnico(): bool {
            return self::hasRol("AdmTecnico");
        }
        public static function isAdmPospro(): bool {
            return self::hasRol("AdmPospro");
        }
    }
    