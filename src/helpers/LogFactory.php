<?php
	namespace admin\gestionDeClases\Helpers;

    use Monolog\Handler\StreamHandler;
    use Monolog\Logger;
    use Psr\Log\LoggerInterface;
    use Monolog\Level;
        
    class LogFactory {
    
        public static function getLogger(string $canal = "Blog") : LoggerInterface {
            $log = new Logger($canal);
            $log->pushHandler(new StreamHandler("logs/Debug.log", Level::Debug));
            $log->pushHandler(new StreamHandler("logs/Errores.log", Level::Error, false));
    
            return $log;
        }
    }    