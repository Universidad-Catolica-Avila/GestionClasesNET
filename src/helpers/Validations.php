<?php
namespace admin\gestionDeClases\Helpers;

use admin\gestionDeClases\Config\Parameters;

class Validations {
    public static function validateName($nombre): bool {
        return (preg_match("/^[a-zñáéíóú]+([ ][a-zñáéíóú]+)*$/", strtolower($nombre)));
    }

    public static function validateFormatPassword($password): bool {
        if (empty($password)) {
            return false;
        }

        if (strlen($password) < Parameters::$PASSWORD_MIN_LENGTH) {
            return false;
        }

        return true;
    }

    public static function validateObservaciones($observaciones): bool {
        return (strlen($observaciones) <= Parameters::$OBSERVACIONES_MAX_LENGTH) &&
               (preg_match("/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,!?():'\"-]*$/", $observaciones));
    }


    public static function validateActivityName($nombreActividad): bool {
        return !empty($nombreActividad);
    }

    public static function validateActivityDate($fechaInicio): bool {
        return !empty($fechaInicio) && $fechaInicio >= date("Y-m-d");
    }
}
