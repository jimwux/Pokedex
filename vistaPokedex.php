
<?php require $_SERVER['DOCUMENT_ROOT'] . '/Pokedex/head.php'; ?>
<?php require $_SERVER['DOCUMENT_ROOT'] .  '/Pokedex/navbar.php'; ?>

<?php
require_once ($_SERVER['DOCUMENT_ROOT']. "/Pokedex/database/MyDatabase.php");
// require './database/MyDatabase.php';
$db = new MyDatabase();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT * FROM pokemon WHERE id = $id";
$pokemon = $db->query($sql);
$pokemon = $pokemon[0] ?? null; // obtiene el primer resultado

// Consulta los tipos
$sqlTipos = "SELECT t.nombre FROM tipo t
             JOIN pokemon_tipo pt ON pt.tipo_id = t.id
             WHERE pt.pokemon_id = $id";
$tipos = $db->query($sqlTipos);
?>

<section class="py-3 py-md-5 py-xl-8 tamanio-pantalla" id="vista-detalle">

    <a class="btn btn-secondary mt-3 ms-4 mb-2" href="./index.php">❮ Volver atrás</a>

    <div class="container mt-5">
        <?php if ($pokemon): ?>
            <div class="card shadow-lg rounded-4 bg-white p-4 pe-5">
                <div class="row align-items-center py-5">
                    <!-- Imagen del Pokémon -->
                    <div class="col-md-5 text-center mb-4 mb-md-0">
                        <img src="img/<?= htmlspecialchars($pokemon['imagen']) ?>" alt="<?= htmlspecialchars($pokemon['nombre']) ?>" class="img-fluid" style="max-height: 400px;">
                    </div>

                    <!-- Detalles del Pokémon -->
                    <div class="col-md-7">
                        <span class="badge bg-danger rounded-pill text-white mt-3 px-4 py-2 mb-4" style="font-size: 1.5rem;">
                            N.º <?= htmlspecialchars($pokemon['numero_identificador']) ?>
                        </span>
                        <h2 class="mb-4 fw-bold"><?= htmlspecialchars($pokemon['nombre']) ?></h2>

                        <h5 class="text-muted">Descripción</h5>
                        <p><?= htmlspecialchars($pokemon['descripcion']) ?></p>

                        <h5 class="mt-4 text-muted">Tipo/s:</h5>
                        <div class="mb-3">
                            <?php foreach ($tipos as $tipo): ?>
                                <img src="img/tipos/tipo-<?= htmlspecialchars($tipo['nombre']) ?>.png"
                                     alt="<?= htmlspecialchars($tipo['nombre']) ?>"
                                     class="me-2"
                                     style="height: 36px;">
                            <?php endforeach; ?>
                        </div>

                        <?php if (isset($_SESSION['usuario_id'])): ?>
                            <div class="d-flex flex-column flex-md-row gap-3 mt-5">
                                <a href='./Admin/actualizar.php?id=<?= $pokemon['id'] ?>' class='btn btn-secondary w-100 py-2 fw-bold'>
                                    <i class='fas fa-pen me-2'></i>Modificar
                                </a>
                                <a href='./Admin/eliminar.php?id=<?= $pokemon['id'] ?>' class='btn btn-danger w-100 py-2 fw-bold'>
                                    <i class='fas fa-trash me-2'></i>Eliminar
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger text-center" role="alert">
                Pokémon no encontrado.
            </div>
        <?php endif; ?>
    </div>
</section>
<div class="session-margin"></div>

<?php require $_SERVER['DOCUMENT_ROOT'] . '/Pokedex/footer.php'; ?>
