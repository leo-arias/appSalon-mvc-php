<?php

namespace Controllers;

use Model\Cita;
use Model\Servicio;
use Model\CitaServicio;

class APIController {
    public static function index() {
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }

    public static function guardar() {
        // Alamcena la Cita y devuelve el ID
        $cita = new Cita($_POST); // Crear nueva cita
        $resultado = $cita->guardar(); // Guardar en la base de datos

        $id = $resultado['id']; // Obtener el ID de la cita

        // Alamcena los Servicios con el ID de la Cita
        $idServicios = explode(',', $_POST['servicios']);

        foreach($idServicios as $idServicio) {
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }

        // Enviar respuesta
        echo json_encode(['resultado' => $resultado]); 
    }

    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id']; // Obtener el ID de la cita
            
            $cita = Cita::find($id); // Buscar la cita en la base de datos

            $cita->eliminar(); // Eliminar la cita

            header('Location:' . $_SERVER['HTTP_REFERER']); // Redireccionar a la página anterior
        }
    }
}