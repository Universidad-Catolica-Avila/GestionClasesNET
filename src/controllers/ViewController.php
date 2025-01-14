<?php
namespace admin\gestionDeClases\Controllers;
use admin\gestionDeClases\Controllers\ErrorController;
use admin\gestionDeClases\Helpers\Authentication;

class ViewController{

    public static function show($viewName, $data = null){
        self::showHeader();
        self::showSidebar();
        require_once $viewName;
        self::showFooter();
    }
    
    public static function showError($error){
        self::showHeader();
        self::showSidebar();
        $metodoError = "show".$error;
        (new ErrorController())->$metodoError();
        self::showFooter();
    }

    private static function showHeader(){
        include 'views/layout/header.php';        
    }
    private static function showSidebar(){
        include 'views/layout/sidebar.php';
    }
    private static function showFooter(){
        include 'views/layout/footer.php';            
    }

}
