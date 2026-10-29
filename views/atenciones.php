<?php
require_once __DIR__ . '/../controller/AtencionController.php';

exigirSesion();
$controller = new AtencionController();
$error = $controller->procesar();

$edit = isset($_GET['editar'])
    ? $controller->modelo->obtener((int) $_GET['editar'])
    : false;

$rows = $controller->modelo->listar();
$campos = $controller->campos;
$titulo = $controller->titulo;

require __DIR__ . '/atenciones/index.php';
