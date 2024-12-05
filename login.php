<?php
session_start();

// Configuración de la conexión a la base de datos
$servername = "localhost"; // Cambia si usas otro servidor
$username = "root";        // Usuario de la base de datos
$password = "";            // Contraseña del usuario
$dbname = "tiendita";      // Nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $user = $conn->real_escape_string($_POST['username']);
    $pass = $_POST['password'];

    // Consultar base de datos
    $sql = "SELECT * FROM users WHERE username = '$user'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verificar contraseña
        if (password_verify($pass, $row['password'])) {
            // Guardar usuario en la sesión
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role']; // Si tienes roles de usuario
            header("Location: index.php");    // Redirigir al inicio
            exit();
        } else {
            echo "<p>Contraseña incorrecta.</p>";
        }
    } else {
        echo "<p>Usuario no encontrado.</p>";
    }
}
$conn->close();
?>
