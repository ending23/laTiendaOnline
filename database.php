<?php
$servername = "localhost";
$username = "tu_usuario"; // Cambia esto por tu usuario de la base de datos
$password = "tu_contrasena"; // Cambia esto por tu contraseña de la base de datos
$dbname = "Onlineshop"; // Cambia esto por el nombre de tu base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
