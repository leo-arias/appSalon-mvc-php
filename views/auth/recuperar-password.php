<h1 class="nombre-pagina">Recuperar Password</h1>
<p class="descripcion-pagina">Ingresa tu nueva contraseña</p>

<?php
    include_once __DIR__ . '/../templates/alertas.php';
?>

<?php if($error) return; ?>

<form class="formulario" method="POST">
    <div class="campo">
        <label for="password">Contraseña: </label>
        <input type="password" name="password" id="password" placeholder="Tu Nueva Contraseña">
    </div>

    <input type="submit" value="Guardar Contraseña" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tenes cuenta? Inicia Sesión</a>
    <a href="/crear-cuenta">¿Aún no tenes una cuenta? Crear Una</a>
</div>