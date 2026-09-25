<?php
session_start();
$host = $_SESSION['servername'] ?? '';
$user = $_SESSION['username'] ?? '';
$password = $_SESSION['password'] ?? '';
$database = $_POST['database'] ?? '';
$table = $_POST['table'] ?? '';
$recordValue = $_POST['record_value'] ?? '';

// Conexión a la base de datos
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}


$updateFields = [];
foreach ($_POST as $key => $value) {
    if ($key !== 'database' && $key !== 'table' && $key !== 'record_value') {
        $updateFields[] = "$key = '" . $conn->real_escape_string($value) . "'";
    }
}
$updateSql = "UPDATE $table SET " . implode(", ", $updateFields) . " WHERE $firstColumn = '$recordValue'";

if ($conn->query($updateSql) === TRUE) {
    echo "<script>
            alert('Registro modificado con éxito.');
            window.location.href = 'records.php?database=" . urlencode($database) . "&table=" . urlencode($table) . "';
          </script>";
} else {
    echo "<script>
            alert('Error al modificar el registro: " . $conn->error . "');
            window.location.href = 'records.php?database=" . urlencode($database) . "&table=" . urlencode($table) . "';
          </script>";
}

$conn->close();
?>