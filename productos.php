<?php
include 'database.php';

// Eliminar producto
if (isset($_GET['eliminar_id'])) {
    $id = (int) $_GET['eliminar_id']; // Asegurarse de que es un número entero
    $sql = "DELETE FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<p>Producto eliminado correctamente.</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }
}

// Agregar producto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $nombre = htmlspecialchars($_POST['nombre']);
    $descripcion = htmlspecialchars($_POST['descripcion']);
    $precio = (float) $_POST['precio']; // Asegurarse de que es un número válido
    $stock = (int) $_POST['stock']; // Asegurarse de que es un número entero

    $sql = "INSERT INTO productos (nombre, descripcion, precio, stock) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdi", $nombre, $descripcion, $precio, $stock);
    if ($stmt->execute()) {
        echo "<p>Producto agregado correctamente.</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }
}

// Editar producto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = (int) $_POST['id']; // Asegurarse de que es un número entero
    $nombre = htmlspecialchars($_POST['nombre']);
    $descripcion = htmlspecialchars($_POST['descripcion']);
    $precio = (float) $_POST['precio'];
    $stock = (int) $_POST['stock'];

    $sql = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, stock = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdii", $nombre, $descripcion, $precio, $stock, $id);
    if ($stmt->execute()) {
        echo "<p>Producto actualizado correctamente.</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
    <style>
        /* General styles */
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        /* Form styles */
        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        form input, form textarea, form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        form button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #0056b3;
        }

        /* Table styles */
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        table th, table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #007BFF;
            color: white;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Button styles in table */
        table a {
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
        }

        table a:hover {
            text-decoration: underline;
        }

        table button {
            margin-top: 5px;
            width: auto;
            padding: 5px 10px;
            font-size: 14px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        table button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <h1>Productos</h1>

    <!-- Formulario para agregar producto -->
    <h2>Agregar Producto</h2>
    <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre" required><br>
        <textarea name="descripcion" placeholder="Descripción" required></textarea><br>
        <input type="number" name="precio" placeholder="Precio" step="0.01" required><br>
        <input type="number" name="stock" placeholder="Stock" required><br>
        <button type="submit" name="add">Agregar Producto</button>
    </form>

    <!-- Listado de productos -->
    <h2>Listado de Productos</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Acciones</th>
        </tr>
        <?php
        $sql = "SELECT * FROM productos";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nombre']; ?></td>
                <td><?php echo $row['descripcion']; ?></td>
                <td><?php echo $row['precio']; ?></td>
                <td><?php echo $row['stock']; ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="text" name="nombre" value="<?php echo $row['nombre']; ?>" required>
                        <input type="text" name="descripcion" value="<?php echo $row['descripcion']; ?>" required>
                        <input type="number" name="precio" value="<?php echo $row['precio']; ?>" step="0.01" required>
                        <input type="number" name="stock" value="<?php echo $row['stock']; ?>" required>
                        <button type="submit" name="update">Actualizar</button>
                    </form>
                    <a href="?eliminar_id=<?php echo $row['id']; ?>" onclick="return confirm('¿Seguro que quieres eliminar este producto?')">Eliminar</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

</body>
</html>

<?php $conn->close(); ?>
