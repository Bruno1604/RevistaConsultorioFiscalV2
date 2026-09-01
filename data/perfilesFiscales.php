<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['action']) && $_GET['action'] === 'reset') {
    unset($_SESSION['perfiles_fiscales']);
    header("Location: ../perfilesFiscales.php");
    exit();
}

// Inicializar el arreglo en sesión si no existe (simula la tabla PERFIL FISCAL)
if (!isset($_SESSION['perfiles_fiscales'])) {
    $_SESSION['perfiles_fiscales'] = [
        '1' => [
            'id'              => '1',
            'tipo_persona'    => 'Física',
            'rfc'             => 'PEJJ850312AB1',
            'razon_social'    => 'Juan Pérez Jiménez',
            'regimen_fiscal'  => 'Sueldos y Salarios',
            'codigo_postal'   => '04510',
            'uso_cfdi'        => 'G03 - Gastos en general',
            'correo'          => 'juan.perez@unam.mx',
            'fecha_registro'  => '2026-08-10',
        ],
        '2' => [
            'id'              => '2',
            'tipo_persona'    => 'Moral',
            'rfc'             => 'FCA050312XY2',
            'razon_social'    => 'Consultoría Fiscal del Valle S.A. de C.V.',
            'regimen_fiscal'  => 'Régimen General de Ley Personas Morales',
            'codigo_postal'   => '04510',
            'uso_cfdi'        => 'G01 - Adquisición de mercancías',
            'correo'          => 'facturacion@consultoriadelvalle.com',
            'fecha_registro'  => '2026-08-12',
        ],
    ];
}

// Autoincremental simple para nuevos IDs
if (!isset($_SESSION['perfiles_fiscales_next_id'])) {
    $_SESSION['perfiles_fiscales_next_id'] = 3;
}

function get_perfiles_fiscales() {
    return $_SESSION['perfiles_fiscales'];
}

function get_perfil_fiscal($id) {
    return $_SESSION['perfiles_fiscales'][$id] ?? null;
}

function add_perfil_fiscal($data) {
    $id = (string) $_SESSION['perfiles_fiscales_next_id'];

    $data['id'] = $id;
    $data['fecha_registro'] = date('Y-m-d');

    $_SESSION['perfiles_fiscales'][$id] = $data;
    $_SESSION['perfiles_fiscales_next_id']++;

    return $id;
}

function update_perfil_fiscal($id, $data) {
    if (isset($_SESSION['perfiles_fiscales'][$id])) {
        $_SESSION['perfiles_fiscales'][$id] = array_merge($_SESSION['perfiles_fiscales'][$id], $data);
        return true;
    }
    return false;
}

function delete_perfil_fiscal($id) {
    if (isset($_SESSION['perfiles_fiscales'][$id])) {
        unset($_SESSION['perfiles_fiscales'][$id]);
        return true;
    }
    return false;
}