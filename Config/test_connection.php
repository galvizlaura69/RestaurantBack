<?php

require_once 'database.php';

try {
    // Verificar la conexión
    $pdo->query("SELECT 1");
    echo "Conexión a la base de datos realizada con éxito.";
} catch (Exception $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>
