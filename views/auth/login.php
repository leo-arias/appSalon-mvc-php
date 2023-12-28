<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesión con tus datos</p>

<?php
    include_once __DIR__ . '/../templates/alertas.php';
?>

<form method="POST" action="/" class="formulario">
    <div class="campo">
        <label for="email">Email:</label>
        <input type="email" name="email" placeholder="Tu Email" id="email" value="<?php echo s($auth->email) ?>">
    </div>

    <div class="campo">
        <label for="password">Password:</label>
        <input type="password" name="password" placeholder="Tu Password" id="password">
    </div>

    <input type="submit" value="Iniciar Sesión" class="boton">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿No tenes cuenta? Crea una</a>
    <a href="/olvide">¿Olvidaste tu contraseña?</a>
</div>