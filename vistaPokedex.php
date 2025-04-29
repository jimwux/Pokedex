<?php

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Allerta+Stencil">
    <link rel="stylesheet" href="pokedexStyle2.css">
    <title>TejerinaTobias</title>
</head>
<body>

<?php include './head.php'; ?>
<?php include './navbar.php';
/*var_dump($_SESSION)*/?>


<a class="w3-left w3-btn" href="index.php">❮ Volver atrás</a>
<div class="pokedex-container">
    <div class="pokemon-container">
        <img class="pokemon-img" src="img/charizard.png" alt="chosen-pokemon">
        <h3 class="pokemon-name">Charizard</h3>
    </div>
    <div class="pokemon-information">
        <h2>Descripción</h2>
        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Odio quos cum hic perferendis quis suscipit eius quae vitae repudiandae repellendus dolorem, itaque modi consequuntur animi.</p>
        <footer class="pokemon-type">tipo/s : <img class="type-img" src="img/tipos/tipo-fuego.png" alt=""> <img class="type-img" src="img/tipos/tipo-volador.png" alt=""></footer>
    </div>
</div>


<!-- <p class="w3-allerta"></p>
-->

<?php include './footer.php'; ?>
</body>
</html>
