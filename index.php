<?php
session_start();
require_once 'config/conexion.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT u.*, r.rol_nombre FROM usuarios u 
            JOIN roles r ON u.rol_id = r.rol_id 
            WHERE u.email = '$email' AND u.contraseña = '$password'";
            
    $resultado = mysqli_query($conexion, $sql);

    if (mysqli_num_rows($resultado) == 1) {
        $usuario = mysqli_fetch_assoc($resultado);
        
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['rol_id'] = $usuario['rol_id'];
        $_SESSION['rol_nombre'] = $usuario['rol_nombre'];

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "El correo o la contraseña no son correctos.";
    }
}
include 'includes/header.php';
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Iniciar Sesión</h3>
                
                <?php if ($error != ""): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" name="email" id="inputEmail" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" id="inputPassword" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-3">Entrar</button>
                </form>

                <div class="border-top pt-3 mt-2">
                    <p class="text-center text-muted small mb-2"><strong>Accesos rápidos:</strong></p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="cargarCredenciales('admin@correo.com', '123456')">
                             Entrar como Administrador
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="cargarCredenciales('gerente@correo.com', '123456')">
                             Entrar como Gerente
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="cargarCredenciales('miembro@correo.com', '123456')">
                             Entrar como Miembro
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
function cargarCredenciales(correo, clave) {
    document.getElementById('inputEmail').value = correo;
    document.getElementById('inputPassword').value = clave;
}
</script>

<?php include 'includes/footer.php'; ?>