<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    // Atributos
    public $nombre;
    public $email;
    public $token;

    // Constructor
    public function __construct($nombre, $email, $token) {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->token = $token;
    }

    // Enviar el email de confirmación
    public function enviarConfirmacion() {
        // Crear el objeto del email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        // Configurar el contenido del email
        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Confirma tu cuenta';

        // Set HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        // Habilitar HTML
        $contenido = "<html>";
        $contenido .= "<p>Hola {$this->nombre}, haz click en el siguiente enlace para confirmar tu cuenta:</p>";
        // cambiar la url por la variable de entorno
        $contenido .= "<p><a href=\"" . $_ENV['APP_URL'] . "/confirmar?token={$this->token}\">Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si no fuiste vos, ignora este mensaje</p>";
        $contenido .= "</html>";

        // Contenido del email
        $mail->Body = $contenido; 

        // Enviar el email
        $mail->send();
    }

    // Enviar el email de recuperación
    public function enviarInstrucciones() {
        // Crear el objeto del email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        // Configurar el contenido del email
        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Reestablece tu contraseña';

        // Set HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        // Habilitar HTML
        $contenido = "<html>";
        $contenido .= "<p>Hola {$this->nombre}, haz click en el siguiente enlace para reestablecer tu contraseña:</p>";
        $contenido .= "<p><a href=\"" . $_ENV['APP_URL'] . "/recuperar?token={$this->token}\">Reestablecer Contraseña</a></p>";
        $contenido .= "<p>Si no fuiste vos, ignora este mensaje</p>";
        $contenido .= "</html>";

        // Contenido del email
        $mail->Body = $contenido; 

        // Enviar el email
        $mail->send();
    }
}