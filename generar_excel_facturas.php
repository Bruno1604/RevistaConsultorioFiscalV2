<?php
session_start();

// Verificar que el usuario sea administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Datos simulados de solicitudes de factura (incluyendo CP)
$solicitudes = [
    [
        'id' => 1,
        'nombre' => 'Ana María Pérez García',
        'rfc' => 'PEGA850101XXX',
        'correo' => 'ana.perez@ejemplo.com',
        'telefono' => '55 1234 5678',
        'cp' => '01000',
        'uso_cfdi' => 'Gastos en general',
        'regimen' => 'Persona física con actividad empresarial',
        'fecha_solicitud' => '2026-08-15 10:30:00'
    ],
    [
        'id' => 2,
        'nombre' => 'Juan Carlos López Sánchez',
        'rfc' => 'LOSJ900203XXX',
        'correo' => 'juan.lopez@ejemplo.com',
        'telefono' => '55 9876 5432',
        'cp' => '02000',
        'uso_cfdi' => 'Servicios profesionales',
        'regimen' => 'Régimen de confianza (RESICO)',
        'fecha_solicitud' => '2026-08-20 12:15:00'
    ],
    [
        'id' => 3,
        'nombre' => 'María Fernanda Ramírez Cruz',
        'rfc' => 'RACM950512XXX',
        'correo' => 'maria.ramirez@ejemplo.com',
        'telefono' => '55 5678 1234',
        'cp' => '03000',
        'uso_cfdi' => 'Arrendamiento',
        'regimen' => 'Persona moral',
        'fecha_solicitud' => '2026-07-10 09:45:00'
    ],
    [
        'id' => 4,
        'nombre' => 'Roberto Antonio Hernández Ortiz',
        'rfc' => 'HEOR880721XXX',
        'correo' => 'roberto.hernandez@ejemplo.com',
        'telefono' => '55 3456 7890',
        'cp' => '04000',
        'uso_cfdi' => 'Gastos médicos',
        'regimen' => 'Persona física sin actividad empresarial',
        'fecha_solicitud' => '2026-07-25 14:20:00'
    ],
    [
        'id' => 5,
        'nombre' => 'Luis Fernando Martínez Vega',
        'rfc' => 'MAVL920813XXX',
        'correo' => 'luis.martinez@ejemplo.com',
        'telefono' => '55 2345 6789',
        'cp' => '05000',
        'uso_cfdi' => 'Honorarios',
        'regimen' => 'Persona física con actividad empresarial',
        'fecha_solicitud' => '2026-06-05 11:00:00'
    ],
    [
        'id' => 6,
        'nombre' => 'Patricia Elizabeth Gómez Torres',
        'rfc' => 'GOTP750920XXX',
        'correo' => 'patricia.gomez@ejemplo.com',
        'telefono' => '55 8765 4321',
        'cp' => '06000',
        'uso_cfdi' => 'Servicios educativos',
        'regimen' => 'Persona moral',
        'fecha_solicitud' => '2026-06-18 16:30:00'
    ],
    [
        'id' => 7,
        'nombre' => 'Jorge Alejandro Cruz Jiménez',
        'rfc' => 'CUIJ980210XXX',
        'correo' => 'jorge.cruz@ejemplo.com',
        'telefono' => '55 6543 2109',
        'cp' => '07000',
        'uso_cfdi' => 'Gastos en general',
        'regimen' => 'Régimen de confianza (RESICO)',
        'fecha_solicitud' => '2026-05-12 08:45:00'
    ],
    [
        'id' => 8,
        'nombre' => 'Gabriela Isabel Navarro Soto',
        'rfc' => 'NASG890405XXX',
        'correo' => 'gabriela.navarro@ejemplo.com',
        'telefono' => '55 7890 1234',
        'cp' => '08000',
        'uso_cfdi' => 'Arrendamiento',
        'regimen' => 'Persona física con actividad empresarial',
        'fecha_solicitud' => '2026-05-28 13:10:00'
    ]
];

// Función para obtener el mes y año de una fecha
function obtenerMesAnio($fecha) {
    $timestamp = strtotime($fecha);
    return date('Y-m', $timestamp);
}

// Obtener el mes seleccionado (por defecto el mes actual)
$mes_seleccionado = isset($_GET['mes']) ? $_GET['mes'] : date('Y-m');

// Filtrar solicitudes por mes seleccionado
$solicitudes_filtradas = array_filter($solicitudes, function($s) use ($mes_seleccionado) {
    return obtenerMesAnio($s['fecha_solicitud']) === $mes_seleccionado;
});

// Reindexar el arreglo filtrado
$solicitudes_filtradas = array_values($solicitudes_filtradas);

// Si se solicita exportar a Excel (CSV)
if (isset($_GET['exportar']) && $_GET['exportar'] === 'csv') {
    if (empty($solicitudes_filtradas)) {
        header("Location: generar_excel_facturas.php?mes=" . urlencode($mes_seleccionado) . "&error=sin_datos#tabla");
        exit();
    }

    // Configurar cabeceras para descarga de CSV
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="facturas_' . $mes_seleccionado . '.csv"');
    
    // Abrir salida
    $output = fopen('php://output', 'w');
    // Escribir BOM para UTF-8 (compatible con Excel)
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Encabezados (agregar CP)
    $encabezados = [
        'ID',
        'Nombre completo',
        'RFC',
        'Correo electrónico',
        'Teléfono',
        'Código Postal',
        'Uso CFDI',
        'Régimen fiscal',
        'Fecha de solicitud'
    ];
    fputcsv($output, $encabezados);
    
    // Datos
    foreach ($solicitudes_filtradas as $s) {
        fputcsv($output, [
            $s['id'],
            $s['nombre'],
            $s['rfc'],
            $s['correo'],
            $s['telefono'],
            $s['cp'],
            $s['uso_cfdi'],
            $s['regimen'],
            $s['fecha_solicitud']
        ]);
    }
    
    fclose($output);
    exit();
}

$page_title = "Generar Excel para Facturas - Consultorio Fiscal";
$page = "admin";
include 'template/header.php';
?>

<section class="hero-static" style="padding: 60px 0 40px;">
  <div class="cs">
    <div class="hero-static__grid">
      <div class="hero-static__content">
        <span class="c-ph__tag">Administración</span>
        <h1 class="hero-static__title">Generar Excel para Facturas</h1>
        <div class="gold-line gold-l"></div>
        <p class="hero-static__excerpt">
          Selecciona un mes para visualizar los solicitantes de factura y exporta sus datos fiscales a Excel.
        </p>
      </div>
      <div class="hero-static__visual">
        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5">
          <path d="M14 3v4a1 1 0 0 0 1 1h4" />
          <path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2z" />
          <path d="M8 12l2 2 4-4" />
        </svg>
      </div>
    </div>
  </div>
</section>

<section class="about" style="padding: 20px 0 60px;">
  <div class="cs">
    <!-- Controles de navegación de meses -->
    <div class="filtro-mes" style="display: flex; flex-wrap: wrap; align-items: center; gap: 15px; margin-bottom: 30px; padding: 20px; background: #f8f5f0; border-radius: 12px; border: 1px solid #e0d6c8;">
      <form method="get" style="display: flex; flex-wrap: wrap; align-items: center; gap: 10px; flex: 1;" action="#tabla">
        <label for="mes" style="font-weight: 600; color: #1a2a3a;">Seleccionar mes:</label>
        <input type="month" id="mes" name="mes" value="<?= htmlspecialchars($mes_seleccionado) ?>" style="padding: 8px 12px; border: 1px solid #ccc; border-radius: 6px; font-family: inherit;" onchange="this.form.submit()">
        <!-- Botón ya no es necesario porque cambia automáticamente, pero lo dejamos oculto o lo eliminamos -->
      </form>
      <div style="display: flex; gap: 10px;">
        <?php
        // Calcular mes anterior y siguiente
        $timestamp_mes = strtotime($mes_seleccionado . '-01');
        $mes_anterior = date('Y-m', strtotime('-1 month', $timestamp_mes));
        $mes_siguiente = date('Y-m', strtotime('+1 month', $timestamp_mes));
        ?>
        <a href="?mes=<?= $mes_anterior ?>#tabla" class="btn-outline" style="padding: 8px 16px; text-decoration: none; font-size: 0.9rem;">&laquo; Anterior</a>
        <a href="?mes=<?= $mes_siguiente ?>#tabla" class="btn-outline" style="padding: 8px 16px; text-decoration: none; font-size: 0.9rem;">Siguiente &raquo;</a>
      </div>
    </div>

    <!-- Mostrar mensaje de error si no hay datos -->
    <?php if (isset($_GET['error']) && $_GET['error'] === 'sin_datos'): ?>
      <div class="alert alert-warning" style="background: #dff3f7; color: #075985; padding: 12px 20px; border-radius: 8px; border-left: 4px solid #075985; margin-bottom: 20px;">
        ⚠️ No hay datos para exportar en el mes seleccionado.
      </div>
    <?php endif; ?>

    <!-- Tabla de resultados con id="tabla" para anclaje -->
    <div id="tabla" class="table-responsive" style="background: #fff; border-radius: 12px; border: 1px solid #e0d6c8; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
      <table class="table table-striped" style="width: 100%; border-collapse: collapse; margin: 0; font-size: 0.9rem;">
        <thead style="background: #1a2a3a; color: #fff;">
          <tr>
            <th style="padding: 12px 16px; text-align: left;">ID</th>
            <th style="padding: 12px 16px; text-align: left;">Nombre</th>
            <th style="padding: 12px 16px; text-align: left;">RFC</th>
            <th style="padding: 12px 16px; text-align: left;">Correo</th>
            <th style="padding: 12px 16px; text-align: left;">Teléfono</th>
            <th style="padding: 12px 16px; text-align: left;">CP</th>
            <th style="padding: 12px 16px; text-align: left;">Uso CFDI</th>
            <th style="padding: 12px 16px; text-align: left;">Régimen</th>
            <th style="padding: 12px 16px; text-align: left;">Fecha</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($solicitudes_filtradas)): ?>
            <tr>
              <td colspan="9" style="text-align: center; padding: 40px 20px; color: #5a6a7a;">
                <p style="font-size: 1.1rem;">No hay solicitudes de factura para el mes seleccionado.</p>
                <p style="font-size: 0.9rem;">Selecciona otro mes o espera a que los suscriptores envíen sus datos.</p>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($solicitudes_filtradas as $s): ?>
              <tr style="border-bottom: 1px solid #f0ebe4;">
                <td style="padding: 10px 16px;"><?= htmlspecialchars($s['id']) ?></td>
                <td style="padding: 10px 16px;"><?= htmlspecialchars($s['nombre']) ?></td>
                <td style="padding: 10px 16px;"><?= htmlspecialchars($s['rfc']) ?></td>
                <td style="padding: 10px 16px;"><?= htmlspecialchars($s['correo']) ?></td>
                <td style="padding: 10px 16px;"><?= htmlspecialchars($s['telefono']) ?></td>
                <td style="padding: 10px 16px;"><?= htmlspecialchars($s['cp']) ?></td>
                <td style="padding: 10px 16px;"><?= htmlspecialchars($s['uso_cfdi']) ?></td>
                <td style="padding: 10px 16px;"><?= htmlspecialchars($s['regimen']) ?></td>
                <td style="padding: 10px 16px;"><?= date('d/m/Y H:i', strtotime($s['fecha_solicitud'])) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Resumen y botón de exportación -->
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; margin-top: 25px; padding: 15px 20px; background: #faf8f5; border-radius: 8px; border: 1px solid #e8e0d8;">
      <div>
        <span style="font-weight: 600; color: #1a2a3a;">Total de registros:</span>
        <span style="font-size: 1.2rem; font-weight: 700; color: var(--gold); margin-left: 8px;"><?= count($solicitudes_filtradas) ?></span>
      </div>
      <div>
        <?php if (!empty($solicitudes_filtradas)): ?>
          <a href="?mes=<?= urlencode($mes_seleccionado) ?>&exportar=csv#tabla" class="btn-gold" style="display: inline-block; padding: 10px 28px; text-decoration: none; border-radius: 30px; font-weight: 600; background: var(--gold); color: #fff; transition: background 0.2s;">
        Generar Excel
          </a>
        <?php else: ?>
          <button class="btn-gold" disabled style="opacity: 0.6; cursor: not-allowed; padding: 10px 28px; border-radius: 30px; font-weight: 600; background: #aaa; color: #fff; border: none;">
        Generar Excel
          </button>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<style>
  /* Ajustes adicionales para la página */
  .btn-outline {
    display: inline-block;
    padding: 8px 20px;
    border: 2px solid var(--gold, #b8860b);
    border-radius: 30px;
    color: var(--gold, #b8860b);
    background: transparent;
    font-weight: 600;
    transition: all 0.2s;
    text-decoration: none;
    font-size: 0.9rem;
  }
  .btn-outline:hover {
    background: var(--gold, #b8860b);
    color: #fff;
  }
  .table-responsive {
    overflow-x: auto;
  }
  @media (max-width: 768px) {
    .filtro-mes {
      flex-direction: column;
      align-items: stretch;
    }
    .filtro-mes form {
      flex-direction: column;
      width: 100%;
    }
    .filtro-mes input[type="month"] {
      width: 100%;
    }
    .filtro-mes .btn-outline {
      text-align: center;
    }
    .table-responsive table {
      font-size: 0.75rem;
    }
    .table-responsive th,
    .table-responsive td {
      padding: 6px 10px;
    }
  }
</style>

<?php include 'template/footer.php'; ?>