<?php
// Archivo: modules/admin/dashboard.php
include("../../config/bd.php");
include("../../includes/autenticacion.php");
include("../../includes/verificar_rol.php");

// Solo rol 1 (Admin) puede entrar aquí
verificarRol([1]);

include("../../includes/header.php");
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold text-dark">Panel de Administrador</h2>
        <p class="text-muted">Gestiona usuarios, roles y permisos del sistema.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4">
                <div class="display-4 text-primary mb-3"><i class="bi bi-people-fill"></i></div>
                <h4 class="card-title fw-bold">Usuarios</h4>
                <p class="card-text text-muted small">Crear, editar o eliminar cuentas.</p>
                <a href="usuarios/index.php" class="btn btn-outline-primary w-100">Gestionar</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4">
                <div class="display-4 text-success mb-3"><i class="bi bi-shield-lock-fill"></i></div>
                <h4 class="card-title fw-bold">Roles</h4>
                <p class="card-text text-muted small">Definir roles (Admin, Docente, etc).</p>
                <a href="roles/index.php" class="btn btn-outline-success w-100">Gestionar</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4">
                <div class="display-4 text-warning mb-3"><i class="bi bi-key-fill"></i></div>
                <h4 class="card-title fw-bold">Permisos</h4>
                <p class="card-text text-muted small">Asignar accesos a cada rol.</p>
                <a href="permisos/index.php" class="btn btn-outline-warning w-100">Gestionar</a>
            </div>
        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>