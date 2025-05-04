<?php require $_SERVER['DOCUMENT_ROOT'] . '/Pokedex/head.php'; ?>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/Pokedex/navbar.php'; ?>


<?php
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

require $_SERVER['DOCUMENT_ROOT'] . '/Pokedex/clases/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email'], $_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $usuario = Usuario::verificarLogin($email, $password);

        if ($usuario) {
            $_SESSION['usuario_id'] = $usuario->getId();
            $_SESSION['usuario_nombre'] = $usuario->getUsername();
            $_SESSION['usuario_email'] = $usuario->getEmail();

            header("Location: index.php");
            exit;
        } else {
            $error = "Credenciales incorrectas.";
        }
    } else {
        $error = "Por favor complete todos los campos.";
    }
}
?>

<section class="py-3 py-md-5 py-xl-8 tamanio-pantalla session" id="login">
    <div class="container">
        <div class="row gy-4 align-items-center">
            <div class="col-12 col-md-6 col-xl-7">
                <div class="d-flex justify-content-center text-light">
                    <div class="col-12 col-xl-9">
                        <div class="logotipo">
                            <img loading="lazy" src="img/assets/pokeball.png" width="130" height="130" alt="Pokeweb">
                            <h1 class="display-3 m-4">PokeWeb</h1>
                        </div>
                        <hr class="border-primary-subtle mb-4">
                        <h2 class="h1 mb-4">Unite a miles de entrenadores en su aventura</h2>
                        <p class="lead mb-5">Gestioná, explorá y atrapá Pokémon en tu propia Pokédex virtual.</p>
                        <div class="text-endx">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-grip-horizontal" viewBox="0 0 16 16">
                                <path d="M2 8a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-5">
                <div class="card border-0 rounded-4">
                    <div class="card-body p-3 p-md-4 p-xl-5">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-4">
                                    <h3>Iniciar sesión</h3>

                                    <?php if (isset($error)): ?>
                                        <div class="alert alert-danger mt-3">
                                            <?= htmlspecialchars($error) ?>
                                        </div>
                                    <?php endif; ?>

                                    <p>No tenés una cuenta? <a href="./register.php">Registrate</a></p>
                                </div>
                            </div>
                        </div>
                        <form method="POST" action="">
                            <div class="row gy-3 overflow-hidden">
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" name="email" id="email" placeholder="nombre@ejemplo.com" required>
                                        <label for="email" class="form-label">Email</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control" name="password" id="password" value="" placeholder="Password" required>
                                        <label for="password" class="form-label">Contraseña</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-grid">
                                        <button class="btn btn-danger btn-lg" type="submit">Iniciar sesión ahora</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="session-margin"></div>

<?php require $_SERVER['DOCUMENT_ROOT'] . '/Pokedex/footer.php'; ?>
