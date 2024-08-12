<?php
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$url = isset($_GET['url']) ? $_GET['url'] : null;
$method = $_SERVER['REQUEST_METHOD'];

// Depuración
// echo "URL: " . $url . "<br>";
// echo "Método: " . $method . "<br>";

switch ($method) {
    case 'GET':
        if ($url == 'libros') {
            // Obtener todos los libros
            $stmt = $db->query("SELECT * FROM libros");
            $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($libros);
        } elseif (preg_match('/libros\/(\d+)/', $url, $matches)) {
            // Obtener un libro por ID
            $id = $matches[1];
            $stmt = $db->prepare("SELECT * FROM libros WHERE id = ?");
            $stmt->execute([$id]);
            $libro = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($libro);
        } else {
            echo json_encode(["message" => "Recurso no encontrado"]);
        }
        break;

    case 'POST':
        if ($url == 'libros') {
            // Crear un nuevo libro
            $data = json_decode(file_get_contents("php://input"), true);
            $stmt = $db->prepare("INSERT INTO libros (titulo, autor, anio_publicacion, genero) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$data['titulo'], $data['autor'], $data['anio_publicacion'], $data['genero']])) {
                echo json_encode(["message" => "Libro creado exitosamente"]);
            } else {
                echo json_encode(["message" => "Error al crear libro"]);
            }
        }
        break;

    case 'PUT':
        if (preg_match('/libros\/(\d+)/', $url, $matches)) {
            // Actualizar un libro por ID
            $id = $matches[1];
            $data = json_decode(file_get_contents("php://input"), true);
            $stmt = $db->prepare("UPDATE libros SET titulo = ?, autor = ?, anio_publicacion = ?, genero = ? WHERE id = ?");
            if ($stmt->execute([$data['titulo'], $data['autor'], $data['anio_publicacion'], $data['genero'], $id])) {
                echo json_encode(["message" => "Libro actualizado exitosamente"]);
            } else {
                echo json_encode(["message" => "Error al actualizar libro"]);
            }
        }
        break;

    case 'DELETE':
        if (preg_match('/libros\/(\d+)/', $url, $matches)) {
            // Eliminar un libro por ID
            $id = $matches[1];
            $stmt = $db->prepare("DELETE FROM libros WHERE id = ?");
            if ($stmt->execute([$id])) {
                echo json_encode(["message" => "Libro eliminado exitosamente"]);
            } else {
                echo json_encode(["message" => "Error al eliminar libro"]);
            }
        }
        break;

    default:
        echo json_encode(["message" => "Método no soportado"]);
        break;
}
