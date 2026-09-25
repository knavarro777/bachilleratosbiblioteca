<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Registro</title>
    <style>
        /* Estilo general para la página */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Contenedor principal */
        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 80%;
            max-width: 800px;
        }

        /* Encabezado */
        h1 {
            text-align: center;
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        h3 {
            color: #333;
            font-size: 20px;
            margin-bottom: 10px;
        }

        /* Formulario */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 16px;
            color: #555;
        }

        input[type="text"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
        }

        input[type="text"]:focus {
            border-color: #4CAF50;
            outline: none;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        .form-section {
            margin-bottom: 20px;
        }

        /* Estilo de los botones de acción */
        .actions {
            display: flex;
            justify-content: space-between;
        }

        /* Estilo para los mensajes de éxito o error */
        .message {
            text-align: center;
            font-size: 18px;
            margin-top: 20px;
        }

        .message.success {
            color: #28a745;
        }

        .message.error {
            color: #dc3545;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Modificar Registro</h1>

        <!-- Formulario de modificación -->
        <?php
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['database'], $_POST['table'], $_POST['selectedRows'])) {
            $database = $_POST['database'];
            $table = $_POST['table'];
            $selectedRows = $_POST['selectedRows']; // Recibir las filas seleccionadas como JSON

            $host = $_SESSION['servername'] ?? '';
            $user = $_SESSION['username'] ?? '';
            $password = $_SESSION['password'] ?? '';

            $conn = new mysqli($host, $user, $password, $database);
            if ($conn->connect_error) {
                die("Error de conexión: " . $conn->connect_error);
            }

            if (empty($selectedRows)) {
                die("No se seleccionaron registros para modificar.");
            }

            echo "<form method='POST' action=''>";
            echo "<input type='hidden' name='database' value='" . htmlspecialchars($database) . "'>";
            echo "<input type='hidden' name='table' value='" . htmlspecialchars($table) . "'>";

            foreach ($selectedRows as $rowJson) {
                $row = json_decode($rowJson, true); // Decodificar la fila seleccionada
                echo "<div class='form-section'>";
                echo "<h3>Modificar Registro</h3>";

                // Mostrar los campos para modificación
                foreach ($row as $key => $value) {
                    echo "<label for='$key'>" . htmlspecialchars($key) . ":</label>";
                    echo "<input type='text' name='data[$key][]' value='" . htmlspecialchars($value) . "'><br>"; // Array para almacenar valores
                }

                echo "<input type='hidden' name='record_data[]' value='" . htmlspecialchars(json_encode($row)) . "'>"; // Guardar el registro original
                echo "</div>";
            }

            echo "<div class='actions'>";
            echo "<button type='submit' name='update'>Modificar</button>";
            echo "</div>";
            echo "</form>";

            $conn->close();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
            // Código para actualizar los registros
            $database = $_POST['database'];
            $table = $_POST['table'];
            $data = $_POST['data']; // Datos de los campos
            $record_data = $_POST['record_data']; // Datos originales de los registros

            $host = $_SESSION['servername'] ?? '';
            $user = $_SESSION['username'] ?? '';
            $password = $_SESSION['password'] ?? '';

            $conn = new mysqli($host, $user, $password, $database);
            if ($conn->connect_error) {
                die("Error de conexión: " . $conn->connect_error);
            }

            foreach ($record_data as $index => $originalRowJson) {
                $originalRow = json_decode($originalRowJson, true); // Decodificar el registro original
                $update_sql = "UPDATE $table SET ";
                $update_fields = [];

                // Construir la consulta de actualización
                foreach ($data as $key => $values) {
                    $newValue = $values[$index]; // Obtener el nuevo valor correspondiente
                    if ($originalRow[$key] !== $newValue) { // Solo actualizar si el valor ha cambiado
                        $update_fields[] = "$key = '" . $conn->real_escape_string($newValue) . "'";
                    }
                }

                if (!empty($update_fields)) {
                    $update_sql .= implode(", ", $update_fields) . " WHERE ";
                    $conditions = [];
                    foreach ($originalRow as $column => $value) {
                        $conditions[] = "$column = '" . $conn->real_escape_string($value) . "'";
                    }
                    $update_sql .= implode(' AND ', $conditions);

                    if ($conn->query($update_sql)) {
                        echo "<p class='message success'>Registro modificado correctamente.</p>";
                    } else {
                        echo "<p class='message error'>Error al modificar el registro: " . htmlspecialchars($conn->error) . "</p>";
                    }
                }
            }

            $conn->close();

            // Redirigir después de la modificación
            header("Location: records.php?database=" . urlencode($database) . "&table=" . urlencode($table));
            exit();
        } else {
            die("Acceso no permitido.");
        }
        ?>
    </div>
</body>
</html>