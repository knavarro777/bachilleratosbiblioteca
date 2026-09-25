<?php
if (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['database'], $_POST['table'])) || 
    ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['database'], $_GET['table'], $_GET['record']))) {
    
    $selectedDB = $_POST['database'] ?? $_GET['database'];
    $selectedTable = $_POST['table'] ?? $_GET['table'];
    $recordIndex = isset($_GET['record']) ? (int)$_GET['record'] : 0;

    session_start(); // Iniciar la sesión

    $host = $_SESSION['servername'] ?? '';
    $user = $_SESSION['username'] ?? '';
    $password = $_SESSION['password'] ?? '';

    $conn = new mysqli($host, $user, $password, $selectedDB);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Obtención del total de registros
    $totalSql = "SELECT COUNT(*) as total FROM $selectedTable";
    $totalResult = $conn->query($totalSql);
    $totalRecords = $totalResult->fetch_assoc()['total'];

    // Recuperación del registro actual
    $sql = "SELECT * FROM $selectedTable LIMIT $recordIndex, 1";
    $result = $conn->query($sql);
    $record = $result->fetch_assoc();

    // Recuperación de metadatos
    $metaSql = "
        SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_KEY 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = '$selectedDB' AND TABLE_NAME = '$selectedTable'";
    $metaResult = $conn->query($metaSql);
    $fields = [];
    while ($metaRow = $metaResult->fetch_assoc()) {
        $fields[] = $metaRow;
    }
} else {
    die("Acceso no permitido.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Registro</title>
    <style>
        body {
            font-family: 'Times New Roman', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
            padding: 20px;
            padding-top: 80px;
        }
        h1 {
            color: #333;
        }
        form {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"] {
            width: 95%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .navigation {
            margin: 20px 0;
        }
        .navigation a, .back-button {
            display: inline-block;
            background-color: #4CAF50; 
            color: white;
            padding: 10px 15px; 
            text-decoration: none; 
            border-radius: 5px; 
            margin: 0 5px; 
            transition: background-color 0.3s;
        }
        .navigation a:hover, .back-button:hover {
            background-color: #45a049; 
        }
        .back-button {
            background-color: #f44336; /* Rojo para diferenciar */
        }
        .back-button:hover {
            background-color: #e53935;
        }
        .back-link {
            margin-top: 20px;
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Detalle de Registro: Tabla <?php echo htmlspecialchars($selectedTable); ?></h1>
    <form>
        <?php foreach ($fields as $field): ?>
            <label for="<?php echo htmlspecialchars($field['COLUMN_NAME']); ?>">
                <?php echo htmlspecialchars($field['COLUMN_NAME']); ?>:
            </label>
            <input 
                type="text" 
                id="<?php echo htmlspecialchars($field['COLUMN_NAME']); ?>" 
                name="<?php echo htmlspecialchars($field['COLUMN_NAME']); ?>" 
                value="<?php echo htmlspecialchars($record[$field['COLUMN_NAME']] ?? ''); ?>" 
                <?php echo $field['IS_NULLABLE'] === 'NO' ? 'required' : ''; ?>
                <?php echo $field['COLUMN_KEY'] === 'PRI' ? 'readonly' : ''; ?>>
            <br>
        <?php endforeach; ?>
    </form>

    <div class="navigation">
        <a href="?record=0&database=<?php echo urlencode($selectedDB); ?>&table=<?php echo urlencode($selectedTable); ?>">Primero</a>
        <a href="?record=<?php echo max(0, $recordIndex - 1); ?>&database=<?php echo urlencode($selectedDB); ?>&table=<?php echo urlencode($selectedTable); ?>">Anterior</a>
        <a href="?record=<?php echo min($totalRecords - 1, $recordIndex + 1); ?>&database=<?php echo urlencode($selectedDB); ?>&table=<?php echo urlencode($selectedTable); ?>">Siguiente</a>
        <a href="?record=<?php echo $totalRecords - 1; ?>&database=<?php echo urlencode($selectedDB); ?>&table=<?php echo urlencode($selectedTable); ?>">Último</a>
    </div>


    <a href="records.php?database=<?php echo urlencode($selectedDB); ?>&table=<?php echo urlencode($selectedTable); ?>" class="back-button">Atrás</a>
</body>
</html>
<?php
$conn->close();
?>