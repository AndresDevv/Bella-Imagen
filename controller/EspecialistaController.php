<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/Especialista.php';

class EspecialistaController
{
    public Especialista $modelo;

    public array $campos = [
        'nombre' => 'text',
        'especialidad' => 'text',
        'habilidades' => 'textarea',
        'telefono' => 'text',
        'email' => 'email',
    ];

    public string $titulo = 'Especialistas';

    public function __construct()
    {
        $this->modelo = new Especialista(db());
    }

    public function procesar(): string
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return '';
        }

        try {
            validarCsrf();
            $id = filter_var(
                $_POST['id'] ?? 0,
                FILTER_VALIDATE_INT,
                ['options' => ['min_range' => 1]]
            ) ?: 0;

            if (($_POST['accion'] ?? '') === 'eliminar') {
                $this->modelo->eliminar($id);
            } else {
                $datos = validarDatos($this->campos, $_POST);
                $this->modelo->guardar($datos, $id);
            }

            notificar('Operación realizada correctamente');
            redirigir('especialistas.php');
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            return 'No se pudo completar la operación. Verifique referencias, duplicados y datos.';
        } catch (Throwable $ex) {
            return $ex->getMessage();
        }
    }
}
