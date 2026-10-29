<?php
require_once __DIR__ . '/BaseModel.php';

class Cliente extends BaseModel
{
    protected string $tabla = 'clientes';
    protected array $columnas = ['nombre', 'telefono', 'email', 'direccion', 'documento'];
}
