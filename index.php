
<?php
// Conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'pokedex');

if ($conexion->connect_error) {
    die('Error de conexión: ' . $conexion->connect_error);
}

// Inicializamos variables
$pokemones = [];
$busqueda = '';

// Si enviaron el formulario de búsqueda
if (isset($_GET['busqueda'])) {
    $busqueda = $_GET['busqueda'];
    $query = "SELECT * FROM pokemon WHERE nombre LIKE '$busqueda%'";
} else {
    // Si no buscaron nada, mostrar todos
    $query = "SELECT * FROM pokemon";
}

$resultado = $conexion->query($query);

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $pokemones[] = $fila;
    }
}
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

    <form action="./Admin/crear.php" method="GET" style="margin: 10;">
        <button type="submit">Agregar Nuevo Pokemon</button>
    </form>

    <form action="./Admin/eliminar.php" method="GET" style="margin: 10;">
        <button type="submit">Eliminar Pokemon</button>
    </form>

    <form method="GET" action="index.php" style="display: flex; align-items: right; gap: 5px; margin: 10;">
        <input type="text" name="busqueda" placeholder="Buscar Pokémon..." value="<?php echo $busqueda; ?>">
        <button type="submit">Buscar</button>
    </form>

</div>
<!-- Listado de Pokemones o mensaje -->
<?php
if (count($pokemones) > 0) {
    foreach ($pokemones as $pokemon) {
        echo "<div style='border:1px solid #ccc; padding:10px; margin-bottom:10px;'>";
        echo "<h2>" . $pokemon['nombre'] . "</h2>";
        echo "</div>";
    }
} else {
    if ($busqueda != '') {
        echo "<div style='color:red; font-weight:bold;'>No se encontraron pokémon que coincidan con '$busqueda'.</div>";
    } else {
        echo "<div style='color:red; font-weight:bold;'>No hay pokémon registrados.</div>";
    }
}
?>

<?php include './footer.php'; ?>

</body>
</html>