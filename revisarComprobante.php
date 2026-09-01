<?php
session_start();

// Verificar que el usuario sea administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require 'includes/proceso_store.php';

// Procesar Aprobar / Rechazar (POST) antes de imprimir nada
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion_comprobante'])) {
    $correoObjetivo = $_POST['correo'] ?? '';
    $accion = $_POST['accion_comprobante'];
    $paginaVuelta = isset($_POST['pagina']) ? (int) $_POST['pagina'] : 1;

    if ($correoObjetivo !== '') {
        if ($accion === 'aprobar') {
            proceso_actualizar($correoObjetivo, [
                'comprobante_estado'         => 'aprobado',
                'comprobante_motivo_rechazo' => '',
            ]);
            $_SESSION['flash_comprobantes'] = ['tipo' => 'success', 'texto' => 'Comprobante aprobado correctamente.'];
        } elseif ($accion === 'rechazar') {
            $motivo = trim($_POST['motivo'] ?? '');
            proceso_actualizar($correoObjetivo, [
                'comprobante_estado'         => 'rechazado',
                'comprobante_motivo_rechazo' => $motivo !== '' ? $motivo : 'El comprobante no cumple con los requisitos de validación.',
            ]);
            $_SESSION['flash_comprobantes'] = ['tipo' => 'danger', 'texto' => 'Comprobante rechazado correctamente.'];
        }
    }

    header("Location: revisarComprobante.php?pagina=" . $paginaVuelta);
    exit();
}

$page_title = "Revisar Comprobantes - Consultorio Fiscal";
$page = "revisarComprobante";

include 'template/header.php';

$flash = $_SESSION['flash_comprobantes'] ?? null;
unset($_SESSION['flash_comprobantes']);

/*
 * Solicitudes reales: alumnos que ya enviaron su comprobante de pago,
 * leídas del store compartido (data/proceso_estado.json).
 */
$solicitudesRaw = proceso_listar_comprobantes();
$solicitudes = [];
foreach ($solicitudesRaw as $correo => $sol) {
    $esUnam = $sol['tarifa_seleccionada'] === 'UNAM';
    $solicitudes[] = [
        'correo'           => $correo,
        'nombre'           => $sol['usuario_nombre'],
        'afiliacion'       => $esUnam ? 'Comunidad UNAM' : 'Público general',
        'fechaInicio'      => $sol['fecha_preregistro'],
        'fechaComprobante' => $sol['comprobante_fecha_envio'] ? substr($sol['comprobante_fecha_envio'], 0, 10) : '-',
        'tarifa'           => 600.00,
        'descuento'        => $esUnam ? 300.00 : 0.00,
        'monto'            => $esUnam ? 300.00 : 600.00,
        'ficha'            => (bool) $sol['ficha_generada'],
        'credencial_pdf'   => $sol['credencial_ruta_archivo'],
        'comprobante_pdf'  => $sol['comprobante_ruta_archivo'],
        'monto_pago'       => $sol['comprobante_importe'],
        'referencia'       => $sol['comprobante_num_operacion'],
        'clave_rastreo'    => $sol['comprobante_clave_rastreo'],
        'estado'           => $sol['comprobante_estado'],
        'motivo'           => $sol['comprobante_motivo_rechazo'],
    ];
}

// ===============================
// PAGINACIÓN
// ===============================
$registrosPorPagina = 5;
$pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$pagina = max(1, $pagina);
$totalRegistros = count($solicitudes);
$totalPaginas = max(1, (int) ceil($totalRegistros / $registrosPorPagina));
if ($pagina > $totalPaginas) $pagina = $totalPaginas;
$inicio = ($pagina - 1) * $registrosPorPagina;
$solicitudesPagina = array_slice($solicitudes, $inicio, $registrosPorPagina);

$estadoBadges = [
    'en_revision' => ['bg' => 'rgba(13,110,253,.10)', 'color' => '#075985', 'texto' => 'En revisión'],
    'aprobado'    => ['bg' => 'rgba(40,167,69,.15)',  'color' => '#155724', 'texto' => 'Aprobado'],
    'rechazado'   => ['bg' => 'rgba(220,53,69,.12)',  'color' => '#721c24', 'texto' => 'Rechazado'],
];

?>

<!-- Hero -->
<section class="hero-static" style="padding: 60px 0 40px;">
    <div class="cs">
        <div class="hero-static__grid">
            <div class="hero-static__content">
                <span class="c-ph__tag">Administración</span>
                <h1 class="hero-static__title">Revisar Comprobantes</h1>
                <div class="gold-line gold-l"></div>
                <p class="hero-static__excerpt">
                    Revisa los comprobantes enviados por los usuarios. Haz clic en una fila para ver los detalles.
                </p>
            </div>
            <div class="hero-static__visual">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5">
                    <path d="M6 2h9l5 5v15H6z"></path>
                    <path d="M15 2v6h5"></path>
                    <circle cx="10.5" cy="13.5" r="3"></circle>
                    <path d="M13 16l3 3"></path>
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
            <!-- Sidebar -->
            <?php include 'includes/sidebar.php'; ?>
            <!-- Contenido -->
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
                    <div class="admin-table-container" style="overflow-x: auto;">

                        <table class="admin-table" id="tablaSolicitudes">
                            <thead>
                                <tr>
                                    <th style="width: 30px;"></th>
                                    <th style="width: 100px;">Fecha inicio</th>
                                    <th style="width: 100px;">Fecha comprobante de Pago</th>
                                    <th>Usuario</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($solicitudesPagina)): ?>
                                    <tr>
                                        <td colspan="4" style="text-align: center; padding: 30px; color: var(--text-soft);">
                                            Aún no hay comprobantes de pago enviados para revisar.
                                        </td>
                                    </tr>
                                <?php endif; ?>

                                <?php foreach ($solicitudesPagina as $sol):
                                    $idSeguro = preg_replace('/[^a-zA-Z0-9]/', '_', $sol['correo']);
                                    $rowId = 'fila-' . $idSeguro;
                                    $detailId = 'detalle-' . $idSeguro;
                                    $b = $estadoBadges[$sol['estado']] ?? null;
                                ?>
                                    <!-- Fila principal (clickeable) -->
                                    <tr id="<?= $rowId ?>" class="fila-principal" style="cursor: pointer;">
                                        <td>
                                            <span class="toggle-icon" data-target="<?= $detailId ?>">&#9654;</span>
                                        </td>
                                        <td><?= htmlspecialchars($sol['fechaInicio']) ?></td>
                                        <td><?= htmlspecialchars($sol['fechaComprobante']) ?></td>
                                        <td>
                                            <div style="font-weight: 500; color: var(--text);">
                                                <?= htmlspecialchars($sol['nombre']) ?>
                                            </div>
                                            <div style="font-size: 0.7rem; font-style: italic; color: var(--accent-gold);">
                                                <?= htmlspecialchars($sol['afiliacion']) ?>
                                            </div>
                                            <?php if ($b): ?>
                                                <span style="display:inline-block; margin-top:4px; padding:3px 9px; border-radius:12px; font-size:0.65rem; font-weight:600; background: <?= $b['bg'] ?>; color: <?= $b['color'] ?>;">
                                                    <?= $b['texto'] ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>

                                    <!-- Fila de detalle (oculta) -->
                                    <tr id="<?= $detailId ?>" class="fila-detalle" style="display: none;">
                                        <td colspan="4">
                                            <div style="padding: 20px; background: #faf8f5; border: 1px solid #e0d6c8; border-radius: 8px; margin: 5px 0;">

                                                <!-- SECCIÓN COSTOS -->
                                                <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px;">
                                                    <div>
                                                        <span style="font-weight: 600; font-size: 0.8rem; color: #5a6a7a;">Tarifa:</span>
                                                        <span style="font-weight: 500;">$<?= number_format($sol['tarifa'], 2) ?></span>
                                                    </div>
                                                    <?php if ($sol['descuento'] > 0): ?>
                                                        <div>
                                                            <span style="font-weight: 600; font-size: 0.8rem; color: #5a6a7a;">Descuento:</span>
                                                            <span style="font-weight: 500; color: #b02a37;">-$<?= number_format($sol['descuento'], 2) ?></span>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <span style="font-weight: 600; font-size: 0.8rem; color: #5a6a7a;">Monto:</span>
                                                        <span style="font-weight: 700; color: #2e7d32;">$<?= number_format($sol['monto'], 2) ?></span>
                                                    </div>
                                                </div>

                                                <!-- SECCIÓN DOCUMENTOS -->
                                                <div style="margin-bottom: 20px;">
                                                    <h4 style="font-size: 0.9rem; margin: 0 0 8px 0; color: #1a2a3a;">Documentos</h4>
                                                    <div style="display: flex; flex-wrap: wrap; gap: 15px; font-size: 0.85rem;">
                                                        <?php if ($sol['credencial_pdf']): ?>
                                                            <span>
                                                                Credencial:
                                                                <a href="<?= htmlspecialchars($sol['credencial_pdf']) ?>" target="_blank" style="color: #0d6efd; text-decoration: underline;">Ver</a>
                                                            </span>
                                                        <?php else: ?>
                                                            <span style="color: #8a9aa8;">Credencial: N/A</span>
                                                        <?php endif; ?>

                                                        <?php if ($sol['ficha']): ?>
                                                            <span>
                                                                Ficha de Pago:
                                                                <a href="docs/ficha_pago_demo.pdf" target="_blank" style="color: #0d6efd; text-decoration: underline;">Ver</a>
                                                            </span>
                                                        <?php else: ?>
                                                            <span style="color: #8a9aa8;">Ficha de Pago: N/A</span>
                                                        <?php endif; ?>
                                                        <?php if ($sol['comprobante_pdf']): ?>
                                                            <span>
                                                                Comprobante de Pago:
                                                                <a href="<?= htmlspecialchars($sol['comprobante_pdf']) ?>" target="_blank" style="color: #b02a37; text-decoration: underline; font-weight: 500;">Ver</a>
                                                            </span>
                                                        <?php else: ?>
                                                            <span style="color: #8a9aa8;">Comprobante de Pago: No cargado</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <!-- SECCIÓN DATOS DEL COMPROBANTE (ingresados por el usuario) -->
                                                <div style="margin-bottom: 20px;">
                                                    <h4 style="font-size: 0.9rem; margin: 0 0 8px 0; color: #1a2a3a;">Datos del comprobante de pago ingresados por el usuario</h4>
                                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px 20px; font-size: 0.85rem; background: #fff; padding: 12px; border-radius: 6px; border: 1px solid #e8e0d8;">
                                                        <div><strong>Monto:</strong> $<?= number_format((float) ($sol['monto_pago'] ?? 0), 2) ?></div>
                                                        <div><strong>Número de operación:</strong> <?= htmlspecialchars($sol['referencia'] ?? 'N/A') ?></div>
                                                        <div style="grid-column: span 2;"><strong>Clave rastreo:</strong> <?= htmlspecialchars($sol['clave_rastreo'] ?? 'N/A') ?></div>
                                                    </div>
                                                </div>

                                                <?php if ($sol['estado'] === 'rechazado' && $sol['motivo']): ?>
                                                    <div style="background: rgba(220,53,69,.08); border-left: 4px solid #b02a37; padding: 10px 14px; font-size: 0.82rem; color: #721c24; margin-bottom: 15px;">
                                                        <strong>Motivo del rechazo:</strong> <?= htmlspecialchars($sol['motivo']) ?>
                                                    </div>
                                                <?php endif; ?>

                                                <!-- ACCIONES -->
                                                <div id="acciones-<?= $idSeguro ?>" style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px; border-top: 1px solid #e0d6c8; padding-top: 15px;">
                                                    <form method="post" style="display: inline;">
                                                        <input type="hidden" name="correo" value="<?= htmlspecialchars($sol['correo']) ?>">
                                                        <input type="hidden" name="accion_comprobante" value="aprobar">
                                                        <input type="hidden" name="pagina" value="<?= $pagina ?>">
                                                        <button type="submit" class="btn-filter-navy-small" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 20px;">
                                                            Aprobar
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn-filter-navy-small" style="background: #b02a37; display: inline-flex; align-items: center; gap: 6px; padding: 8px 20px;"
                                                            onclick="
                                                                document.getElementById('rechazo-<?= $idSeguro ?>').style.display='block';
                                                                document.getElementById('acciones-<?= $idSeguro ?>').style.display='none';
                                                            ">
                                                        Rechazar
                                                    </button>
                                                </div>

                                                <!-- Rechazo (oculto) -->
                                                <div id="rechazo-<?= $idSeguro ?>" style="display: none; margin-top: 15px; padding: 15px; background: #fff; border: 1px solid #ddd; border-radius: 6px;">
                                                    <form method="post">
                                                        <input type="hidden" name="correo" value="<?= htmlspecialchars($sol['correo']) ?>">
                                                        <input type="hidden" name="accion_comprobante" value="rechazar">
                                                        <input type="hidden" name="pagina" value="<?= $pagina ?>">

                                                        <div style="font-weight: 600; margin-bottom: 8px; color: var(--accent-navy);">Comentarios del rechazo</div>
                                                        <textarea name="motivo" rows="3" placeholder="Escribe el motivo del rechazo..." style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px; font-family: inherit; font-size: 0.85rem;"></textarea>
                                                        <div style="display: flex; justify-content: flex-end; gap: 8px; margin-top: 10px;">
                                                            <button type="button" class="btn-filter-navy-small" onclick="
                                                                document.getElementById('rechazo-<?= $idSeguro ?>').style.display='none';
                                                                document.getElementById('acciones-<?= $idSeguro ?>').style.display='flex';
                                                            ">Cancelar</button>
                                                            <button type="submit" class="btn-filter-navy-small" style="background: #b02a37;">Confirmar</button>
                                                        </div>
                                                    </form>
                                                </div>

                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <!-- Paginador -->
                        <?php if ($totalPaginas > 1): ?>
                            <div class="paginador" style="margin-top: 20px; display: flex; justify-content: center; gap: 8px; flex-wrap: wrap;">
                                <?php if ($pagina > 1): ?>
                                    <a href="?pagina=<?= $pagina - 1 ?>" class="pagina-btn" style="padding: 6px 14px; border: 1px solid #ccc; border-radius: 4px; text-decoration: none; color: #1a2a3a;">&laquo; Anterior</a>
                                <?php endif; ?>
                                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                                    <a href="?pagina=<?= $i ?>" class="pagina-btn <?= ($i == $pagina) ? 'pagina-activa' : '' ?>" style="padding: 6px 14px; border: 1px solid #ccc; border-radius: 4px; text-decoration: none; color: #1a2a3a; <?= ($i == $pagina) ? 'background: var(--gold); color: #fff; border-color: var(--gold);' : '' ?>"><?= $i ?></a>
                                <?php endfor; ?>
                                <?php if ($pagina < $totalPaginas): ?>
                                    <a href="?pagina=<?= $pagina + 1 ?>" class="pagina-btn" style="padding: 6px 14px; border: 1px solid #ccc; border-radius: 4px; text-decoration: none; color: #1a2a3a;">Siguiente &raquo;</a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- JavaScript para toggle -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.fila-principal').forEach(function(row) {
            row.addEventListener('click', function(e) {
                if (e.target.closest('a') || e.target.closest('button')) return;
                const icon = this.querySelector('.toggle-icon');
                const targetId = icon.getAttribute('data-target');
                const detailRow = document.getElementById(targetId);
                if (detailRow) {
                    const isVisible = detailRow.style.display !== 'none';
                    detailRow.style.display = isVisible ? 'none' : 'table-row';
                    icon.innerHTML = isVisible ? '&#9654;' : '&#9660;';
                }
            });
        });
        document.querySelectorAll('.toggle-icon').forEach(function(icon) {
            icon.addEventListener('click', function(e) {
                e.stopPropagation();
                const targetId = this.getAttribute('data-target');
                const detailRow = document.getElementById(targetId);
                if (detailRow) {
                    const isVisible = detailRow.style.display !== 'none';
                    detailRow.style.display = isVisible ? 'none' : 'table-row';
                    this.innerHTML = isVisible ? '&#9654;' : '&#9660;';
                }
            });
        });
    });
</script>

<style>
    .fila-principal:hover {
        background-color: #f8f5f0;
        transition: background 0.15s;
    }
    .fila-principal .toggle-icon {
        display: inline-block;
        width: 20px;
        font-size: 0.8rem;
        color: var(--gold);
        transition: transform 0.2s;
        user-select: none;
    }
    .fila-detalle td {
        padding: 0 !important;
    }
    .fila-detalle .btn-filter-navy-small {
        padding: 6px 18px;
        font-size: 0.75rem;
    }
    @media (max-width: 768px) {
        .admin-table th, .admin-table td {
            padding: 8px 6px;
            font-size: 0.75rem;
        }
        .fila-detalle div[style*="grid-template-columns"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<?php include 'template/footer.php'; ?>
