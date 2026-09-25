<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto</title>
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
        .login-container {
            background-color: white;
            padding: 25px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            width: 300px;
            padding-right: 45px; 
        }

        .login-container p {
            margin: 15px 0;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .login-container input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
            padding: 10px;
            cursor: pointer;
            width: 100%;
            margin-left: 10px;
        }
        .login-container input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>

<div class="login-container">
    <form method='POST' action=''>
      <p>Nombre del Servidor: <input type="text" id="servername" name="servername"></p>
      <p>Nombre de Usuario: <input type="text" id="username" name="username"></p>
      <p>Contraseña: <input type="password" id="password" name="password"></p>
      <input type="submit" value="Enviar">
    </form>
</div>


<?php
  session_start(); // Iniciar la sesión

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $servername = $_POST['servername'];
      $username = $_POST['username'];
      $password = $_POST['password'];
  
      // Almacenar las credenciales en variables de sesión
      $_SESSION['servername'] = $servername;
      $_SESSION['username'] = $username;
      $_SESSION['password'] = $password;
  
      // Envio al archivo showTables.php
      header("Location: showTables.php");
      exit(); 
  }

?>

</body>

</html>