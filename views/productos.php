<?php
require_once __DIR__ . '/../controller/ProductoController.php';

exigirSesion();
$controller = new ProductoController();
$error = $controller->procesar();

$edit = isset($_GET['editar'])
    ? $controller->modelo->obtener((int) $_GET['editar'])
    : false;

$rows = $controller->modelo->listar();
$campos = $controller->campos;
$titulo = $controller->titulo;

require __DIR__ . '/productos/index.php';
