<?php
require_once __DIR__ . '/BaseModel.php';

class Servicio extends BaseModel
{
    protected string $tabla = 'servicios';
    protected array $columnas = ['nombre', 'categoria', 'descripcion', 'precio', 'duracion_min'];
}
