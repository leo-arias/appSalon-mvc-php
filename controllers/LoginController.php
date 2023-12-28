<?php

namespace Controllers;

use Classes\Email;
use MVC\Router;
use Model\Usuario;

class LoginController{
    public static function login(Router $router){
        // Arreglo con mensajes de error
        $alertas = [];

        // Crear una nueva instancia
        $auth = new Usuario;

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            // Crear una nueva instancia
            $auth = new Usuario($_POST);
           
            // Validar el usuario
            $alertas = $auth->validarLogin();

            // Revisar si no hay errores en el arreglo de alertas
            if(empty($alertas)) {
                // Verificar si el usuario existe
                $usuario = Usuario::where('email', $auth->email);

                if($usuario) {
                    // Verificar si el password es correcto
                    if( $usuario->comprobarPasswordAndVerificado($auth->password) ) {
                        // Autenticar el usuario
                        if(!isset($_SESSION)) {
                            session_start();
                        }

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionar al usuario
                        if($usuario->admin === '1'){
                            $_SESSION['admin'] = $usuario->admin ?? null;

                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                    }
                } else {
                    // Autenticación incorrecta
                    Usuario::setAlerta('error', 'El usuario no existe');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas,
            'auth' => $auth
        ]);
    }

    public static function logout(Router $router){
        if(!isset($_SESSION)){
            session_start();
        }

        $_SESSION = [];

        header('Location: /');
    }

    public static function olvide(Router $router){
        // Arreglo con mensajes de error
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            // Crear una nueva instancia
            $auth = new Usuario($_POST);

            // Validar el usuario
            $alertas = $auth->validarEmail();

            // Revisar si no hay errores en el arreglo de alertas
            if(empty($alertas)) {
                // Verificar si el usuario existe
                $usuario = Usuario::where('email', $auth->email);

                // Si el usuario existe
                if($usuario && $usuario->confirmado === '1') {
                    // Generar un token único
                    $usuario->crearToken();

                    // Actualizar el usuario en la base de datos
                    $usuario->guardar();

                    // Enviar el Email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarInstrucciones();

                    // Alerta de éxito
                    Usuario::setAlerta('exito', 'Se envió un enlace a tu correo para recuperar tu contraseña');
                } else {
                    // Autenticación incorrecta
                    Usuario::setAlerta('error', 'El usuario no existe o no ha confirmado su cuenta');
                }
            }
        }

        // Obtener las alertas
        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router){
        // Arreglo con mensajes de error
        $alertas = [];  
        $error = false;

        // Verificar que el token sea válido
        $token = s($_GET['token']);

        // Consultar por el token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario) || $usuario->token === ''){
            // Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no válido');
            $error = true;
        } 
        
        if($_SERVER['REQUEST_METHOD'] === 'POST' && !$error){
            // Crear una nueva instancia
            $password = new Usuario($_POST);

            // Validar el usuario
            $alertas = $password->validarPassword();

            // Revisar si no hay errores en el arreglo de alertas
            if(empty($alertas)) {
                // Resetear el password
                $usuario->password = null; 

                // Asignar el nuevo password
                $usuario->password = $password->password; 

                // Hashear el password
                $usuario->hashPassword();

                // Limpiar el token
                $usuario->token = null;

                // Guardar el nuevo password
                $resultado = $usuario->guardar();

                if($resultado) {   
                    // Redireccionar al mensje de éxito
                    header('Location: /mensaje?resultado=2');
                }
            }
        }

        // Obtener las alertas
        $alertas = Usuario::getAlertas();

        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router){
        // Crear una nueva instancia
        $usuario = new Usuario;

        // Arreglo con mensajes de error
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            // Sincronizar con los datos
            $usuario->sincronizar($_POST);

            // Validar
            $alertas = $usuario->validarNuevaCuenta();

            // Revisar si no hay errores en el arreglo de alertas
            if(empty($alertas)){
                // Verificar si el usuario ya existe
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                } else {
                    // Hashear el password
                    $usuario->hashPassword();

                    // Generar un token único
                    $usuario->crearToken();

                    // Enviar el Email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();

                    // Guardar el usuario en la base de datos
                    $resultado = $usuario->guardar();

                    if($resultado){
                        // Redireccionar al usuario
                        header('Location: /mensaje?resultado=1');
                    }
                }
            }
        }

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas ?? []
        ]);
    }

    public static function mensaje(Router $router){
        // Muestra mensaje condicional
        $resultado = $_GET['resultado'] ?? null;

        $router->render('auth/mensaje', [
            'resultado' => $resultado
        ]);
    }

    public static function confirmar(Router $router){
        // Arreglo con mensajes de error
        $alertas = [];

        // Verificar que el token sea válido
        $token = s($_GET['token']);

        // Consultar por el token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario) || $usuario->token === ''){
            // Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no válido');
        } else {
            // Confirmar el usuario
            $usuario->confirmado = "1";
            $usuario->token = "";
            $usuario->guardar();

            // Mostrar mensaje de confirmación
            Usuario::setAlerta('exito', 'Cuenta confirmada correctamente');
        }

        // Obtener las alertas
        $alertas = Usuario::getAlertas();

        // Renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas ?? []
        ]);
    }
}