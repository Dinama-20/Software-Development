<?php

namespace App\Models;

class User
{
    public function register($data)
    {
        // Aquí iría la lógica para validar y almacenar los datos del usuario en la base de datos
        // Si todo es correcto, devuelve verdadero, si no, falso.
        return true;
    }

    public function login($credentials)
    {
        // Lógica para autenticar al usuario con sus credenciales
        return true;
    }
}
