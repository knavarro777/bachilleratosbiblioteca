<?php
if (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['database'], $_POST['table'])) || 
    ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['database'], $_GET['table'], $_GET['page']))) {

    $selectedDB = $_POST['database'] ?? $_GET['database'];
    $selectedTable = $_POST['table'] ?? $_GET['table'];
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $recordsPerPage = 10;
    $offset = ($page - 1) * $recordsPerPage;

    session_start(); // Iniciar la sesión

    $host = $_SESSION['servername'] ?? '';
    $user = $_SESSION['username'] ?? '';
    $password = $_SESSION['password'] ?? '';

    $conn = new mysqli($host, $user, $password, $selectedDB);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

  
    // Total de registros
    $totalSql = "SELECT COUNT(*) as total FROM $selectedTable";
    $totalResult = $conn->query($totalSql);
    $totalRecords = $totalResult->fetch_assoc()['total'];
    $totalPages = ceil($totalRecords / $recordsPerPage);

    // Recuperar registros con límite
    $sql = "SELECT * FROM $selectedTable LIMIT $offset, $recordsPerPage";
    $result = $conn->query($sql);

    // Procesar consulta personalizada
    
}

if (isset($_POST['execute_query']) && isset($_POST['custom_query'])) {
    $customQuery = trim($_POST['custom_query']);

    // Validación básica
    if (stripos($customQuery, 'SELECT') !== 0) {
        echo "<p style='color: red;'>Solo se permiten consultas de tipo SELECT.</p>";
    } else {
        $prohibitedWords = ['DROP', 'TRUNCATE', 'ALTER'];
        foreach ($prohibitedWords as $word) {
            if (stripos($customQuery, $word) !== false) {
                die("<p style='color: red;'>Consulta no permitida: se detectaron palabras peligrosas.</p>");
            }
        }

        // Ejecutar consulta y mostrar resultados una vez
        $customResult = $conn->query($customQuery);
        if ($customResult) {
            echo "<h2>Resultados de la Consulta:</h2>";
            echo "<table border='1'>";
            echo "<tr>";

            // Mostrar encabezados
            while ($fieldInfo = $customResult->fetch_field()) {
                echo "<th>" . htmlspecialchars($fieldInfo->name) . "</th>";
            }
            echo "</tr>";

            // Mostrar filas
          while ($row = $customResult->fetch_assoc()) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>" . htmlspecialchars($value) . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: red;'>Error en la consulta: " . htmlspecialchars($conn->error) . "</p>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registros de la Tabla</title>
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
        table {
            width: 100%;
            max-width: 800px;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        input[type="text"], textarea {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        button {
            background-color: #4CAF50; /* Color de fondo */
            color: white; /* Color del texto */
            padding: 10px 15px; /* Espaciado interno */
            border: none; /* Sin borde */
            border-radius: 5px; /* Bordes redondeados */
            cursor: pointer; /* Cambia el cursor al pasar el mouse */
            transition: background-color 0.3s; /* Transición suave para el hover */
        }
        button:hover {
            background-color: #45a049; /* Color más oscuro al pasar el mouse */
        }
        .navigation {
            margin: 20px 0;
        }
        .navigation a {
            display: inline-block;
            background-color: #4CAF50; /* Color de fondo */
            color: white; /* Color del texto */
            padding: 10px 15px; /* Espaciado interno */
            text-decoration: none; /* Sin subrayado */
            border-radius: 5px; /* Bordes redondeados */
            margin: 0 5px; /* Espaciado entre enlaces */
            transition: background-color 0.3s; /* Transición suave para el hover */
        }
        .navigation a:hover {
            background-color: #45a049; /* Color más oscuro al pasar el mouse */
        }
        .button-container {
            display: inline-block; /* Los elementos hijos se alinearán en una fila */
            margin-top: 20px; /* Separación superior */
        }

        .back-link {
            margin-top: 20px;
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
            padding: 10px 15px;
            border: 1px solid green;
            border-radius: 5px;
            display: inline-block;
            transition: background-color 0.3s, color 0.3s;
        }

        .back-link:hover {
            background-color: #45a049;
            color: white;
        }

        .back-button {
            background: none;
            color: maroon;
            border: 1px solid maroon;
            padding: 10px 15px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s, color 0.3s;
            display: inline-block;
        }

        .back-button:hover {
            background-color: maroon;
            color: white;
        }
    </style>
</head>
<body>
    <h1>Registros de la Tabla: 
        
    
        
        <?php 
    $selectedDB = $_POST['database'] ?? $_GET['database'];
    $selectedTable = $_POST['table'] ?? $_GET['table'];
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $recordsPerPage = 10;
    $offset = ($page - 1) * $recordsPerPage;
    echo htmlspecialchars($selectedTable); 
    ?></h1>
 <table border="1">
    <thead>
        <tr>
            <!--<th style="width: 5%;">Seleccionar</th> Encabezado para la columna de checkboxes -->
            <?php
            $selectedDB = $_POST['database'] ?? $_GET['database'];
            $selectedTable = $_POST['table'] ?? $_GET['table'];
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $recordsPerPage = 10;
            $offset = ($page - 1) * $recordsPerPage;
        
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }

            $host = $_SESSION['servername'] ?? '';
            $user = $_SESSION['username'] ?? '';
            $password = $_SESSION['password'] ?? ''; 

            $conn = new mysqli($host, $user, $password, $selectedDB);

            $sql = "SELECT * FROM $selectedTable LIMIT $offset, $recordsPerPage";
            $result = $conn->query($sql);
            $columns = $result->fetch_fields();
            /*foreach ($columns as $column) {
                echo "<th>" . htmlspecialchars($column->name) . "</th>";
            }*/
            ?>
        </tr>
    </thead>
    <tbody>
    <form method="POST" action="record_update.php"> <!-- Cambia 'record_action.php' por el archivo que maneja tanto la modificación como la eliminación -->
    <input type="hidden" name="database" value="<?php echo htmlspecialchars($selectedDB); ?>">
    <input type="hidden" name="table" value="<?php echo htmlspecialchars($selectedTable); ?>">
    <table border="1">
        <thead>
            <tr>
                <th style="width: 5%;">Seleccionar</th>
                <?php
                $columns = $result->fetch_fields();
                foreach ($columns as $column) {
                    echo "<th>" . htmlspecialchars($column->name) . "</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td><input type='checkbox' name='selectedRows[]' value='" . htmlspecialchars(json_encode($row)) . "'></td>";
                foreach ($row as $value) {
                    echo "<td>" . htmlspecialchars($value) . "</td>";
                }
                echo "</tr>";
            }
            ?>
            <tr>
                <td colspan="<?php echo count($columns) + 1; ?>">
                    <button type="submit" name="modify" value="all" onclick="return confirm('¿Estás seguro de modificar los registros seleccionados?');">Modificar Seleccionados</button>
                    <button type="submit" name="delete" onclick="return confirm('¿Estás seguro de eliminar los registros seleccionados?');">Eliminar Seleccionados</button>
                </td>
            </tr>
        </tbody>
    </table>
</form>




    <div class="navigation">
    <a href="records.php?page=1&database=<?php echo urlencode($selectedDB); ?>&table=<?php echo urlencode($selectedTable); ?>">Primera</a>
    <a href="records.php?page=<?php echo max(1, $page - 1); ?>&database=<?php echo urlencode($selectedDB); ?>&table=<?php echo urlencode($selectedTable); ?>">Anterior</a>
    <a href="records.php?page=<?php echo min($totalPages, $page + 1); ?>&database=<?php echo urlencode($selectedDB); ?>&table=<?php echo urlencode($selectedTable); ?>">Siguiente</a>
    <a href="records.php?page=<?php echo $totalPages; ?>&database=<?php echo urlencode($selectedDB); ?>&table=<?php echo urlencode($selectedTable); ?>">Última</a>
</div>


    <form method="POST" action="record_details.php">
        <input type="hidden" name="database" value="<?php echo htmlspecialchars($selectedDB); ?>">
        <input type="hidden" name="table" value="<?php echo htmlspecialchars($selectedTable); ?>">
        <button type="submit">Ver Vista de Registro Individual</button><br>
    </form>

    <form method="POST" action="records.php">
    <input type="hidden" name="database" value="<?php echo htmlspecialchars($selectedDB); ?>">
    <input type="hidden" name="table" value="<?php echo htmlspecialchars($selectedTable); ?>">
    <br>
    <label class="cen" for="custom_query">Consulta SQL Personalizada:</label>
    <textarea id="custom_query" name="custom_query" rows="4" cols="50" placeholder="Escriba su consulta SQL de tipo SELECT aquí..."></textarea>
    <br>
    <button type="submit" name="execute_query">Ejecutar Consulta</button>



    
</form>
<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['delete_action'])) {
    // Mostrar el formulario de inserción
    echo "<form method='POST' action='record_insert.php'>";
    echo "<input type='hidden' name='database' value='" . htmlspecialchars($selectedDB) . "'>";
    echo "<input type='hidden' name='table' value='" . htmlspecialchars($selectedTable) . "'>";

    // Mostrar los campos para la inserción
    $sql = "DESCRIBE $selectedTable";
    $result = $conn->query($sql);
    while ($column = $result->fetch_assoc()) {
        echo "<label for='{$column['Field']}'>" . htmlspecialchars($column['Field']) . ":</label>";
        echo "<input type='text' name='{$column['Field']}'><br>";
    }
    echo "<button type='submit'>Insertar</button>";
    echo "</form>";
}
?>

<div class="button-container">
    <a href="index.php" class="back-link">Volver al inicio</a>
    <form method="POST" action="tables.php" style="display: inline;">
        <input type="hidden" name="database" value="<?php echo htmlspecialchars($selectedDB); ?>">
        <button type="submit" class="back-button">Atrás</button>
    </form>
</div>

    

<?php
$conn->close();
?>





