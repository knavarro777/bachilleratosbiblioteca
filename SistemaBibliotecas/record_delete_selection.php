<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['database'], $_POST['table'], $_POST['selectedRows'])) { echo 'Entró en el método POST y las variables están definidas.'; } else { die("Acceso no permitido. Variables: " . print_r($_POST, true)); }
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['database'], $_POST['table'], $_POST['selectedRows'])) {
    $database = $_POST['database'];
    $table = $_POST['table'];
    $selectedRows = $_POST['selectedRows']; // Recibir las filas seleccionadas como JSON
    
    session_start(); // Iniciar la sesión
    $host = $_SESSION['servername'] ?? '';
    $user = $_SESSION['username'] ?? '';
    $password = $_SESSION['password'] ?? '';

    if (empty($selectedRows)) {
        die("No se seleccionaron registros para eliminar.");
    }

    // Conexión a la base de datos
    $conn = new mysqli($host, $user, $password, $database);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $deletedCount = 0;
    foreach ($selectedRows as $rowJson) {
        $row = json_decode($rowJson, true); // Decodificar la fila seleccionada
        $conditions = [];
        foreach ($row as $column => $value) {
            $conditions[] = "$column = '" . $conn->real_escape_string($value) . "'";
        }
        $whereClause = implode(' AND ', $conditions);
        $sql = "DELETE FROM $table WHERE $whereClause";

        if ($conn->query($sql)) {
            $deletedCount++;
        }
    }

    echo "$deletedCount registros eliminados exitosamente.";
    $conn->close();
    header("Location: records.php?database=$database&table=$table");
    exit;
} else {
    die("Acceso no permitido.");
}
?>
