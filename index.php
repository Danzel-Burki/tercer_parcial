<?php
include 'includes/conexion.php';


$sql_pendientes = "SELECT * FROM tareas WHERE completada = FALSE AND eliminado = FALSE ORDER BY fecha_creacion DESC";
$result_pendientes = mysqli_query($conn, $sql_pendientes);


$sql_completadas = "SELECT * FROM tareas WHERE completada = TRUE AND eliminado = FALSE ORDER BY fecha_completada DESC";
$result_completadas = mysqli_query($conn, $sql_completadas);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']);
    $archivo_adjunto = NULL;

    
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
     
        $file_type = mime_content_type($_FILES['archivo']['tmp_name']);
        if ($file_type == 'application/pdf') {
 
            $archivo_adjunto = 'uploads/' . $_FILES['archivo']['name'];
            move_uploaded_file($_FILES['archivo']['tmp_name'], $archivo_adjunto);
        } else {
          
            echo "Solo se permiten archivos PDF.";
            exit();
        }
    }

    
    $sql = "INSERT INTO tareas (nombre, descripcion, archivo_adjunto, fecha_creacion) 
            VALUES ('$nombre', '$descripcion', '$archivo_adjunto', NOW())";
    mysqli_query($conn, $sql);
    
    header("Location: index.php");
    exit();
}


if (isset($_GET['completar'])) {
    $id = $_GET['completar'];
    $sql = "UPDATE tareas SET completada = TRUE, fecha_completada = NOW() WHERE id = $id";
    mysqli_query($conn, $sql);
    header("Location: index.php");
    exit();
}


if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];

   
    $sql_select = "SELECT * FROM tareas WHERE id = $id";
    $result_select = mysqli_query($conn, $sql_select);
    $tarea = mysqli_fetch_assoc($result_select);

    
    if ($tarea['archivo_adjunto']) {
        $archivo_ruta = $tarea['archivo_adjunto']; 

        if (file_exists($archivo_ruta)) {
            unlink($archivo_ruta); 
        }
    }

   
    $sql_insert_eliminada = "INSERT INTO tareas_eliminadas (id, nombre, archivo_adjunto, fecha_creacion, fecha_completada, fecha_eliminacion, descripcion)
                             VALUES ('{$tarea['id']}', '{$tarea['nombre']}', '{$tarea['archivo_adjunto']}', '{$tarea['fecha_creacion']}', '{$tarea['fecha_completada']}', NOW(), '{$tarea['descripcion']}')";
    mysqli_query($conn, $sql_insert_eliminada);

    $sql_delete = "UPDATE tareas SET eliminado = TRUE WHERE id = $id";
    mysqli_query($conn, $sql_delete);

    header("Location: index.php");
    exit();
}


if (isset($_GET['ver_pdf'])) {
    $id = $_GET['ver_pdf'];

    $sql = "SELECT archivo_adjunto FROM tareas WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $archivo_adjunto);
    mysqli_stmt_fetch($stmt);

   
    if ($archivo_adjunto) {
  
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="tarea.pdf"');
        echo file_get_contents($archivo_adjunto);
    } else {
        echo "No se ha encontrado el archivo PDF.";
    }

    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Tareas</title>
    <link rel="stylesheet" href="./css/estilos.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Gestor de Tareas</h1>

    <h2>Agregar Nueva Tarea</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="nombre">Nombre de la tarea:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="descripcion">Descripción de la tarea (opcional):</label>
        <textarea id="descripcion" name="descripcion" rows="4" cols="50" placeholder="Descripción de la tarea (opcional)"></textarea>

        <label for="archivo">Adjuntar archivo (opcional):</label>
        <input type="file" id="archivo" name="archivo" accept="application/pdf">

        <button type="submit">Agregar tarea</button>
    </form>


    <h2>Tareas Pendientes</h2>
    <ul id="lista-pendientes">
        <?php while ($tarea = mysqli_fetch_assoc($result_pendientes)): ?>
            <li id="tarea-<?php echo $tarea['id']; ?>">
                <strong><?php echo htmlspecialchars($tarea['nombre']); ?></strong> - Fecha: <?php echo htmlspecialchars($tarea['fecha_creacion']); ?>
                <div>
                    <a href="?completar=<?php echo $tarea['id']; ?>">Completar</a>
                    <a href="?eliminar=<?php echo $tarea['id']; ?>">Eliminar</a>
                    <?php if ($tarea['archivo_adjunto']): ?>
                        <a href="?ver_pdf=<?php echo $tarea['id']; ?>">Ver PDF</a>
                    <?php endif; ?>
                </div>
            </li>
        <?php endwhile; ?>
    </ul>

    <h2>Tareas Completadas</h2>
    <ul>
        <?php while ($tarea = mysqli_fetch_assoc($result_completadas)): ?>
            <li>
                <strong><?php echo htmlspecialchars($tarea['nombre']); ?></strong> - Completada el <?php echo htmlspecialchars($tarea['fecha_completada']); ?>
            </li>
        <?php endwhile; ?>
    </ul>

</body>
</html>
