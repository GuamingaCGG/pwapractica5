<?php
session_start();
require_once 'config/conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$rol_id = $_SESSION['rol_id'];
$usuario_id = $_SESSION['usuario_id'];
$mensaje = "";

// --- PROCESAMIENTO ---
if (isset($_POST['crear_usuario']) && $rol_id == 1) {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $pass = mysqli_real_escape_string($conexion, $_POST['password']);
    $nuevo_rol = intval($_POST['rol_id']);
    
    mysqli_query($conexion, "INSERT INTO usuarios (nombre, email, contraseña, rol_id) VALUES ('$nombre', '$email', '$pass', $nuevo_rol)");
    $mensaje = "Usuario creado con éxito.";
}

if (isset($_POST['crear_tarea']) && $rol_id == 2) {
    $titulo = mysqli_real_escape_string($conexion, $_POST['titulo']);
    $desc = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $asignado_a = intval($_POST['usuario_id']);
    
    mysqli_query($conexion, "INSERT INTO tareas (titulo, descripcion, usuario_id) VALUES ('$titulo', '$desc', $asignado_a)");
    $mensaje = "Tarea asignada correctamente.";
}

if (isset($_POST['actualizar_estado'])) {
    $tarea_id = intval($_POST['tarea_id']);
    $nuevo_estado = mysqli_real_escape_string($conexion, $_POST['estado']);
    
    mysqli_query($conexion, "UPDATE tareas SET estado = '$nuevo_estado' WHERE id = $tarea_id");
    $mensaje = "Estado de la tarea actualizado.";
}

include 'includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <?php if ($mensaje != ""): ?>
            <div class="alert alert-success alert-dismissible fade show"><?php echo $mensaje; ?></div>
        <?php endif; ?>
    </div>
</div>

<?php if ($rol_id == 1): 
    $usuarios_query = mysqli_query($conexion, "SELECT u.*, r.rol_nombre FROM usuarios u JOIN roles r ON u.rol_id = r.rol_id");
?>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Crear Nuevo Usuario</div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="mb-2"><label class="small">Nombre</label><input type="text" name="nombre" class="form-control form-control-sm" required></div>
                        <div class="mb-2"><label class="small">Email</label><input type="email" name="email" class="form-control form-control-sm" required></div>
                        <div class="mb-2"><label class="small">Contraseña</label><input type="password" name="password" class="form-control form-control-sm" required></div>
                        <div class="mb-3">
                            <label class="small">Rol</label>
                            <select name="rol_id" class="form-select form-select-sm">
                                <option value="1">Administrador</option>
                                <option value="2">Gerente de proyecto</option>
                                <option value="3">Miembro del equipo</option>
                            </select>
                        </div>
                        <button type="submit" name="crear_usuario" class="btn btn-primary btn-sm w-100">Guardar Usuario</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Usuarios en el Sistema</div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0 small">
                        <thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th></tr></thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($usuarios_query)): ?>
                            <tr><td><?php echo $row['id']; ?></td><td><?php echo $row['nombre']; ?></td><td><?php echo $row['email']; ?></td><td><span class="badge bg-secondary"><?php echo $row['rol_nombre']; ?></span></td></tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php elseif ($rol_id == 2): 
    $miembros_query = mysqli_query($conexion, "SELECT id, nombre FROM usuarios WHERE rol_id = 3");
    $tareas_query = mysqli_query($conexion, "SELECT t.*, u.nombre FROM tareas t JOIN usuarios u ON t.usuario_id = u.id");
?>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">Crear Tarea</div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="mb-2"><label class="small">Título</label><input type="text" name="titulo" class="form-control form-control-sm" required></div>
                        <div class="mb-2"><label class="small">Descripción</label><textarea name="descripcion" class="form-control form-control-sm" rows="3"></textarea></div>
                        <div class="mb-3">
                            <label class="small">Asignar a</label>
                            <select name="usuario_id" class="form-select form-select-sm">
                                <?php while($m = mysqli_fetch_assoc($miembros_query)): ?>
                                    <option value="<?php echo $m['id']; ?>"><?php echo $m['nombre']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <button type="submit" name="crear_tarea" class="btn btn-success btn-sm w-100">Asignar Tarea</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Panel de Tareas</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 small">
                        <thead><tr><th>Título</th><th>Asignado A</th><th>Estado</th></tr></thead>
                        <tbody>
                            <?php while($t = mysqli_fetch_assoc($tareas_query)): ?>
                            <tr><td><strong><?php echo $t['titulo']; ?></strong><br><span class="text-muted"><?php echo $t['descripcion']; ?></span></td><td><?php echo $t['nombre']; ?></td><td><span class="badge <?php echo $t['estado']=='Completado'?'bg-success':($t['estado']=='En proceso'?'bg-warning':'bg-danger'); ?>"><?php echo $t['estado']; ?></span></td></tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php elseif ($rol_id == 3): 
    $mis_tareas = mysqli_query($conexion, "SELECT * FROM tareas WHERE usuario_id = $usuario_id");
?>
    <div class="row">
        <div class="col-12">
            <h4 class="mb-3">Mis Tareas Asignadas</h4>
            <?php if (mysqli_num_rows($mis_tareas) == 0): ?>
                <div class="alert alert-info">No tienes tareas asignadas.</div>
            <?php else: ?>
                <div class="row">
                <?php while($mt = mysqli_fetch_assoc($mis_tareas)): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm border-start border-4 <?php echo $mt['estado']=='Completado'?'border-success':($mt['estado']=='En proceso'?'border-warning':'border-danger'); ?>">
                            <div class="card-body">
                                <h5><?php echo $mt['titulo']; ?></h5>
                                <p class="text-muted small"><?php echo $mt['descripcion']; ?></p>
                                <form action="" method="POST" class="d-flex align-items-center mt-3">
                                    <input type="hidden" name="actualizar_estado" value="1">
                                    <input type="hidden" name="tarea_id" value="<?php echo $mt['id']; ?>">
                                    <select name="estado" class="form-select form-select-sm me-2">
                                        <option value="Pendiente" <?php echo $mt['estado']=='Pendiente'?'selected':''; ?>>Pendiente</option>
                                        <option value="En proceso" <?php echo $mt['estado']=='En proceso'?'selected':''; ?>>En proceso</option>
                                        <option value="Completado" <?php echo $mt['estado']=='Completado'?'selected':''; ?>>Completado</option>
                                    </select>
                                    <button type="submit" class="btn btn-dark btn-sm">Actualizar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>