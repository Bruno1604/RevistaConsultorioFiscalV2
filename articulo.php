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
    <header class="article-header" style="background: var(--navy); padding: 80px 0 50px 0;">
        <div class="cs">
            <div class="row">
                <div class="col-lg-10 offset-lg-1 text-center">
                    <span class="lbl" style="color: var(--gold); letter-spacing: 2px;">Artículo Especializado</span>
                    <h1 style="color: white; font-family: 'Cormorant Garamond', serif; font-size: 3rem; margin: 20px 0; line-height: 1.2;">
                        <?php echo htmlspecialchars($articulo['titulo']); ?>
                    </h1>
                    <div class="gold-line mx-auto" style="width: 50px; height: 2px; background: var(--gold); margin-bottom: 25px;"></div>

                    <div class="article-meta" style="color: white; font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 0.95rem;">
                        <span>Por <strong><?php echo htmlspecialchars($articulo['autor']); ?></strong></span>
                        <span style="margin: 0 10px; opacity:.5;">&middot;</span>
                        <span><?php echo htmlspecialchars($articulo['seccion']); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="article-body-section" style="padding: 60px 0 20px; background: #fff;">
        <div class="cs">
            <div class="row justify-content-center">

                <div class="col-lg-8">
                    <article class="article-summary">
                        <h4 style="font-family: 'Cormorant Garamond', serif; color: var(--navy); font-weight: 700; margin-bottom: 16px;">Descripción</h4>
                        <p><?php echo htmlspecialchars($articulo['descripcion']); ?></p>
                    </article>

                    <div class="article-footer-tools">
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

                        <a href="contenido.php" class="btn-ghost" style="border-color: var(--navy);">
                            <span>Ver Revista Completa →</span>
                        </a>

                        <a href="favoritos.php" class="btn-ghost">
                            <span>← Mis Favoritos</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <?php if (!empty($relacionados)): ?>
    <section class="related-section" style="padding: 10px 0 70px; background: #fff;">
        <div class="cs">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h4 class="related-title">Artículos relacionados</h4>
                    <div class="row g-3">
                        <?php $i = 1; foreach ($relacionados as $rid => $ra): ?>
                        <div class="col-md-4">
                            <a href="articulo.php?id=<?php echo urlencode($rid); ?>" class="article-card-mini related-card">
                                <span class="article-card-mini__rank"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></span>
                                <span class="article-card-mini__content">
                                    <h4><?php echo htmlspecialchars($ra['titulo']); ?></h4>
                                    <span class="article-card-mini__meta">Por <?php echo htmlspecialchars($ra['autor']); ?></span>
                                </span>
                            </a>
                        </div>
                        <?php $i++; endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main>

<style>
/* Ajustes para el botón */
.btn-ghost {
    display: inline-block;
    padding: 12px 25px;
    text-decoration: none;
    transition: all 0.3s ease;
}
.btn-ghost:hover {
    background: var(--gold);
    color: white !important;
    border-color: var(--gold);
}
.btn-ghost span {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.7rem;
    letter-spacing: 1px;
}

.related-title{
    font-family: 'Cormorant Garamond', serif;
    color: var(--navy);
    font-weight: 700;
    font-size: 1.4rem;
    margin-bottom: 20px;
    padding-top: 24px;
    border-top: 1px solid #eee;
}
.related-card{ text-decoration: none; }
.related-card:hover{ transform: translateY(-3px); }
.related-card .article-card-mini__content h4{ transition: color .25s var(--e2); }
.related-card:hover .article-card-mini__content h4{ color: var(--gold); }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    setTimeout(() => {
        document.querySelectorAll('.reveal').forEach(el => {
            el.classList.add('in');
        });
    }, 100);
});
</script>

<script src="<?php echo cf_asset('js/favoritos.js'); ?>"></script>

<?php include 'template/footer.php'; ?>
