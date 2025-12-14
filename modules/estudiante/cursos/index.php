<?php
// Archivo: modules/estudiante/cursos/index.php
include("../../../config/bd.php");
include("../../../includes/autenticacion.php");
include("../../../includes/verificar_rol.php");

verificarRol([2]); // Solo Estudiantes

$idEstudiante = $_SESSION['user_id'];

// 1. Procesar Inscripción
if($_POST && isset($_POST['id_curso_inscribir'])){
    $idCurso = $_POST['id_curso_inscribir'];
    
    // Verificar si ya está inscrito para evitar duplicados
    $check = $conexion->prepare("SELECT ID FROM inscripciones WHERE IdCurso=:c AND IdEstudiante=:e");
    $check->bindParam(":c", $idCurso);
    $check->bindParam(":e", $idEstudiante);
    $check->execute();
    
    if(!$check->fetch()){
        $insert = $conexion->prepare("INSERT INTO inscripciones (IdCurso, IdEstudiante) VALUES (:c, :e)");
        $insert->bindParam(":c", $idCurso);
        $insert->bindParam(":e", $idEstudiante);
        $insert->execute();
        header("Location: ../mis-cursos/index.php?mensaje=¡Inscripción exitosa!");
        exit;
    }
}

// 2. Obtener IDs de cursos donde YA estoy inscrito (para bloquear el botón)
$sqlInscritos = $conexion->prepare("SELECT IdCurso FROM inscripciones WHERE IdEstudiante = :id");
$sqlInscritos->bindParam(":id", $idEstudiante);
$sqlInscritos->execute();
$mis_inscripciones = $sqlInscritos->fetchAll(PDO::FETCH_COLUMN); // Array simple de IDs [1, 5, 8...]

// 3. Listar TODOS los cursos activos
// Usamos JOIN para mostrar el nombre del docente
$sql = "SELECT c.*, u.Nick as Docente 
        FROM cursos c 
        INNER JOIN usuario u ON c.IdDocente = u.ID 
        WHERE c.Estado = 1 
        ORDER BY c.ID DESC";
$stmt = $conexion->prepare($sql);
$stmt->execute();
$cursos_disponibles = $stmt->fetchAll(PDO::FETCH_ASSOC);

include("../../../includes/header.php");
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h2 class="fw-bold text-dark">Catálogo de Cursos</h2>
        <p class="text-muted">Explora y únete a nuevas clases.</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="../mis-cursos/index.php" class="btn btn-outline-primary">Ir a Mis Clases</a>
    </div>
</div>

<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php foreach($cursos_disponibles as $curso): 
        $yaInscrito = in_array($curso['ID'], $mis_inscripciones);
    ?>
    <div class="col">
        <div class="card h-100 shadow-sm border-0 hover-shadow">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                <span class="badge bg-info text-dark mb-2">Curso</span>
                <h5 class="card-title fw-bold text-primary mb-0"><?php echo $curso['Titulo']; ?></h5>
            </div>
            <div class="card-body px-4">
                <p class="card-text text-muted small mb-3">
                    <?php echo substr($curso['Descripcion'], 0, 100); ?>...
                </p>
                <div class="d-flex align-items-center text-muted small">
                    <i class="bi bi-person-circle me-2"></i>
                    <span>Prof. <?php echo $curso['Docente']; ?></span>
                </div>
            </div>
            <div class="card-footer bg-white border-0 px-4 pb-4">
                <?php if($yaInscrito): ?>
                    <button class="btn btn-secondary w-100" disabled>
                        <i class="bi bi-check-circle-fill me-2"></i>Ya estás inscrito
                    </button>
                <?php else: ?>
                    <form method="post">
                        <input type="hidden" name="id_curso_inscribir" value="<?php echo $curso['ID']; ?>">
                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            Inscribirse Gratis
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php include("../../../includes/footer.php"); ?>