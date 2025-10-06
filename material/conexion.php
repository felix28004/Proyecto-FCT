<?php
// Crear la conexión
$conn = new mysqli("localhost", "felix", "Marvel-28004", "material");


// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} else {
    echo " ";
}
?>