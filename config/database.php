<?php
// Configuración de MySQL para Bella Imagen.
class Database
{
    private string $host = 'localhost';
    private string $name = 'bella_imagen_db';
    private string $user = 'root';
    private string $password = '';

    public function conectar(): PDO
    {
        return new PDO(
            "mysql:host={$this->host};dbname={$this->name};charset=utf8mb4",
            $this->user,
            $this->password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }
}
