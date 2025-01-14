<?php
	namespace admin\gestionDeClases\Database;
	use admin\gestionDeClases\Config\ConfigBD;

	class Conexion{
		public static function conectar() {
			try {
				// Configura el DSN para SQL Server usando PDO
				$dsn = "sqlsrv:Server=" . ConfigBD::$SERVER_NAME_BD . ";Database=" . ConfigBD::$DB_NAME;
	
				// Crea una nueva instancia de PDO
				$conexion = new \PDO($dsn, ConfigBD::$USER_BD, ConfigBD::$PASSWORD_BD);
	
				// Configura el modo de error para excepciones
				$conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
				
				return $conexion;
			}catch (\PDOException $e){
				// Notificar al sistema de log
				// Reenviar al controlador de errores.
				echo $e->getMessage();
				return NULL;
			}
		}
	} 


	// class Conexion{
    //     public static function conectar() {
    //         try {
    //             // Configura el DSN para SQL Server usando PDO
    //             $dsn = "sqlsrv:Server=" . ConfigBD::$SERVER_NAME_BD . ";Database=" . ConfigBD::$DB_NAME;
    
    //             // Crea una nueva instancia de PDO
    //             $conexion = new \PDO($dsn, ConfigBD::$USER_BD, ConfigBD::$PASSWORD_BD);
    
    //             // Configura el modo de error para excepciones
    //             $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                
    //             return $conexion;
    //         } catch (\PDOException $e) {
    //             // Maneja el error de conexión
    //             echo "<p>Fallo en la conexión: " . $e->getMessage() . "</p>";
    //             return null;
    //         }
    //     }
    // }
