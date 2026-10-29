<?php
require_once __DIR__ . '/BaseModel.php';

class Especialista extends BaseModel
{
    protected string $tabla = 'especialistas';
    protected array $columnas = ['nombre', 'especialidad', 'habilidades', 'telefono', 'email'];
}
