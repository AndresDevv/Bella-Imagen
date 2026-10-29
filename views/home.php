<?php
require_once __DIR__ . '/../controller/BaseController.php';
exigirSesion();
require __DIR__ . '/layout.php';

$imagenes = [
    'clientes' => 'photo-1524504388940-b1c1722653e1',
    'especialistas' => 'photo-1522335789203-aabd1fc54bc9',
    'productos' => 'photo-1608248543803-ba4f8c70ae0b',
    'servicios' => 'photo-1487412947147-5cebf100ffc2',
    'citas' => 'photo-1521737711867-e3b97375f902',
    'atenciones' => 'photo-1600334129128-685c5582fd35',
    'tratamientos' => 'photo-1570172619644-dfd03ed5d881',
    'pagos' => 'photo-1556742049-0cfed4f6a45d',
    'reportes' => 'photo-1556761175-b413da4baf72',
];

layoutStart('Panel de control');
?>
<p>Organiza las reservas, el equipo, los servicios, las atenciones y los pagos de Bella Imagen.</p>
<div class="cards">
    <?php foreach (modulos() as $id => $modulo): ?>
        <a class="card" href="<?= e($modulo['url']) ?>">
            <img
                loading="lazy"
                alt="<?= e($modulo['nombre']) ?>"
                src="https://images.unsplash.com/<?= e($imagenes[$id]) ?>?w=700&amp;q=75"
            >
            <div>
                <h2><?= e($modulo['nombre']) ?></h2>
                <span>Administrar registros →</span>
            </div>
        </a>
    <?php endforeach; ?>
</div>
<?php layoutEnd(); ?>
