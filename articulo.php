<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: accesoRestringido.php");
    exit();
}

require_once 'data/favoritos.php';

// ─────────────────────────────────────────────────────────────
// Catálogo de artículos (demo). En una versión conectada a la
// base de datos, este bloque se sustituiría por la consulta del
// artículo (y sus relacionados) según su ID real.
// ─────────────────────────────────────────────────────────────
$articulos_demo = [
    '1' => [
        'titulo'      => 'Declaración anual de personas físicas',
        'autor'       => 'Georgina Ivonne Ramírez Esquivel',
        'seccion'     => 'Declaración Anual',
        'numero'      => '879',
        'descripcion' => 'Requisitos, plazos y mejores prácticas para presentar la declaración anual de personas físicas sin contratiempos ante el SAT.',
    ],
    '2' => [
        'titulo'      => 'Paso a paso para la declaración de personas físicas',
        'autor'       => 'José Julio Solís García',
        'seccion'     => 'Declaración Anual',
        'numero'      => '878',
        'descripcion' => 'Una guía práctica, paso a paso, para completar la declaración anual de personas físicas con ejemplos y recomendaciones.',
    ],
    '3' => [
        'titulo'      => 'Deducciones personales',
        'autor'       => 'Ignacio Jaramillo Bermúdez',
        'seccion'     => 'Declaración Anual',
        'numero'      => '877',
        'descripcion' => 'Un repaso a las deducciones personales permitidas por la LISR y cómo aprovecharlas correctamente en la declaración anual.',
    ],
    '4' => [
        'titulo'      => 'Alcance de la no deducibilidad de salarios en la disminución de la PTU',
        'autor'       => 'Lucía Muñoz',
        'seccion'     => 'Estrategias Fiscales e Investigación Académica',
        'numero'      => '873',
        'descripcion' => 'Un análisis de las reformas de 2014 y su impacto en la base gravable del ISR para la determinación de la PTU pagada.',
    ],
    '5' => [
        'titulo'      => 'Nuevo régimen aplicable a los pagos de previsión social',
        'autor'       => 'Diana Valerio Pino',
        'seccion'     => 'Estrategias Fiscales e Investigación Académica',
        'numero'      => '870',
        'descripcion' => 'Un estudio sobre las prestaciones de previsión social y su tratamiento en la base del impuesto sobre la renta.',
    ],
    '6' => [
        'titulo'      => 'Ajuste anual de ISR a salarios 2023. Casos prácticos',
        'autor'       => 'L.C., E.F. y PC.FI. Arturo Morales',
        'seccion'     => 'Régimen Fiscal de Personas Físicas',
        'numero'      => '868',
        'descripcion' => 'Casos prácticos del ajuste anual de ISR a salarios conforme al artículo 97 de la Ley del Impuesto sobre la Renta.',
    ],
];

$id = (isset($_GET['id']) && isset($articulos_demo[$_GET['id']])) ? (string) $_GET['id'] : '1';
$articulo = $articulos_demo[$id];

// Artículos relacionados: primero de la misma sección, luego se completa con otros
// (PHP convierte las claves numéricas del arreglo a enteros, así que se compara
// como string para no confundir el id actual con uno "distinto").
$relacionados = [];
foreach ($articulos_demo as $rid => $a) {
    if ((string) $rid === $id) continue;
    if ($a['seccion'] === $articulo['seccion']) {
        $relacionados[$rid] = $a;
    }
}
foreach ($articulos_demo as $rid => $a) {
    if (count($relacionados) >= 3) break;
    if ((string) $rid === $id || isset($relacionados[$rid])) continue;
    $relacionados[$rid] = $a;
}
$relacionados = array_slice($relacionados, 0, 3, true);

// Favoritos es una función exclusiva de suscriptores
$puede_favoritos = isset($_SESSION['rol']) && in_array($_SESSION['rol'], ['suscriptor', 'usuario']);
$es_fav = $puede_favoritos && es_favorito('articulo', $id);

$page_title = $articulo['titulo'] . " - Consultorio Fiscal";
$page = "historico";
include 'template/header.php';
?>

<main class="article-preview">
    <header class="article-header" style="background: var(--navy); padding: 48px 0 38px 0;">
        <div class="cs">
            <div class="row">
                <div class="col-lg-12 text-start">
                    <h1 style="color: white; font-family: 'Cormorant Garamond', serif; font-size: 3rem; margin: 0 0 14px 0; line-height: 1.2;">
                        <?php echo htmlspecialchars($articulo['titulo']); ?>
                    </h1>
                    <div class="article-meta" style="color: white; font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 0.95rem; margin-top: 0;">
                        <span>Por <strong><?php echo htmlspecialchars($articulo['autor']); ?></strong></span>
                        <span style="margin: 0 10px; opacity:.5;">&middot;</span>
                        <span><?php echo htmlspecialchars($articulo['seccion']); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="article-body-section" style="padding: 34px 0 42px; background: #fff;">
        <div class="cs">
            <div class="row g-4">
                <!-- Columna izquierda: Información y contenido -->
                <div class="col-lg-8 pe-lg-5 editorial-col-main">
                    <article class="article-summary">
                        <h4 style="font-family: 'Cormorant Garamond', serif; color: var(--navy); font-weight: 700; font-size: 1.6rem; margin-bottom: 16px;">Descripción</h4>
                        <p style="font-size: 1.05rem; line-height: 1.7; color: #333;"><?php echo htmlspecialchars($articulo['descripcion']); ?></p>
                    </article>
                </div>

                <!-- Columna derecha: Panel de Acciones -->
                <div class="col-lg-4 ps-lg-4 editorial-col-side">
                    <aside class="article-actions-panel d-flex flex-column h-100 justify-content-between">
                        <div class="action-buttons-primary d-flex flex-column">
                            <?php if ($puede_favoritos): ?>
                            <button type="button"
                                    class="fav-btn<?php echo $es_fav ? ' is-activo' : ''; ?>"
                                    data-tipo="articulo"
                                    data-articulo_id="<?php echo htmlspecialchars($id); ?>"
                                    data-titulo="<?php echo htmlspecialchars($articulo['titulo']); ?>"
                                    data-autor="<?php echo htmlspecialchars($articulo['autor']); ?>"
                                    data-descripcion="<?php echo htmlspecialchars($articulo['descripcion']); ?>"
                                    data-label-on="En tus favoritos"
                                    data-label-off="Agregar a favoritos"
                                    aria-pressed="<?php echo $es_fav ? 'true' : 'false'; ?>">
                                <i class="fa-<?php echo $es_fav ? 'solid' : 'regular'; ?> fa-star"></i>
                                <span class="fav-btn__label"><?php echo $es_fav ? 'En tus favoritos' : 'Agregar a favoritos'; ?></span>
                            </button>
                            <?php endif; ?>

                            <a href="contenido.php" class="btn-ghost btn-navy-filled">
                                <span>Ver artículo</span>
                            </a>

                            <a href="contenido.php" class="btn-ghost" style="border-color: var(--navy);">
                                <span>Revista completa</span>
                            </a>

                            <button type="button" id="btnDescargar" class="btn-ghost" style="border-color: var(--navy);">
                                <span><i class="fa fa-download"></i> Descargar</span>
                            </button>
                        </div>

                        <div class="action-buttons-footer">
                            <a href="favoritos.php" class="btn-ghost">
                                <span>Mis favoritos</span>
                            </a>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>

    <?php if (!empty($relacionados)): ?>
    <section class="related-section" style="padding: 20px 0 50px; background: #fff; border-top: 1px solid #eee;">
        <div class="cs">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h4 class="related-title" style="font-family: 'Cormorant Garamond', serif; color: var(--navy); font-weight: 700; font-size: 1.5rem; margin: 0;">Artículos relacionados</h4>
                </div>
                <div class="related-nav-controls d-flex gap-2">
                    <button type="button" id="relPrevBtn" class="rel-arrow-btn" aria-label="Anterior">
                        <i class="fa fa-chevron-left"></i>
                    </button>
                    <button type="button" id="relNextBtn" class="rel-arrow-btn" aria-label="Siguiente">
                        <i class="fa fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div class="related-scroll-wrapper">
                <div class="related-scroll-track d-flex gap-4" id="relatedTrack">
                    <?php foreach ($relacionados as $rid => $ra): ?>
                    <div class="related-scroll-item">
                        <a href="articulo.php?id=<?php echo urlencode($rid); ?>" class="article-card-mini related-card related-card-original-style">
                            <span class="article-card-mini__content" style="padding-left: 0;">
                                <h4><?php echo htmlspecialchars($ra['titulo']); ?></h4>
                                <span class="article-card-mini__meta">Por <?php echo htmlspecialchars($ra['autor']); ?></span>
                            </span>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main>

<style>
/* Estilos para el panel de acciones lateral */
.article-actions-panel {
    min-height: 100%;
    padding-top: 0;
    gap: 0.5rem;
}

.article-actions-panel .action-buttons-primary {
    gap: 0.5rem !important;
}

.article-actions-panel .action-buttons-footer {
    margin-top: 6px;
    padding-top: 8px;
    border-top: 1px solid #f0f0f0;
}

.article-actions-panel .fav-btn,
.article-actions-panel .btn-ghost {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    box-sizing: border-box;
    padding: 8px 10px;
    min-height: 34px;
    border-radius: 6px;
    line-height: 1.15;
    font-size: 0.68rem;
}

.article-actions-panel .fav-btn i {
    font-size: 0.75rem;
    margin-right: 5px;
}

.btn-navy-filled {
    background: var(--navy) !important;
    color: #fff !important;
    border-color: var(--navy) !important;
}

.btn-navy-filled:hover {
    background: var(--gold) !important;
    color: white !important;
    border-color: var(--gold) !important;
}

.btn-ghost {
    display: inline-block;
    padding: 8px 10px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-ghost:hover {
    background: var(--gold);
    color: white !important;
    border-color: var(--gold) !important;
}

.btn-ghost span {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.58rem;
    letter-spacing: 0.06em;
}

@media (min-width: 992px) {
    .editorial-col-main {
        border-right: 1px solid #eaeaea;
    }
}

/* Estilos de la sección de artículos relacionados con scroll horizontal */
.related-scroll-wrapper {
    overflow-x: auto;
    scroll-behavior: smooth;
    scrollbar-width: thin;
    padding: 8px 4px 16px 4px;
}

.related-scroll-wrapper::-webkit-scrollbar {
    height: 6px;
}

.related-scroll-wrapper::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 3px;
}

.related-scroll-track {
    display: flex;
    flex-wrap: nowrap;
}

.related-scroll-item {
    flex: 0 0 calc(33.333% - 16px);
    min-width: 270px;
    box-sizing: border-box;
}

@media (max-width: 991px) {
    .related-scroll-item {
        flex: 0 0 calc(50% - 12px);
    }
}

@media (max-width: 576px) {
    .related-scroll-item {
        flex: 0 0 100%;
    }
}

.related-card-original-style {
    display: flex;
    height: 100%;
    text-decoration: none;
    padding: 22px 24px;
    border: 1px solid #eee;
    border-radius: 6px;
    background: #fff;
    transition: all 0.25s var(--e2);
}

.related-card-original-style:hover {
    transform: translateY(-3px);
    border-color: var(--gold);
    box-shadow: 0 6px 20px rgba(0,0,0,0.05);
}

.related-card-original-style .article-card-mini__content h4 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.15rem;
    font-weight: 400;
    color: var(--navy);
    margin: 0 0 10px 0;
    line-height: 1.3;
    transition: color 0.25s var(--e2);
}

.related-card-original-style:hover .article-card-mini__content h4 {
    color: var(--gold);
}

.related-card-original-style .article-card-mini__meta {
    font-size: 0.85rem;
    color: #777;
}

.rel-arrow-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 1px solid #ddd;
    background: #fff;
    color: var(--navy);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.rel-arrow-btn:hover {
    background: var(--navy);
    color: #fff;
    border-color: var(--navy);
}

/* Descargar = imprimir a PDF */
@media print {
    .cfnav, .cfnav__panel, .cfnav__burger, .footer-a, .footer-b,
    .editorial-col-side, .related-section, .rel-arrow-btn { display: none !important; }
    html, body { background: #fff !important; }
    .editorial-col-main { border-right: none !important; }
    .article-header { background: #fff !important; }
    .article-header h1, .article-meta { color: var(--navy) !important; }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    setTimeout(() => {
        document.querySelectorAll('.reveal').forEach(el => {
            el.classList.add('in');
        });
    }, 100);

    const track = document.getElementById('relatedTrack');
    const wrapper = track?.parentElement;
    const prevBtn = document.getElementById('relPrevBtn');
    const nextBtn = document.getElementById('relNextBtn');

    if (wrapper && prevBtn && nextBtn) {
        prevBtn.addEventListener('click', () => {
            wrapper.scrollBy({ left: -320, behavior: 'smooth' });
        });
        nextBtn.addEventListener('click', () => {
            wrapper.scrollBy({ left: 320, behavior: 'smooth' });
        });
    }
});

document.getElementById('btnDescargar')?.addEventListener('click', function () {
    window.print();
});
</script>

<script src="<?php echo cf_asset('js/favoritos.js'); ?>"></script>

<?php include 'template/footer.php'; ?>
