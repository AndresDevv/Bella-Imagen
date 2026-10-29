<?php
require_once __DIR__ . '/../controller/BaseController.php';
exigirSesion();
require __DIR__ . '/layout.php';

$desde = $_GET['desde'] ?? date('Y-m-01');
$hasta = $_GET['hasta'] ?? date('Y-m-d');
foreach (['desde' => $desde, 'hasta' => $hasta] as $campo => $fecha) {
    $validada = DateTime::createFromFormat('!Y-m-d', $fecha);
    if (!$validada || $validada->format('Y-m-d') !== $fecha) {
        ${$campo} = $campo === 'desde' ? date('Y-m-01') : date('Y-m-d');
    }
}

$consultaIngresos = db()->prepare("SELECT COALESCE(SUM(monto),0) FROM pagos WHERE estado='pagado' AND DATE(fecha_pago) BETWEEN ? AND ?");
$consultaIngresos->execute([$desde, $hasta]);
$ingresos = (float) $consultaIngresos->fetchColumn();
$consultaCitas = db()->prepare("SELECT estado, COUNT(*) AS total FROM citas WHERE DATE(fecha_hora) BETWEEN ? AND ? GROUP BY estado");
$consultaCitas->execute([$desde, $hasta]);
$citasEstado = $consultaCitas->fetchAll(PDO::FETCH_KEY_PAIR);
$consultaServicios = db()->prepare("SELECT s.nombre, COUNT(c.id) AS total FROM citas c JOIN servicios s ON s.id=c.servicio_id WHERE c.estado='atendida' AND DATE(c.fecha_hora) BETWEEN ? AND ? GROUP BY s.id,s.nombre ORDER BY total DESC LIMIT 10");
$consultaServicios->execute([$desde, $hasta]);
$serviciosTop = $consultaServicios->fetchAll();

layoutStart('Reportes de gestión');
?>
<p>Resumen de ingresos, servicios más solicitados y estado de las citas.</p>
<section class="panel">
    <form method="get" class="form-grid">
        <label>Desde<input type="date" name="desde" value="<?= e($desde) ?>" required></label>
        <label>Hasta<input type="date" name="hasta" value="<?= e($hasta) ?>" required></label>
        <div class="actions"><button type="submit">Actualizar reporte</button></div>
    </form>
</section>
<section class="cards">
    <article class="panel"><h2>Ingresos registrados</h2><strong>S/ <?= e(number_format($ingresos, 2)) ?></strong><p>Pagos completados en el periodo</p></article>
    <article class="panel"><h2>Citas atendidas</h2><strong><?= e($citasEstado['atendida'] ?? 0) ?></strong></article>
    <article class="panel"><h2>Citas canceladas</h2><strong><?= e($citasEstado['cancelada'] ?? 0) ?></strong></article>
</section>
<section class="panel">
    <h2>Servicios más solicitados</h2>
    <div class="table-scroll"><table><thead><tr><th>Servicio</th><th>Atenciones</th></tr></thead><tbody>
    <?php foreach ($serviciosTop as $fila): ?><tr><td><?= e($fila['nombre']) ?></td><td><?= e($fila['total']) ?></td></tr><?php endforeach; ?>
    <?php if (!$serviciosTop): ?><tr><td colspan="2">No hay atenciones en este periodo.</td></tr><?php endif; ?>
    </tbody></table></div>
</section>
<?php layoutEnd(); ?>
