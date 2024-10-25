<?php

// Permitir solicitudes desde cualquier origen (para CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Manejar solicitudes preflight de CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Importar el archivo de conexión a la base de datos
require_once '../Config/database.php';

// Obtener el método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'POST') {
    // Obtener la entrada JSON
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Verificar la acción a realizar
    if ($input['accion'] === 'insertar_usuario') {
        // Datos del usuario
        $nombre = $input['data']['usuario_nombre'];
        $correo = $input['data']['usuario_correo'];
        $password = $input['data']['password'];
        // Procedimiento almacenado para insertar usuario
        $stmt = $pdo->prepare("CALL sp_insertar_usuario(?, ?, ?)");
        $stmt->execute([$nombre, $correo, $password]);

        echo json_encode(['mensaje' => 'Usuario insertado con éxito']);

    } elseif ($input['accion'] === 'insertar_reserva') {
        // Datos de la reserva
        $usuario_id = $input['data']['usuario_id'];
        $fecha = $input['data']['reserva_fecha'];
        $hora = $input['data']['reserva_hora'];
        $num_personas = $input['data']['reserva_num_personas'];
        $estado = $input['data']['reserva_estado'];

        // Procedimiento almacenado para insertar reserva
        $stmt = $pdo->prepare("CALL sp_insertar_reserva(?, ?, ?, ?, ?)");
        $stmt->execute([$usuario_id, $fecha, $hora, $num_personas, $estado]);

        echo json_encode(['mensaje' => 'Reserva insertada con éxito']);

    } elseif ($input['accion'] === 'insertar_comentario') {
        // Datos del comentario
        $reserva_id = $input['data']['reserva_id'];
        $usuario_id = $input['data']['usuario_id'];
        $comentario_text = $input['data']['comentario_text'];
        $fecha = $input['data']['comentario_fecha'];

        // Procedimiento almacenado para insertar comentario
        $stmt = $pdo->prepare("CALL sp_insertar_comentario(?, ?, ?, ?)");
        $stmt->execute([$reserva_id, $usuario_id, $comentario_text, $fecha]);

        echo json_encode(['mensaje' => 'Comentario insertado con éxito']);

    } elseif ($input['accion'] === 'insertar_menu') {
        // Datos del menú
        $plato_nombre = $input['data']['menu_plato_nombre'];
        $descripcion = $input['data']['menu_plato_descripcion'];
        $precio = $input['data']['menu_precio'];
        $disponible = $input['data']['menu_disponible'];

        // Procedimiento almacenado para insertar menú
        $stmt = $pdo->prepare("CALL sp_insertar_menu(?, ?, ?, ?)");
        $stmt->execute([$plato_nombre, $descripcion, $precio, $disponible]);

        echo json_encode(['mensaje' => 'Plato insertado en el menú con éxito']);
    }

} elseif ($method == 'GET') {
    // Verificar que se haya pasado la acción en la URL
    if (isset($_GET['accion'])) {
        // Visualizar usuarios
        if ($_GET['accion'] === 'visualizar_usuarios') {
            // Procedimiento almacenado para visualizar todos los usuarios
            $stmt = $pdo->query("CALL sp_visualizar_usuarios()");
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($usuarios);

        // Buscar usuario por correo
        } elseif ($_GET['accion'] === 'buscar_usuario_por_correo' && isset($_GET['usuario_correo'])) {
            $usuario_correo = $_GET['usuario_correo'];
            // Procedimiento almacenado para buscar usuario por correo
            $stmt = $pdo->prepare("CALL sp_visualizar_usuario_por_correo(?)");
            $stmt->execute([$usuario_correo]);
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($usuarios) {
                echo json_encode($usuarios);
            } else {
                echo json_encode(['error' => 'No se encontraron usuarios con ese correo']);
            }

        // Visualizar reservas
        } elseif ($_GET['accion'] === 'visualizar_reservas') {
            // Procedimiento almacenado para visualizar todas las reservas
            $stmt = $pdo->query("CALL sp_visualizar_reservas()");
            $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($reservas);

        // Visualizar comentarios
        } elseif ($_GET['accion'] === 'visualizar_comentarios') {
            // Procedimiento almacenado para visualizar todos los comentarios
            $stmt = $pdo->query("CALL sp_visualizar_comentarios()");
            $comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($comentarios);

        // Visualizar menú
        } elseif ($_GET['accion'] === 'visualizar_menu') {
            // Procedimiento almacenado para visualizar todos los platos del menú
            $stmt = $pdo->query("CALL sp_visualizar_menu()");
            $menu = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($menu);
        } else {
            // Si la acción no coincide con ninguna de las esperadas
            echo json_encode(['error' => 'Acción no válida']);
        }
    } else {
        // Si no se proporciona ninguna acción
        echo json_encode(['error' => 'No se proporcionó ninguna acción']);
    }

} else {
    // Si el método no es GET o POST
    echo json_encode(['error' => 'Método no permitido']);
}

?>
