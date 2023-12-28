<?php
// Muestra los mensajes

switch($resultado) {
    case 1:
        ?> 
            <h1 class="nombre-pagina">Confirma tu cuenta</h1>
            <p class="descripcion-pagina">Enviamos las instrucciones para confirmar tu cuenta a tu email</p>
        <?php
        break;
    case 2:
        ?> 
            <h1 class="nombre-pagina">Contraseña actualizada</h1>
            <p class="descripcion-pagina">Ya puedes iniciar sesión</p>

            <a class="boton" href="/">Iniciar Sesión</a>
        <?php
        break;
    case 3:

    default:
        break;
}

?>