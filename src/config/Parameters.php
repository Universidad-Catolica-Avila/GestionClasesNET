<?php
namespace admin\gestionDeClases\Config;

class Parameters{
    public static $CONTROLLER_DEFAULT = "Usuario";
    public static $ACTION_DEFAULT = "index";
    public static $PASSWORD_MIN_LENGTH = 6;

    public static $BASE_URL = "http://localhost/GestionDeClases/";
    public static $OBSERVACIONES_MAX_LENGTH = 100;
    public static $TIPO_PAD_ADMITIDO = ['Aula Virtual FCCS', 'PAD Único', 'Enlace múltiple', 'Presencial/Distancia', 'Aula Virtual Ingenierías', 'Sala Steven', ''];
    public static $ROLES_ADMITIDOS = ['Técnico', 'Posproducción', 'AdmTecnico', 'AdmPospro'];
}