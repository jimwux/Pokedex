<?php
include_once 'database/MyDatabase.php';

$db = new MyDatabase();
$busqueda = '';
$pokemones = [];

if (isset($_GET['busqueda'])) {
    $busqueda = $_GET['busqueda'];
    $query = "SELECT * FROM pokemon WHERE nombre LIKE '%$busqueda%' OR numero_identificador = '$busqueda'";
} else {
    $query = "SELECT * FROM pokemon";
}

$pokemones = $db->query($query);
?>

<html>
<body>

<?php include './head.php'; ?>
<?php include './navbar.php'; ?>

<!-- Dump de la sesión para ver contenido -->
<?php var_dump($_SESSION); ?>

<!-- Título principal -->
<h1>Listado de Pokemones</h1>

<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">

    <form method="GET" action="index.php" style="display: flex; align-items: right; gap: 5px; margin: 10;">
        <input type="text" name="busqueda" placeholder="Buscar Pokémon..." value="<?php echo $busqueda; ?>">
        <button type="submit">Buscar</button>
    </form>

</div>
<!-- Listado de Pokemones o mensaje -->
<?php
foreach ($pokemones as $pokemon) {
  echo "<div class='card'>";
  echo "<img src='img/{$pokemon['imagen']}' alt='{$pokemon['nombre']}' style='width:100px; height:100px;'><br>";
  echo "<strong>{$pokemon['nombre']}</strong><br>";
  if (isset($_SESSION['usuario_id'])) {
    echo "<a href='Admin/eliminar.php?id={$pokemon['id']}' style='color: red; margin-right: 10px;'>Eliminar</a>";
    echo "<a href='Admin/actualizar.php?id={$pokemon['id']}'>Modificar</a>";
    }
  echo "</div>";
}
if (!count($pokemones) > 0) {
  
  if ($busqueda != '') {
    echo "<div style='color:red; font-weight:bold;'>No se encontraron pokémon que coincidan en nombre o numero identificador con: '$busqueda'.</div>";
} else {
    echo "<div style='color:red; font-weight:bold;'>No hay pokémon registrados.</div>";
}
}
?>

<?php include './footer.php'; ?>

</body>
</html>

<!-- Jime despues cambia el estilo como te guste -->
<style>
    .card {
        border: 1px solid #ccc;
        padding: 10px;
        display: inline-block;
        margin: 10px;
        text-align: center;
        border-radius: 5px;
    }

    .card a {
        text-decoration: none;
        font-weight: bold;
    }

    .card a:hover {
        text-decoration: underline;
    }
</style>