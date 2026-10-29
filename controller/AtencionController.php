<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/Atencion.php';

class AtencionController
{
    public Atencion $modelo;

    public array $campos = [
        'cita_id' => 'citas',
        'descripcion' => 'textarea',
        'recomendaciones' => 'textarea',
        'fecha' => 'date',
    ];

    public string $titulo = 'Atenciones';

    public function __construct()
    {
        $this->modelo = new Atencion(db());
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
                $productos = is_array($_POST['productos_ids'] ?? null) ? $_POST['productos_ids'] : [];
                $this->modelo->guardarConProductos($datos, $id, $productos);
            }

            notificar('Operación realizada correctamente');
            redirigir('atenciones.php');
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            return 'No se pudo completar la operación. Verifique referencias, duplicados y datos.';
        } catch (Throwable $ex) {
            return $ex->getMessage();
        }
    }
}
