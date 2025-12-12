<?php
// Archivo: dashboard.php
include("includes/autenticacion.php");
include("config/bd.php");
include("includes/header.php");
?>

<div class="row mb-4">
    <div class="col-12">
        <h1 class="display-5 fw-bold text-dark">Hola, <?php echo $_SESSION['nick']; ?> 👋</h1>
        <p class="lead text-muted">Bienvenido al panel de control del sistema.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4">
                <div class="display-4 text-primary mb-3"><i class="bi bi-people-fill"></i></div>
                <h4 class="card-title">Usuarios</h4>
                <p class="card-text text-muted small">Administra los usuarios, crea nuevas cuentas y asigna roles.</p>
                <a href="modules/usuarios/index.php" class="btn btn-outline-primary w-100">Gestionar Usuarios</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4">
                <div class="display-4 text-success mb-3"><i class="bi bi-shield-lock-fill"></i></div>
                <h4 class="card-title">Roles</h4>
                <p class="card-text text-muted small">Define los roles del sistema (Admin, Estudiante, Docente).</p>
                <a href="modules/roles/index.php" class="btn btn-outline-success w-100">Gestionar Roles</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4">
                <div class="display-4 text-warning mb-3"><i class="bi bi-key-fill"></i></div>
                <h4 class="card-title">Permisos</h4>
                <p class="card-text text-muted small">Controla qué puede hacer cada rol en el sistema.</p>
                <a href="modules/permisos/index.php" class="btn btn-outline-warning w-100">Gestionar Permisos</a>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>