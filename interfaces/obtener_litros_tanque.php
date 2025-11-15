<?php
require '../negocio/USUARIO.php';
require '../negocio/TANQUE.php';
header('Content-Type: application/json');
$usu = new USUARIO();
session_start();
$tanque = new TANQUE();

if (isset($_POST['milimetros']) && isset($_POST['idhac'])) {
    $milimetros = (int)$_POST['milimetros'];
    $idhac = (int)$_POST['idhac'];

    // Instancia la clase TANQUE
    $tanque = new TANQUE();

    // Llama a la función obtenerLitrosTanque usando el idhac
    $litros = $tanque->obtenerLitrosTanque($idhac, $milimetros);

    if ($litros !== null) {
        echo json_encode(['litros' => $litros]);
    } else {
        echo json_encode(['error' => '0']);
    }
} else {
    echo json_encode(['error' => 'Faltan parámetros.']);
}