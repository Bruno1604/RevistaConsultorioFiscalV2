<?php
session_start();

// Solo un usuario logueado con rol "usuario" puede avanzar su proceso de suscripción
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'usuario') {
    header("Location: login.php");
    exit();
}

require 'includes/proceso_store.php';

$correo = $_SESSION['correo'];
$nombre = $_SESSION['nombre'] ?? $correo;

// Estado real, persistido en data/proceso_estado.json (se crea solo la primera vez)
$p = proceso_obtener($correo, $nombre);
$p['paso_actual'] = proceso_calcular_paso_actual($p);

// Si el comprobante ya fue aprobado y el flujo llegó a "Suscripción completada",
// se reinicia el proceso guardado para que se pueda volver a realizar desde cero.
// (Esta página se sigue mostrando con los datos de $p, ya con la suscripción completada;
// el reinicio aplica a partir de la próxima vez que se cargue proceso.php.)
if ($p['paso_actual'] === 6) {
    proceso_reiniciar($correo, $nombre);
}

// Título y navegación del sistema
$page_title = "Proceso de Suscripción - Consultorio Fiscal";
$page = "suscripciones";
$use_process_header = true;
include 'template/header.php';
?>

<link rel="stylesheet" href="css/suscripciones.css">

<!-- Contenido Principal -->
<div class="cs sub-process-container">

    <!-- Layout del Proceso: Sidebar Stepper + Contenido Contextual por Pasos -->
    <div class="sub-process-layout">

        <!-- Sidebar Timeline Vertical Invertido -->
        <?php include 'includes/suscripcion/sidebar_stepper.php'; ?>

        <!-- Área de Contenido Principal -->
        <main class="stepper-content">
            <div class="stepper-content-card">

                <!-- Paso 1: Pre-registro (Captura de datos) -->
                <?php include 'includes/suscripcion/paso1_preregistro.php'; ?>

                <!-- Paso 2: Elegir tipo de suscripción (Selección de tarifa) -->
                <?php include 'includes/suscripcion/paso2_tarifa.php'; ?>

                <!-- Paso 3: Documentación / Credencial UNAM -->
                <?php include 'includes/suscripcion/paso3_credencial.php'; ?>

                <!-- Paso 4: Obtener referencia / Ficha de Pago -->
                <?php include 'includes/suscripcion/paso4_ficha.php'; ?>

                <!-- Paso 5: Pago / Comprobante de Pago -->
                <?php include 'includes/suscripcion/paso5_comprobante.php'; ?>

                <!-- Paso 6: Suscripción Completada -->
                <?php include 'includes/suscripcion/paso6_completado.php'; ?>

            </div>
        </main>
    </div>
</div>

<!-- Lógica JavaScript y Control del Flujo -->
<?php include 'includes/suscripcion/script_flujo.php'; ?>

<?php include 'template/footer.php'; ?>
