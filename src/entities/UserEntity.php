<?php
	namespace admin\gestionDeClases\Entities;

	class UserEntity{
        private $id;
        private $nombre;
        private $email;
        private $password;
        private $estado;
        private $rol;
        private $fechaBaja;
        private $observaciones;

        public function __construct(){}   
        public function setId($id){$this->id = $id; return $this;}
        public function setNombre($nombre){$this->nombre = $nombre; return $this;}
        public function setEmail($email){$this->email = $email; return $this;}
        public function setPassword($password){$this->password = $password; return $this;}
        public function setEstado($estado){$this->estado = $estado; return $this;}
        public function setRol($rol) {$this->rol = $rol; return $this; }
        public function setfechaBaja($fechaBaja) {$this->fechaBaja = $fechaBaja; return $this; }
        public function setobservaciones($observaciones) {$this->observaciones = $observaciones; return $this; }
        
        public function getId(){ return $this->id;}
        public function getNombre(){ return $this->nombre;}
        public function getEmail(){ return $this-> email;}
        public function getPassword(){ return $this->password;}
        public function getEstado(){ return $this->estado;}
        public function getRol() {return $this->rol;}
        public function getfechaBaja() {return $this->fechaBaja;}
        public function getobservaciones() {return $this->observaciones;}
    }