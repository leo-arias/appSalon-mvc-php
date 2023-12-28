<h1 class="nombre-pagina">Olvide Password</h1>
<p class="descripcion-pagina">Si olvidaste tu password, ingresa tu email y te enviaremos un enlace para recuperarlo</p>

<?php
    include_once __DIR__ . '/../templates/alertas.php';
?>

<form action="/olvide" class="formulario" method="POST">
    <div class="campo">
        <label for="email">Email: </label>
        <input type="email" name="email" id="email" placeholder="Tu Email">
    </div>

    <input type="submit" value="Enviar Instrucciones" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tenes cuenta? Inicia Sesión</a>
    <a href="/crear-cuenta">¿Aún no tenes una cuenta? Crear Una</a>
</div>