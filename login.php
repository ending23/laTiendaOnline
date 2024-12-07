<?php
session_start();

// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tiendita";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        // Proceso de login
        $user = $conn->real_escape_string($_POST['username']);
        $pass = $_POST['password'];
        $sql = "SELECT * FROM users WHERE username = '$user'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($pass, $row['password'])) {
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];
                header("Location: index.php");
                exit();
            } else {
                echo "<p>Contraseña incorrecta.</p>";
            }
        } else {
            echo "<p>Usuario no encontrado.</p>";
        }
    } elseif (isset($_POST['register'])) {
        // Proceso de registro
        $user = $conn->real_escape_string($_POST['username']);
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = "user"; // Rol por defecto para usuarios registrados

        $sql = "INSERT INTO users (username, password, role) VALUES ('$user', '$pass', '$role')";
        if ($conn->query($sql) === TRUE) {
            echo "<p>Registro exitoso. Ahora puedes iniciar sesión.</p>";
        } else {
            echo "<p>Error al registrar usuario: " . $conn->error . "</p>";
        }
    }
}
$conn->close();
?>
