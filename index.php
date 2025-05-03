<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Pokedex/database/MyDatabase.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Pokedex/clases/Mensaje.php';

$db = new MyDatabase();
$busqueda = '';
$pokemones = [];
$tipo_id = isset($_GET['tipo_id']) ? $_GET['tipo_id'] : '';

if (isset($_GET['busqueda'])) {
    $busqueda = $_GET['busqueda'];
    $query = "SELECT * FROM pokemon WHERE nombre LIKE '%$busqueda%' OR numero_identificador = '$busqueda'";
} else {
    $query = "SELECT * FROM pokemon";
}

if ($busqueda != '' || $tipo_id != '') {
    $query = "SELECT DISTINCT p.* FROM pokemon p 
              LEFT JOIN pokemon_tipo pt ON p.id = pt.pokemon_id ";

    $where = [];

    if ($busqueda != '') {
        $where[] = "(p.nombre LIKE '%$busqueda%' OR p.numero_identificador = '$busqueda')";
    }

    if ($tipo_id != '') {
        $where[] = "pt.tipo_id = '$tipo_id'";
    }

    $query .= "WHERE " . implode(" AND ", $where);
} else {
    $query = "SELECT * FROM pokemon";
}

$pokemones = $db->query($query);
?>

<?php require $_SERVER['DOCUMENT_ROOT'] . '/Pokedex/head.php'; ?>
<?php require $_SERVER['DOCUMENT_ROOT'] .  '/Pokedex/navbar.php'; ?>

<!-- Dump de la sesión para ver contenido -->
<?php //var_dump($_SESSION); ?>
<main class="bg-light">
<section class="pt-3 pt-md-5 pt-xl-8" id="listado">


<!-- Título principal -->
<div class="logotipo d-flex justify-content-center align-items-center text-center my-2">
    <img loading="lazy" src="img/assets/pokeball.png" width="80" height="80" alt="Listado pokemon">
    <h1 class="display-4 m-4">Listado de Pokémon</h1>
</div>

<?php Mensaje::mostrar(); ?>

<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
    <div class="container-fluid mb-4 px-5">
        <div style="display: flex; align-items: center; gap: 10px;" class="d-flex flex-column flex-md-row align-items-stretch gap-2">

            <form method="GET" action="index.php" style="flex-grow: 1;" class="flex-grow-1 d-flex gap-2 flex-column flex-md-row align-items-stretch">
                <div class="search-bar flex-grow-1">
                    <div class="input-group">
                        <input type="text" name="busqueda" class="form-control" placeholder="Buscar pokémon por nombre o número identificador..."
                            aria-label="Search" aria-describedby="search-addon"
                            value="<?= htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8') ?>">
                        <select name="tipo_id" class="form-select border-0 bg-light" style="max-width: 200px;">
                            <option value="">Todos los tipos</option>
                            <?php
                            $tipos = $db->query("SELECT * FROM tipo");
                            foreach ($tipos as $tipo) {
                                $selected = (isset($_GET['tipo_id']) && $_GET['tipo_id'] == $tipo['id']) ? 'selected' : '';
                                echo "<option value='{$tipo['id']}' $selected>{$tipo['nombre']}</option>";
                            }
                            ?>
                        </select>
                        <button class="btn btn-secondary" type="submit" id="search-addon">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>

            <?php if (isset($_SESSION['usuario_id'])): ?>
                <a href="./Admin/crear.php" class="agregar-pokemon btn btn-danger rounded-pill shadow-sm" style="min-width: 200px;">
                    <i class="fas fa-plus me-2"></i> Agregar Pokémon
                </a>
            <?php endif; ?>

        </div>
    </div>
</div>
<!-- Listado de Pokemones o mensaje -->
<?php

if ($pokemones && count($pokemones) > 0) {
    echo "<div class='container-fluid px-5 pt-4'>";
    echo "<div class='row'>";
    foreach ($pokemones as $pokemon) {
        echo "<div class='col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-5'>";
        echo "<a href='./vistaPokedex.php?id={$pokemon['id']}' class='text-decoration-none text-dark'>"; // Enlace que envuelve la tarjeta
        echo "<div class='pokemon-card text-center p-4 pt-5 h-100 bg-white rounded-4 position-relative'>";
        echo "<span class='badge bg-danger rounded-pill text-white id-circle py-3 px-4' title='ID del Pokémon'>N.º {$pokemon['numero_identificador']}</span>";
        echo "<div class='pokemon-img-wrapper mb-2'>";
        echo "<img src='img/{$pokemon['imagen']}' alt='{$pokemon['nombre']}' class='img-fluid'>";
        echo "</div>";
        echo "<h4 class='nombre-pokemon-card'>{$pokemon['nombre']}</h4><br>";
        echo "</a>";
        if (isset($_SESSION['usuario_id'])) {
            echo "<a href='./Admin/actualizar.php?id={$pokemon['id']}' class='btn btn-secondary btn-block col-md-12 m-1'>
               <i class='fas fa-pen me-1'></i> Modificar
            </a>";

            echo "<a href= './Admin/eliminar.php?id={$pokemon['id']}' class='btn btn-danger btn-block col-md-12 m-1'>
               <i class='fas fa-trash me-1'></i> Eliminar
            </a>";
        }
        echo "  </div>";
        echo "</div>";
    }
    echo "</div>";
    echo "</div>";
} else {
    if ($busqueda != '') {
        echo "<div class='alert alert-danger mx-5' role='alert'>No se encontraron pokémon que coincidan en nombre o numero identificador con: <strong>'$busqueda'</strong>.</div>";
    } else {
        echo "<div class='alert alert-danger mx-5' role='alert'>No hay pokémon registrados.</div>";
    }
}

?>
</section>
</main>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const tipoSelect = document.querySelector("select[name='tipo_id']");
    const form = tipoSelect.closest("form");

    tipoSelect.addEventListener("change", function () {
        form.submit();
    });
});
</script>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/Pokedex/footer.php'; ?>
