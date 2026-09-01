<?php
session_start();

// Verificar que el usuario sea administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Procesar formulario si se envió (simulación)
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que ambos archivos hayan sido subidos (sin errores)
    if (isset($_FILES['archivo_zip']) && $_FILES['archivo_zip']['error'] === UPLOAD_ERR_OK &&
        isset($_FILES['archivo_excel']) && $_FILES['archivo_excel']['error'] === UPLOAD_ERR_OK) {
        $mensaje = '<div style="background: #e8f5e9; color: #2e7d32; padding: 15px 20px; border-radius: 8px; border-left: 4px solid #2e7d32; margin-top: 20px;">
                        ✅ Ambos archivos se han subido correctamente.
                    </div>';
    } else {
        $mensaje = '<div style="background: #ffebee; color: #b71c1c; padding: 15px 20px; border-radius: 8px; border-left: 4px solid #b71c1c; margin-top: 20px;">
                        ❌ Error al subir los archivos. Por favor, verifica que ambos sean válidos.
                    </div>';
    }
}

$page_title = "Subir Fichas - Consultorio Fiscal";
$page = "subirFichas";

include 'template/header.php';
?>

<link rel="stylesheet" href="css/suscripciones.css">

<!-- Hero -->
<section class="hero-static" style="padding: 60px 0 40px;">
    <div class="cs">
        <div class="hero-static__grid">
            <div class="hero-static__content">
                <span class="c-ph__tag">Administración</span>
                <h1 class="hero-static__title">Subir Fichas</h1>
                <div class="gold-line gold-l"></div>
                <p class="hero-static__excerpt">
                    Sube el archivo ZIP con las fichas en PDF y el archivo Excel con el registro de fichas.
                </p>
            </div>
            <div class="hero-static__visual">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5">
                    <path d="M12 3v12"></path>
                    <path d="M8 7l4-4 4 4"></path>
                    <path d="M5 13v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6"></path>
                </svg>
            </div>
        </div>
    </div>
</section>

<!-- Contenido -->
<section class="about" style="padding: 20px 0 60px;">
    <div class="cs-wide">
        <div class="admin-layout-grid">
            <div class="admin-content">
                <div class="detail-card" style="margin: 30px auto 0; max-width: 1150px; background: #f5f5f5; padding: 30px;">

                    <?php if ($mensaje): ?>
                        <?= $mensaje ?>
                    <?php endif; ?>

                    <form
                        action=""
                        method="POST"
                        enctype="multipart/form-data"
                        id="formFichas"
                    >
                        <!-- Campo 1: ZIP -->
                        <div style="margin-bottom: 25px;">
                            <label
                                for="archivo_zip"
                                style="display: block; margin-bottom: 8px; font-weight: 600; color: #1a2a3a;"
                            >
                                Archivo ZIP (fichas en PDF)
                            </label>
                            <input
                                type="file"
                                name="archivo_zip"
                                id="archivo_zip"
                                accept=".zip"
                                required
                                style="width: 100%; padding: 10px; border: 1px solid rgba(0,0,0,.15); border-radius: 6px; box-sizing: border-box;"
                            >
                            <div style="margin-top: 6px; font-size: 0.85rem; color: #5a6a7a;">
                                <span id="nombre_zip" style="font-weight: 500;">Ningún archivo seleccionado</span>
                            </div>
                        </div>

                        <!-- Campo 2: Excel -->
                        <div style="margin-bottom: 25px;">
                            <label
                                for="archivo_excel"
                                style="display: block; margin-bottom: 8px; font-weight: 600; color: #1a2a3a;"
                            >
                                Excel de fichas de pago
                            </label>
                            <input
                                type="file"
                                name="archivo_excel"
                                id="archivo_excel"
                                accept=".xlsx,.xls,.csv"
                                required
                                style="width: 100%; padding: 10px; border: 1px solid rgba(0,0,0,.15); border-radius: 6px; box-sizing: border-box;"
                            >
                            <div style="margin-top: 6px; font-size: 0.85rem; color: #5a6a7a;">
                                <span id="nombre_excel" style="font-weight: 500;">Ningún archivo seleccionado</span>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px;">
                            <!-- Cancelar -->
                            <a
                                href="solicitudes.php"
                                class="btn-filter-navy-small"
                                style="text-decoration: none;"
                            >
                                Cancelar
                            </a>

                            <!-- Confirmar (deshabilitado inicialmente) -->
                            <button
                                type="submit"
                                id="btnConfirmar"
                                class="btn-filter-navy-small"
                                disabled
                                style="opacity: 0.6; cursor: not-allowed;"
                            >
                                Confirmar
                            </button>
                        </div>
                    </form>

                    <!-- Mensaje de estado (opcional) -->
                    <div style="margin-top: 20px; font-size: 0.85rem; color: #5a6a7a; text-align: center;">
                        <span id="estado_archivos">Selecciona ambos archivos para habilitar el botón.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputZip = document.getElementById('archivo_zip');
        const inputExcel = document.getElementById('archivo_excel');
        const spanZip = document.getElementById('nombre_zip');
        const spanExcel = document.getElementById('nombre_excel');
        const btnConfirmar = document.getElementById('btnConfirmar');
        const estadoMsg = document.getElementById('estado_archivos');

        function actualizarEstado() {
            const zipSeleccionado = inputZip.files.length > 0;
            const excelSeleccionado = inputExcel.files.length > 0;

            // Actualizar nombres
            if (zipSeleccionado) {
                spanZip.textContent = inputZip.files[0].name;
                spanZip.style.color = '#2e7d32';
            } else {
                spanZip.textContent = 'Ningún archivo seleccionado';
                spanZip.style.color = '#5a6a7a';
            }

            if (excelSeleccionado) {
                spanExcel.textContent = inputExcel.files[0].name;
                spanExcel.style.color = '#2e7d32';
            } else {
                spanExcel.textContent = 'Ningún archivo seleccionado';
                spanExcel.style.color = '#5a6a7a';
            }

            // Habilitar/deshabilitar botón
            if (zipSeleccionado && excelSeleccionado) {
                btnConfirmar.disabled = false;
                btnConfirmar.style.opacity = '1';
                btnConfirmar.style.cursor = 'pointer';
                estadoMsg.textContent = '✅ Ambos archivos seleccionados. Puedes confirmar.';
                estadoMsg.style.color = '#2e7d32';
            } else {
                btnConfirmar.disabled = true;
                btnConfirmar.style.opacity = '0.6';
                btnConfirmar.style.cursor = 'not-allowed';
                estadoMsg.textContent = 'Selecciona ambos archivos para habilitar el botón.';
                estadoMsg.style.color = '#5a6a7a';
            }
        }

        inputZip.addEventListener('change', actualizarEstado);
        inputExcel.addEventListener('change', actualizarEstado);

        // Validar antes de enviar (por si acaso)
        document.getElementById('formFichas').addEventListener('submit', function(e) {
            if (inputZip.files.length === 0 || inputExcel.files.length === 0) {
                e.preventDefault();
                alert('Debes seleccionar ambos archivos antes de confirmar.');
            }
        });
    });
</script>

<?php include 'template/footer.php'; ?>