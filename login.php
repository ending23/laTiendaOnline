<?php
session_start();

// Mostrar errores para depuración (solo en entorno de desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
$servidor = "localhost";
$usuario = "root";
$contrasena = "123";
$base_datos = "onlineshop";

$conn = new mysqli($servidor, $usuario, $contrasena, $base_datos);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // Login
        $nombre_usuario = $_POST['nombre_usuario'];
        $contrasena = $_POST['contrasena'];

        $query = "SELECT id, rol, password FROM usuarios WHERE nombre_usuario = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $nombre_usuario);
        $stmt->execute();
        $stmt->bind_result($id, $rol, $hashed_password);

        if ($stmt->fetch()) {
            if (password_verify($contrasena, $hashed_password)) {
                $_SESSION['id'] = $id;
                $_SESSION['rol'] = $rol;

                if ($rol === 'Admin') {
                    header("Location: admin.html");
                } else {
                    header("Location: clientes.html");
                }
                exit();
            } else {
                echo "<p>Contraseña incorrecta.</p>";
            }
        } else {
            echo "<p>Usuario no encontrado.</p>";
        }
        $stmt->close();
    } elseif (isset($_POST['register'])) {
        // Registro
        $nombre_usuario = $_POST['nombre_usuario'];
        $correo = $_POST['correo'];
        $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
        $rol = 'Cliente'; // Por defecto, los nuevos usuarios son clientes

        $query = "INSERT INTO usuarios (nombre_usuario, password, rol) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $nombre_usuario, $contrasena, $rol);

        if ($stmt->execute()) {
            echo "<p>Usuario registrado exitosamente.</p>";
        } else {
            echo "<p>Error al registrar usuario: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
}

$conn->close();
?>
