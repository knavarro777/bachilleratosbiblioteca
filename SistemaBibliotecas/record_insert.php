<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['database'], $_POST['table'])) {
    $database = $_POST['database'];
    $table = $_POST['table'];
    session_start(); // Iniciar la sesión

    $host = $_SESSION['servername'] ?? '';
    $user = $_SESSION['username'] ?? '';
    $password = $_SESSION['password'] ?? '';

    $conn = new mysqli($host, $user, $password, $database);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }


    $columns = array_keys($_POST);
    $values = array_values($_POST);

    $sql = "INSERT INTO $table (" . implode(', ', array_slice($columns, 2)) . ") VALUES (" . str_repeat('?, ', count($values) - 3) . "?)";
    $stmt = $conn->prepare($sql);

    $types = str_repeat('s', count($values) - 2);
    $params = array_slice($values, 2);

    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo "Registro insertado exitosamente.";
    } else {
        echo "Error al insertar el registro: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
