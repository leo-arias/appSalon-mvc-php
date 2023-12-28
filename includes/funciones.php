<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

// Revisa si el elemento actual es el último de la lista
function esUltimo(string $actual, string $proximo) : bool {
    if($actual !== $proximo) {
        return true;
    } else {
        return false;
    }
}

// Revisa que el usuario haya iniciado sesión
function isAuth() : void {
    if(!isset($_SESSION['login'])) {
        header('Location: /');
    }
}

// Revisa que el usuario sea administrador
function isAdmin() : void {
    if(!isset($_SESSION['admin'])) {
        header('Location: /');
    }
}