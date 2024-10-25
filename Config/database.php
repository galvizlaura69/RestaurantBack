<?php
// Configuración de la base de datos
$host = 'localhost';
$db   = 'restaurante'; // Nombre de la base de datos
$user = 'root';        // Cambia esto por tu usuario de MySQL
$pass = '';  // Cambia esto por tu contraseña
$charset = 'utf8mb4';

// DSN para conexión
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //Lanzará excepciones en caso de error
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //Modo de obtencion de datos, devuelve resultados como un array asociativo
    PDO::ATTR_EMULATE_PREPARES   => false, //forma de ejecutar consultas SQL de manera más segura y eficiente
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Error en la conexión: " . $e->getMessage()); //Si hay un error en la conexión
}
?>
