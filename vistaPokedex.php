
<?php include './head.php'; ?>
<?php include './navbar.php';
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

/*var_dump($_SESSION)*/

?>


<a class="w3-left w3-btn" href="index.php">❮ Volver atrás</a>
<div class="pokedex-container">
    <div class="pokemon-container">
        <img class="pokemon-img" src="img/<?= htmlspecialchars($pokemon['imagen']) ?>" alt="chosen-pokemon">
        <h3 class="pokemon-name"><?php if ($pokemon): ?>
                <p><?= htmlspecialchars($pokemon['nombre']) ?></p>
            <?php else: ?>
                <p>Pokémon no encontrado.</p>
            <?php endif; ?></h3>
    </div>
    <div class="pokemon-information">
        <h2>Descripción</h2>
        <?php if ($pokemon): ?>
            <p><?= htmlspecialchars($pokemon['descripcion']) ?></p>
            <footer class="pokemon-type">
                Tipo/s:
                <?php foreach ($tipos as $tipo): ?>
                    <img class="type-img" src="img/tipos/tipo-<?= htmlspecialchars($tipo['nombre']) ?>.png" alt="<?= htmlspecialchars($tipo['nombre']) ?>">
                <?php endforeach; ?>
            </footer>
        <?php else: ?>
            <p>No se encontró información del Pokémon.</p>
        <?php endif; ?>
    </div>
</div>


<!-- <p class="w3-allerta"></p>
-->

<?php include './footer.php'; ?>
