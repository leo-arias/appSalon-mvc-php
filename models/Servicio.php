<?php

namespace Model;

class Servicio extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'servicios';
    protected static $columnasDB = ['id', 'nombre', 'precio'];

    // Atributos
    public $id;
    public $nombre;
    public $precio;

    // Constructor de la clase
    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }

    // Validación
    public function validar() {
        if(!$this->nombre) {
            self::$alertas['error'][] = "El nombre es obligatorio";
        }

        if(!$this->precio) {
            self::$alertas['error'][] = "El precio es obligatorio";
        }

        if(!is_numeric($this->precio)) {
            self::$alertas['error'][] = "El precio debe ser un número";
        }

        return self::$alertas;
    }
}