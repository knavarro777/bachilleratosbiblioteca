<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['database'])) {
    $selectedDB = $_POST['database'];
    session_start(); // Iniciar la sesión

    $servername = $_SESSION['servername'] ?? '';
    $username = $_SESSION['username'] ?? '';
    $password = $_SESSION['password'] ?? '';
    
    $conn = new mysqli($servername, $username, $password, $selectedDB);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "SHOW TABLES";
    $result = $conn->query($sql);
} else {
    die("Acceso no permitido.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Seleccionar Tabla</title>
    <style>
        body {
            font-family: 'Times New Roman', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            margin: 0;
            text-align: center;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin-top: 20px;
        }

        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px; 
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
            margin-bottom: 10px;
        }

        .buttons {
            display: inline;
            justify-content: space-between;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
            padding: 10px;
            cursor: pointer;
            font-size: 16px;
            width: 48%; 
        }

        button:hover {
            background-color: #45a049;
        }

        .back-button {
            background-color: #800000;
        }

        .back-button:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>
    <h1>Base de Datos: <?php echo htmlspecialchars($selectedDB); ?></h1>
    <div class="form-container">
    <form method="POST" action="records.php">
        <input type="hidden" name="database" value="<?php echo htmlspecialchars($selectedDB); ?>">
        <label for="table">Seleccione una Tabla:</label>
        <select name="table" id="table" required>
            <?php
            while ($row = $result->fetch_row()) {
                echo "<option value='" . $row[0] . "'>" . $row[0] . "</option>";
            }
            ?>
        </select>
            <div class="buttons">
                <button type="submit">Siguiente</button>
                
        
        </form>
        <form method="POST" action="showTables.php" style="display: inline;">
                <input type="hidden" name="database" value="<?php echo htmlspecialchars($selectedDB); ?>">
                <button type="submit" class="back-button">Atrás</button>
        </form>
        </div>
    </div>
</body>
</html>
<?php
$conn->close();
?>