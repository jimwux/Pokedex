<?php
session_start();
?>

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm px-3 poke-nav bg-gris-oscuro">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center gap-2" href="./index.php">
            <img src="/Pokedex/img/assets/pokeball.png" alt="Logo" width="40" height="40" loading="lazy">
            <span class="fs-4 fw-bold">PokeWeb</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <div class="navbar-nav mb-2 mb-lg-0 d-flex flex-row">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <div class="nav-item">
                        <span class="nav-link fw-bold">
                            <i class="fas fa-user me-2" ></i> <?= $_SESSION['usuario_nombre'] ?>
                        </span>
                    </div>
                    <div class="nav-item">
                        <a href="./logout.php" class="btn btn-light ms-lg-3">Cerrar sesión</a>
                    </div>
                <?php else: ?>
                    <div class="nav-item">
                        <a href="./login.php" class="btn btn-light ms-lg-3">Iniciar sesión</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
