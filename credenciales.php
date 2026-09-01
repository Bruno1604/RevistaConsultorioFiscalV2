<?php
session_start();

// Verificar que el usuario sea administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require 'includes/proceso_store.php';

// Procesar Aprobar / Rechazar (POST) antes de imprimir nada
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion_credencial'])) {
    $correoObjetivo = $_POST['correo'] ?? '';
    $accion = $_POST['accion_credencial'];
    $paginaVuelta = isset($_POST['pagina']) ? (int) $_POST['pagina'] : 1;

    if ($correoObjetivo !== '') {
        if ($accion === 'aprobar') {
            proceso_actualizar($correoObjetivo, [
                'credencial_estado'         => 'aprobada',
                'credencial_motivo_rechazo' => '',
            ]);
            $_SESSION['flash_credenciales'] = ['tipo' => 'success', 'texto' => 'Credencial aprobada correctamente.'];
        } elseif ($accion === 'rechazar') {
            $motivo = trim($_POST['motivo'] ?? '');
            proceso_actualizar($correoObjetivo, [
                'credencial_estado'         => 'rechazada',
                'credencial_motivo_rechazo' => $motivo !== '' ? $motivo : 'La credencial no cumple con los requisitos de validación.',
            ]);
            $_SESSION['flash_credenciales'] = ['tipo' => 'danger', 'texto' => 'Credencial rechazada correctamente.'];
        }
    }

    header("Location: credenciales.php?pagina=" . $paginaVuelta);
    exit();
}

$page_title = "Gestión de Credenciales - Consultorio Fiscal";
$page = "credenciales";

include 'template/header.php';

$flash = $_SESSION['flash_credenciales'] ?? null;
unset($_SESSION['flash_credenciales']);

/*
 * Solicitudes reales: alumnos con tarifa UNAM que ya enviaron su credencial,
 * leídas del store compartido (data/proceso_estado.json).
 */
$solicitudesRaw = proceso_listar_credenciales();
$solicitudes = [];
foreach ($solicitudesRaw as $correo => $sol) {
    $solicitudes[] = [
        'correo'          => $correo,
        'nombre'          => $sol['usuario_nombre'],
        'afiliacion'      => 'Comunidad UNAM',
        'fechaCredencial' => $sol['credencial_fecha_envio'] ?? '-',
        'archivo'         => $sol['credencial_nombre_archivo'],
        'ruta'            => $sol['credencial_ruta_archivo'],
        'estado'          => $sol['credencial_estado'],
        'motivo'          => $sol['credencial_motivo_rechazo'],
    ];
}

$porPagina = 5;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$totalRegistros = count($solicitudes);
$totalPaginas = max(1, (int) ceil($totalRegistros / $porPagina));

if ($paginaActual < 1) {
    $paginaActual = 1;
}
if ($paginaActual > $totalPaginas) {
    $paginaActual = $totalPaginas;
}

$inicio = ($paginaActual - 1) * $porPagina;
$solicitudesPagina = array_slice($solicitudes, $inicio, $porPagina);

$estadoBadges = [
    'en_revision' => ['bg' => 'rgba(13,110,253,.10)', 'color' => '#075985', 'texto' => 'En revisión'],
    'aprobada'    => ['bg' => 'rgba(40,167,69,.15)',  'color' => '#155724', 'texto' => 'Aprobada'],
    'rechazada'   => ['bg' => 'rgba(220,53,69,.12)',  'color' => '#721c24', 'texto' => 'Rechazada'],
];
?>

<!-- Hero -->
<section class="hero-static" style="padding: 60px 0 40px;">
    <div class="cs">

        <div class="hero-static__grid">

            <div class="hero-static__content">

                <span class="c-ph__tag">
                    Administración
                </span>

                <h1 class="hero-static__title">
                    Gestión de Credenciales
                </h1>

                <div class="gold-line gold-l"></div>

                <p class="hero-static__excerpt">
                    Revisa las credenciales enviadas por los usuarios.
                </p>

            </div>

            <div class="hero-static__visual">

                <svg width="80" height="80" viewBox="0 0 24 24"
                     fill="none"
                     stroke="var(--gold)"
                     stroke-width="1.5">

                    <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                    <circle cx="8" cy="10" r="2"></circle>
                    <path d="M13 9h5"></path>
                    <path d="M13 13h5"></path>
                    <path d="M6 16h12"></path>

                </svg>

            </div>

        </div>

    </div>
</section>

<!-- Estilos -->
<link rel="stylesheet" href="css/suscripciones.css">

<!-- Contenido -->
<section class="about" style="padding: 20px 0 60px;">

    <div class="cs-wide">

        <div class="admin-layout-grid">

            <?php include 'includes/sidebar.php'; ?>

            <div class="admin-content">

                <?php if ($flash): ?>
                    <div style="margin-bottom: 18px; padding: 12px 16px; border-radius: 6px; font-size: 0.85rem; font-weight: 500;
                        <?php echo $flash['tipo'] === 'success'
                            ? 'background: rgba(40,167,69,.12); color: #155724; border-left: 4px solid #28a745;'
                            : 'background: rgba(220,53,69,.10); color: #721c24; border-left: 4px solid #b02a37;'; ?>">
                        <?php echo htmlspecialchars($flash['texto']); ?>
                    </div>
                <?php endif; ?>

                <div class="detail-card">

                    <div class="admin-table-container"
                        style="overflow-x: auto;">

                        <!-- Tabla -->
                        <table class="admin-table">

                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                    <th>Docs</th>
                                    <th>Estado</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php if (empty($solicitudesPagina)): ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; padding: 30px; color: var(--text-soft);">
                                            Aún no hay credenciales UNAM enviadas para revisar.
                                        </td>
                                    </tr>
                                <?php endif; ?>

                                <?php foreach ($solicitudesPagina as $sol):
                                    $idSeguro = preg_replace('/[^a-zA-Z0-9]/', '_', $sol['correo']);
                                    $b = $estadoBadges[$sol['estado']] ?? null;
                                ?>

                                    <tr>

                                        <!-- Fecha envío credencial -->
                                        <td>
                                            <?php echo htmlspecialchars($sol['fechaCredencial']); ?>
                                        </td>

                                        <!-- Usuario -->
                                        <td>

                                            <div style="
                                                font-weight: 500;
                                                color: var(--text);
                                            ">
                                                <?php echo htmlspecialchars($sol['nombre']); ?>
                                            </div>

                                            <div style="
                                                font-size: 0.7rem;
                                                font-style: italic;
                                                color: var(--accent-gold);
                                            ">
                                                <?php echo htmlspecialchars($sol['afiliacion']); ?>
                                            </div>

                                        </td>

                                        <!-- Credencial -->
                                        <td>
                                            <?php if ($sol['ruta']): ?>

                                                <span style="
                                                    font-size: 0.7rem;
                                                    color: var(--text);
                                                ">
                                                    Credencial:
                                                    <a
                                                        href="<?php echo htmlspecialchars($sol['ruta']); ?>"
                                                        target="_blank"
                                                        style="
                                                            color: #37517e;
                                                            text-decoration: underline;
                                                            margin-left: 2px;
                                                        "
                                                    >
                                                        Ver
                                                    </a>
                                                </span>

                                            <?php else: ?>

                                                <span class="text-soft">
                                                    No disponible
                                                </span>

                                            <?php endif; ?>
                                        </td>

                                        <!-- Estado -->
                                        <td>
                                            <?php if ($b): ?>
                                                <span style="display:inline-block; padding:4px 10px; border-radius:12px; font-size:0.7rem; font-weight:600; background: <?php echo $b['bg']; ?>; color: <?php echo $b['color']; ?>;">
                                                    <?php echo $b['texto']; ?>
                                                </span>
                                                <?php if ($sol['estado'] === 'rechazada' && $sol['motivo']): ?>
                                                    <div style="font-size: 0.65rem; color: #721c24; margin-top: 4px; max-width: 180px;">
                                                        <?php echo htmlspecialchars($sol['motivo']); ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Acciones -->
                                        <td>

                                            <div
                                                id="acciones-<?php echo $idSeguro; ?>"
                                                style="
                                                    display: flex;
                                                    gap: 6px;
                                                    align-items: center;
                                                    flex-wrap: wrap;
                                                "
                                            >

                                                <!-- Aprobar -->
                                                <form method="post" style="display: inline;">
                                                    <input type="hidden" name="correo" value="<?php echo htmlspecialchars($sol['correo']); ?>">
                                                    <input type="hidden" name="accion_credencial" value="aprobar">
                                                    <input type="hidden" name="pagina" value="<?php echo $paginaActual; ?>">
                                                    <button
                                                        type="submit"
                                                        class="btn-filter-navy-small"
                                                        style="
                                                            display: inline-flex;
                                                            align-items: center;
                                                            justify-content: center;
                                                            gap: 6px;
                                                            width: 100px;
                                                            height: 36px;
                                                            box-sizing: border-box;
                                                        "
                                                    >
                                                        Aprobar
                                                    </button>
                                                </form>

                                                <!-- Rechazar -->
                                                <button
                                                    type="button"
                                                    class="btn-filter-navy-small"
                                                    style="
                                                        background: #b02a37;
                                                        display: inline-flex;
                                                        align-items: center;
                                                        justify-content: center;
                                                        gap: 6px;
                                                        width: 100px;
                                                        height: 36px;
                                                        box-sizing: border-box;
                                                    "
                                                    onclick="
                                                        document.getElementById(
                                                            'rechazo-container-<?php echo $idSeguro; ?>'
                                                        ).style.display='table-row';

                                                        document.getElementById(
                                                            'acciones-<?php echo $idSeguro; ?>'
                                                        ).style.display='none';
                                                    "
                                                >
                                                    Rechazar
                                                </button>

                                            </div>

                                        </td>

                                        </tr>

                                        <!-- Fila para rechazo -->
                                        <tr
                                            id="rechazo-container-<?php echo $idSeguro; ?>"
                                            style="display: none;"
                                        >

                                            <td colspan="5">

                                                <form method="post" style="
                                                    padding: 18px;
                                                    background: #f8f8f8;
                                                    border: 1px solid #ddd;
                                                    border-radius: 6px;
                                                    display: block;
                                                ">
                                                    <input type="hidden" name="correo" value="<?php echo htmlspecialchars($sol['correo']); ?>">
                                                    <input type="hidden" name="accion_credencial" value="rechazar">
                                                    <input type="hidden" name="pagina" value="<?php echo $paginaActual; ?>">

                                                    <div style="
                                                        font-weight: 600;
                                                        margin-bottom: 8px;
                                                        color: var(--accent-navy);
                                                    ">
                                                        Comentarios del rechazo
                                                    </div>

                                                    <textarea
                                                        name="motivo"
                                                        rows="2"
                                                        placeholder="Escribe el motivo del rechazo..."
                                                        style="
                                                            width: 100%;
                                                            height: 80px;
                                                            resize: vertical;
                                                            padding: 8px 10px;
                                                            border: 1px solid #ccc;
                                                            border-radius: 5px;
                                                            font-family: inherit;
                                                            font-size: 0.75rem;
                                                            box-sizing: border-box;
                                                            margin-bottom: 12px;
                                                        "
                                                    ></textarea>

                                                    <div style="
                                                        display: flex;
                                                        justify-content: flex-end;
                                                        gap: 8px;
                                                    ">

                                                        <!-- Cancelar -->
                                                        <button
                                                            type="button"
                                                            class="btn-filter-navy-small"
                                                            onclick="
                                                                document.getElementById(
                                                                    'rechazo-container-<?php echo $idSeguro; ?>'
                                                                ).style.display='none';

                                                                document.getElementById(
                                                                    'acciones-<?php echo $idSeguro; ?>'
                                                                ).style.display='flex';
                                                            "
                                                        >
                                                            Cancelar
                                                        </button>

                                                        <!-- Confirmar rechazo -->
                                                        <button
                                                            type="submit"
                                                            class="btn-filter-navy-small"
                                                            style="
                                                                background: #b02a37;
                                                            "
                                                        >
                                                            Confirmar
                                                        </button>

                                                    </div>

                                                </form>

                                            </td>

                                        </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                        <?php if ($totalPaginas > 1): ?>

                        <div class="paginador">

                            <?php if ($paginaActual > 1): ?>
                                <a
                                    href="?pagina=<?php echo $paginaActual - 1; ?>"
                                    class="pagina-btn"
                                >
                                    &laquo; Anterior
                                </a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>

                                <a
                                    href="?pagina=<?php echo $i; ?>"
                                    class="pagina-btn <?php echo ($i == $paginaActual) ? 'pagina-activa' : ''; ?>"
                                >
                                    <?php echo $i; ?>
                                </a>

                            <?php endfor; ?>

                            <?php if ($paginaActual < $totalPaginas): ?>
                                <a
                                    href="?pagina=<?php echo $paginaActual + 1; ?>"
                                    class="pagina-btn"
                                >
                                    Siguiente &raquo;
                                </a>
                            <?php endif; ?>

                        </div>

                    <?php endif; ?>

                    </div>

                </div>

            </div>
        
        </div>
    </div>

</section>

<?php include 'template/footer.php'; ?>
