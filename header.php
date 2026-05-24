<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Tareas - UBE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php if (isset($_SESSION['usuario_id'])): ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Gestor Tareas</a>
            <div class="navbar-nav ms-auto">
                <span class="nav-item nav-link text-white me-3">
                    Hola, <strong><?php echo $_SESSION['usuario_nombre']; ?></strong> (<?php echo $_SESSION['rol_nombre']; ?>)
                </span>
                <a class="btn btn-danger btn-sm" href="logout.php">Cerrar Sesión</a>
            </div>
        </div>
    </nav>
<?php endif; ?>
<div class="container">