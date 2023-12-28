<?php

namespace Model;

class Usuario extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];

    // Atributos
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    // Constructor
    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    // Validación
    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            self::$alertas['error'][] = "Tenes que añadir un nombre";
        }

        if(!$this->apellido) {
            self::$alertas['error'][] = "Tenes que añadir un apellido";
        }

        if(!$this->email) {
            self::$alertas['error'][] = "Tenes que añadir un email";
        }

        if(!$this->password) {
            self::$alertas['error'][] = "Tenes que añadir una contraseña";
        }

        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = "La contraseña debe tener al menos 6 caracteres";
        }

        return self::$alertas;
    }

    // Validación
    public function validarLogin() {
        if(!$this->email) {
            self::$alertas['error'][] = "Tenes que añadir un email";
        }

        if(!$this->password) {
            self::$alertas['error'][] = "Tenes que añadir una contraseña";
        }

        return self::$alertas;
    }

    // Validación
    public function validarEmail() {
        if(!$this->email) {
            self::$alertas['error'][] = "Tenes que añadir un email";
        }

        return self::$alertas;
    }

    // Verificar si el usuario ya existe
    public function existeUsuario() {
        // Revisar si el email ya está registrado
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";
        $resultado = self::$db->query($query);

        if($resultado->num_rows) {
            self::$alertas['error'][] = "El usuario ya está registrado";
        }

        return $resultado;
    }

    // Hashear el password
    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // Generar un token
    public function crearToken() {
        $this->token = uniqid();
    }

    // Comprobar el password
    public function comprobarPasswordAndVerificado($password) {
        $resultado = password_verify($password, $this->password);

        if(!$resultado || !$this->confirmado) {
            self::$alertas['error'][] = "El password es incorrecto o la cuenta no está verificada";
        } else {
            return true;
        }
    }

    // Validar el password
    public function validarPassword() {
        if(!$this->password) {
            self::$alertas['error'][] = "Tenes que añadir una contraseña";
        }

        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = "La contraseña debe tener al menos 6 caracteres";
        }

        return self::$alertas;
    }
    
}