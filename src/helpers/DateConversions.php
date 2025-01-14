<?php
namespace admin\gestionDeClases\Helpers;

class DateConversions{
    public static function formatearFechaDesdeMysql($fecha){
        // Desde AÑO-MES-DIA (Year-Month-Day) --> A DIA-MES-AÑO
        return date('d/m/Y', strtotime($fecha));
    }

    public static function formatearFechaAMysql($fecha){
        // Desde DIA-MES-AÑO (d-m-Y) --> A AÑO-MES-DIA (Y-m-d)
        return date('Y-m-d', strtotime($fecha));
    }
}

	