<?php
require_once __DIR__ . '/BaseModel.php';

class Producto extends BaseModel
{
    protected string $tabla = 'productos';
    protected array $columnas = ['nombre', 'categoria', 'unidad', 'stock', 'stock_minimo', 'precio'];
}
