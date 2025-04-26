<?php include './head.php'; ?>
<?php include './navbar.php'; ?>

<?php
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

include './clases/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirm_password'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if (empty($password)) {
            $error = "La contraseña no puede estar vacía.";
        } elseif ($password !== $confirm_password) {
            $error = "Las contraseñas no coinciden.";
        } else {
            $usuarioRegistrado = Usuario::registrar($username, $email, $password);

            if ($usuarioRegistrado === true) {
                header("Location: login.php");
                exit;
            } else {
                switch ($usuarioRegistrado) {
                    case 'email_exists':
                        $error = "Ya existe un usuario registrado con ese email.";
                        break;
                    case 'insert_error':
                        $error = "Ocurrió un error al registrar el usuario. Intente de nuevo.";
                        break;
                    case 'password_empty':
                        $error = "La contraseña no puede estar vacía.";
                        break;
                    default:
                        $error = "Error desconocido.";
                        break;
                }
            }
        }
    } else {
        $error = "Por favor complete todos los campos.";
    }
}

?>

<section class="py-3 py-md-5 py-xl-8 session" id="register">
    <div class="container">
        <div class="row gy-4 align-items-center">
            <div class="col-12 col-md-6 col-xl-7">
                <div class="d-flex justify-content-center text-light">
                    <div class="col-12 col-xl-9">
                        <div class="logotipo">
                            <img loading="lazy" src="img/assets/pokeball.png" width="140" height="140" alt="BootstrapBrain Logo">
                            <h1 class="display-1 m-4">PokeWeb</h1>
                        </div>
                        <hr class="border-primary-subtle mb-4">
                        <h2 class="h1 mb-4">Unite a miles de entrenadores en su aventura</h2>
                        <p class="lead mb-5">Gestioná, explorá y atrapá Pokémon en tu propia Pokédex virtual.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-5">
                <div class="card border-0 rounded-4">
                    <div class="card-body p-3 p-md-4 p-xl-5">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-4">
                                    <h3>Registrarse</h3>

                                    <?php if (isset($error)): ?>
                                        <div class="alert alert-danger mt-3">
                                            <?= htmlspecialchars($error) ?>
                                        </div>
                                    <?php endif; ?>

                                    <p>¿Ya tenés una cuenta? <a href="login.php">Inicia sesión</a></p>
                                </div>
                            </div>
                        </div>
                        <form action="" method="POST">
                            <div class="row gy-3 overflow-hidden">
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="username" id="username" placeholder="Tu nombre de usuario" required>
                                        <label for="username" class="form-label">Nombre de usuario</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" name="email" id="email" placeholder="nombre@ejemplo.com" required>
                                        <label for="email" class="form-label">Email</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" required>
                                        <label for="password" class="form-label">Contraseña</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirmar contraseña" required>
                                        <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                        <label class="form-check-label text-secondary" for="terms">
                                            Acepto los <a href="#!">términos y condiciones</a>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="d-grid">
                                        <button class="btn btn-danger btn-lg" type="submit">Registrarse</button>
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



<?php include './footer.php'; ?>
