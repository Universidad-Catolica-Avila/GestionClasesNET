<?php
namespace admin\gestionDeClases\Controllers;

class ErrorController{
    public function index(){}

    public function show404(){
        echo "<div class='errores-container'>";
            echo "<p class='error404'>Error 404, el recurso solicitado no existe </p>";
        echo "</div>";
    }
    
    public function show403(){
        echo "<div class='errores-container'>";
            echo "<p class='error'>Error 403, acceso prohibido para todas las personas sin autorización </p>";
        echo "</div>";
    }
    
}