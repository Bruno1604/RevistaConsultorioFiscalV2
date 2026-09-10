<?php
$articulos_gratuitos = [
    '21' => [
        'titulo' => 'El fideicomiso empresarial',
        'autor' => 'Dr. Benjamín Hernández',
        'seccion' => 'Estrategias Fiscales e Investigación Académica',
        'numero' => '—',
        'descripcion' => 'Con la decadencia del imperio romano, en cuanto a la influencia directa en el derecho de la época posterior en los aspectos de los derechos de las personas, es preciso señalar la importancia que tenía el concepto de fideicomiso como una forma de legar bienes.'
    ],
    '22' => [
        'titulo' => 'Capitales constitutivos en materia de seguridad social',
        'autor' => 'L.C. Judith Karen Cárdenas',
        'seccion' => 'Seguridad Social',
        'numero' => '—',
        'descripcion' => 'Es probable que algunos lectores hayan tenido la no muy grata experiencia de recibir una cédula de liquidación de capitales constitutivos emitida por el Instituto Mexicano del Seguro Social (IMSS), por lo que se habrán hecho preguntas como estas: ¿qué pasa?'
    ]
];

$id = isset($_GET['id']) ? (string) $_GET['id'] : '21';
if (!isset($articulos_gratuitos[$id])) {
    header('Location: accesoRestringido.php');
    exit();
}

$articulo = $articulos_gratuitos[$id];
$relacionados = [
    '21' => $articulos_gratuitos['21'] + ['gratuito' => true],
    '22' => $articulos_gratuitos['22'] + ['gratuito' => true],
    '7' => [
        'titulo' => 'Reformas fiscales federales 2026',
        'autor' => 'Equipo Editorial Consultorio Fiscal',
        'gratuito' => false
    ],
    '8' => [
        'titulo' => 'Reglas Generales de Comercio Exterior 2026',
        'autor' => 'Mtro. Roberto Álvarez del Juncal',
        'gratuito' => false
    ]
];
unset($relacionados[$id]);
$relacionados = array_slice($relacionados, 0, 3, true);

$page_title = $articulo['titulo'] . ' - Consultorio Fiscal';
$page = 'historico';
include 'template/header.php';
?>

<main class="article-preview">
    <header class="article-header" style="background: var(--navy); padding: 48px 0 38px 0;">
        <div class="cs">
            <h1 style="color: white; font-family: 'Cormorant Garamond', serif; font-size: 3rem; margin: 0 0 14px 0; line-height: 1.2;">
                <?php echo htmlspecialchars($articulo['titulo']); ?>
            </h1>
            <div class="article-meta" style="color: white; font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: .95rem;">
                <span>Por <strong><?php echo htmlspecialchars($articulo['autor']); ?></strong></span>
                <span style="margin: 0 10px; opacity: .5;">&middot;</span>
                <span><?php echo htmlspecialchars($articulo['seccion']); ?></span>
            </div>
        </div>
    </header>

    <section id="contenido" class="article-body-section" style="padding: 34px 0 42px; background: #fff;">
        <div class="cs">
            <div class="row g-4">
                <div class="col-lg-8 pe-lg-5 editorial-col-main">
                    <article class="article-summary">
                        <span class="article-card__free">Artículo gratuito</span>
                        <h4 style="font-family: 'Cormorant Garamond', serif; color: var(--navy); font-weight: 700; font-size: 1.35rem; margin: 14px 0;">Contenido del artículo</h4>
                        <p style="font-size: 1rem; line-height: 1.65; color: #333;">
                            <?php echo htmlspecialchars($articulo['descripcion']); ?>
                        </p>
                    </article>
                </div>

                <div class="col-lg-4 ps-lg-4 editorial-col-side">
                    <aside class="article-actions-panel d-flex flex-column">
                        <div class="action-buttons-primary d-flex flex-column">
                            <a href="#contenido" class="btn-ghost btn-navy-filled"><span>Ver artículo</span></a>
                            <button type="button" id="btnDescargar" class="btn-ghost" style="border-color: var(--navy);">
                                <span><i class="fa fa-download"></i> Descargar</span>
                            </button>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>

    <section class="related-section" style="padding: 20px 0 50px; background: #fff; border-top: 1px solid #eee;">
        <div class="cs">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="related-title" style="font-family: 'Cormorant Garamond', serif; color: var(--navy); font-weight: 700; font-size: 1.5rem; margin: 0;">Artículos relacionados</h4>
                <div class="related-nav-controls d-flex gap-2">
                    <button type="button" id="relPrevBtn" class="rel-arrow-btn" aria-label="Anterior"><i class="fa fa-chevron-left"></i></button>
                    <button type="button" id="relNextBtn" class="rel-arrow-btn" aria-label="Siguiente"><i class="fa fa-chevron-right"></i></button>
                </div>
            </div>

            <div class="related-scroll-wrapper">
                <div class="related-scroll-track d-flex gap-4" id="relatedTrack">
                    <?php foreach ($relacionados as $rid => $ra):
                        $href = $ra['gratuito'] ? 'articuloGratis.php?id=' . urlencode($rid) : 'accesoRestringido.php';
                    ?>
                    <div class="related-scroll-item">
                        <a href="<?php echo $href; ?>" class="article-card-mini related-card related-card-original-style">
                            <span class="article-card-mini__content" style="padding-left: 0;">
                                <h4><?php if (!$ra['gratuito']): ?><i class="fa fa-lock" aria-hidden="true"></i> <?php endif; ?><?php echo htmlspecialchars($ra['titulo']); ?></h4>
                                <span class="article-card-mini__meta">Por <?php echo htmlspecialchars($ra['autor']); ?></span>
                            </span>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
.article-actions-panel .action-buttons-primary { gap: .5rem; }
.article-actions-panel .btn-ghost {
    width: 100%; display: flex; align-items: center; justify-content: center;
    text-align: center; box-sizing: border-box; padding: 8px 10px;
    min-height: 34px; border-radius: 6px; line-height: 1.15; font-size: .68rem;
}
.btn-navy-filled { background: var(--navy) !important; color: #fff !important; border-color: var(--navy) !important; }
.btn-navy-filled:hover { background: var(--gold) !important; border-color: var(--gold) !important; }
.btn-ghost span { font-weight: 600; text-transform: uppercase; font-size: .58rem; letter-spacing: .06em; }
@media (min-width: 992px) { .editorial-col-main { border-right: 1px solid #eaeaea; } }

.related-scroll-wrapper {
    overflow-x: auto;
    scroll-behavior: smooth;
    scrollbar-width: thin;
    padding: 8px 4px 16px;
}
.related-scroll-wrapper::-webkit-scrollbar { height: 6px; }
.related-scroll-wrapper::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
.related-scroll-track { display: flex; flex-wrap: nowrap; }
.related-scroll-item {
    flex: 0 0 calc(33.333% - 16px);
    min-width: 270px;
    box-sizing: border-box;
}
.related-card-original-style {
    display: flex;
    height: 100%;
    text-decoration: none;
    padding: 22px 24px;
    border: 1px solid #eee;
    border-radius: 6px;
    background: #fff;
    transition: all .25s ease;
}
.related-card-original-style:hover {
    transform: translateY(-3px);
    border-color: var(--gold);
    box-shadow: 0 6px 20px rgba(0, 0, 0, .05);
}
.related-card-original-style .article-card-mini__content h4 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.15rem;
    font-weight: 400;
    color: var(--navy);
    margin: 0 0 10px;
    line-height: 1.3;
}
.related-card-original-style:hover .article-card-mini__content h4 { color: var(--gold); }
.related-card-original-style .article-card-mini__meta { font-size: .72rem; color: #777; }
.rel-arrow-btn {
    width: 36px;
    height: 36px;
    flex: 0 0 36px;
    border-radius: 50%;
    border: 1px solid #ddd;
    background: #fff;
    color: var(--navy);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all .2s ease;
}
.rel-arrow-btn:hover {
    background: var(--navy);
    color: #fff;
    border-color: var(--navy);
}

@media (max-width: 991px) { .related-scroll-item { flex-basis: calc(50% - 12px); } }
@media (max-width: 576px) { .related-scroll-item { flex-basis: 100%; } }

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
document.addEventListener('DOMContentLoaded', function() {
    const track = document.getElementById('relatedTrack');
    const wrapper = track?.parentElement;
    const prevBtn = document.getElementById('relPrevBtn');
    const nextBtn = document.getElementById('relNextBtn');
    if (wrapper && prevBtn && nextBtn) {
        prevBtn.addEventListener('click', () => wrapper.scrollBy({ left: -320, behavior: 'smooth' }));
        nextBtn.addEventListener('click', () => wrapper.scrollBy({ left: 320, behavior: 'smooth' }));
    }
});
document.getElementById('btnDescargar')?.addEventListener('click', function() { window.print(); });
</script>

<?php include 'template/footer.php'; ?>
