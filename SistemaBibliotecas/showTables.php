<?php
session_start(); // Iniciar la sesión

$servername = $_SESSION['servername'] ?? '';
$username = $_SESSION['username'] ?? '';
$password = $_SESSION['password'] ?? '';

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Recuperar las bases de datos disponibles
$sql = "SHOW DATABASES";
$result = $conn->query($sql);

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Seleccionar Base de Datos</title>
    <style>
        body {
            font-family: 'Times New Roman', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: auto;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 16px;
        }
        button {
            background-color: #4CAF50; 
            color: white;
            padding: 10px 15px; 
            border: none; 
            border-radius: 5px;
            cursor: pointer; 
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Seleccionar Base de Datos</h1>
    
    <form method="POST" action="tables.php">
        <label for="database">Seleccione una Base de Datos:</label><br>
        <select name="database" id="database" required>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['Database'] . "'>" . $row['Database'] . "</option>";
            }
            ?>
        </select>
        <button type="submit">Siguiente</button>
    </form>
</body>
</html>
        </div>
        <?php
$conn->close();
?>


