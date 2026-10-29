<?php
require_once __DIR__ . '/BaseModel.php';

class Atencion extends BaseModel
{
    protected string $tabla = 'atenciones';
    protected array $columnas = ['cita_id', 'descripcion', 'recomendaciones', 'fecha'];

    public function guardarConProductos(array $datos, int $id, array $productos): void
    {
        $this->db->beginTransaction();
        try {
            $this->guardar($datos, $id);
            $atencionId = $id ?: (int) $this->db->lastInsertId();

            if ($id > 0) {
                $anteriores = $this->db->prepare('SELECT producto_id, cantidad FROM atencion_productos WHERE atencion_id = ?');
                $anteriores->execute([$atencionId]);
                foreach ($anteriores->fetchAll() as $anterior) {
                    $restaurar = $this->db->prepare('UPDATE productos SET stock = stock + ? WHERE id = ?');
                    $restaurar->execute([$anterior['cantidad'], $anterior['producto_id']]);
                }
                $borrar = $this->db->prepare('DELETE FROM atencion_productos WHERE atencion_id = ?');
                $borrar->execute([$atencionId]);
            }

            foreach (array_unique(array_map('intval', $productos)) as $productoId) {
                if ($productoId < 1) continue;
                $actualizar = $this->db->prepare('UPDATE productos SET stock = stock - 1 WHERE id = ? AND stock >= 1');
                $actualizar->execute([$productoId]);
                if ($actualizar->rowCount() !== 1) {
                    throw new RuntimeException('No hay stock disponible para uno de los productos seleccionados.');
                }
                $detalle = $this->db->prepare('INSERT INTO atencion_productos (atencion_id, producto_id, cantidad) VALUES (?, ?, 1)');
                $detalle->execute([$atencionId, $productoId]);
            }
            $this->db->commit();
        } catch (Throwable $error) {
            $this->db->rollBack();
            throw $error;
        }
    }

    public function eliminar(int $id): void
    {
        $this->db->beginTransaction();
        try {
            $consulta = $this->db->prepare('SELECT producto_id, cantidad FROM atencion_productos WHERE atencion_id = ?');
            $consulta->execute([$id]);
            foreach ($consulta->fetchAll() as $detalle) {
                $restaurar = $this->db->prepare('UPDATE productos SET stock = stock + ? WHERE id = ?');
                $restaurar->execute([$detalle['cantidad'], $detalle['producto_id']]);
            }
            parent::eliminar($id);
            $this->db->commit();
        } catch (Throwable $error) {
            $this->db->rollBack();
            throw $error;
        }
    }
}
