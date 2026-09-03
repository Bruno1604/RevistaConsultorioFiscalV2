<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: accesoRestringido.php");
    exit();
}

require_once 'data/favoritos.php';

// Este artículo corresponde al id "1" del catálogo demo usado en articulo.php,
// para que favoritearlo desde aquí o desde allá sea el mismo favorito.
$articulo_id = '1';
$puede_favoritos = isset($_SESSION['rol']) && in_array($_SESSION['rol'], ['suscriptor', 'usuario']);
$es_fav = $puede_favoritos && es_favorito('articulo', $articulo_id);

$page_title = "Declaración anual de personas físicas - Consultorio Fiscal";
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
                        Declaración anual de personas físicas
                    </h1>
                    <div class="gold-line mx-auto" style="width: 50px; height: 2px; background: var(--gold); margin-bottom: 25px;"></div>
                    
                    <div class="article-meta" style="color: white; font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 0.95rem;">
                        <span>Por <strong>Georgina Ivonne Ramírez Esquivel</strong></span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="article-body-section" style="padding: 60px 0; background: #fff;">
        <div class="cs">
            <div class="row justify-content-center">
                
                <div class="col-lg-7">
                    <article class="article-summary" style="font-family: 'Montserrat', sans-serif; line-height: 1.8; color: #444; font-size: 1.1rem;">
                        <h4 style="font-family: 'Cormorant Garamond', serif; color: var(--navy); font-weight: 700; margin-bottom: 20px;">Resumen del artículo</h4>
                        <p>
                            Un análisis detallado sobre el proceso de declaración anual para personas físicas, incluyendo los requisitos, plazos y las mejores prácticas para cumplir con las obligaciones fiscales de manera eficiente y sin contratiempos.
                        </p>
                        <p>
                            Este artículo es esencial para contribuyentes que buscan entender a fondo el marco normativo vigente y optimizar su declaración anual, evitando errores comunes y aprovechando las deducciones permitidas por la ley.
                        </p>
                    </article>

                    <div class="article-footer-tools mt-5 pt-4" style="border-top: 1px solid #eee;">
                        <?php if ($puede_favoritos): ?>
                        <button type="button"
                                class="fav-btn<?php echo $es_fav ? ' is-activo' : ''; ?>"
                                data-tipo="articulo"
                                data-articulo_id="<?php echo htmlspecialchars($articulo_id); ?>"
                                data-titulo="Declaración anual de personas físicas"
                                data-autor="Georgina Ivonne Ramírez Esquivel"
                                data-descripcion="Requisitos, plazos y mejores prácticas para presentar la declaración anual de personas físicas sin contratiempos ante el SAT."
                                data-label-on="En tus favoritos"
                                data-label-off="Agregar a favoritos"
                                aria-pressed="<?php echo $es_fav ? 'true' : 'false'; ?>">
                            <i class="fa-<?php echo $es_fav ? 'solid' : 'regular'; ?> fa-star"></i>
                            <span class="fav-btn__label"><?php echo $es_fav ? 'En tus favoritos' : 'Agregar a favoritos'; ?></span>
                        </button>
                        <?php endif; ?>

                        <a href="buscar.php" class="btn-ghost" style="border-color: var(--navy);">
                            <span>← Volver al Histórico</span>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 offset-lg-1">
                    <div class="sticky-top" style="top: 120px;">
                        <div class="p-4 shadow-sm" style="background: #fdfdfd; border: 1px solid #eee; border-radius: 4px;">
                            <h5 style="font-family: 'Cormorant Garamond', serif; font-weight: 700; color: var(--navy); margin-bottom: 15px; border-bottom: 1px solid var(--gold); padding-bottom: 10px;">
                                Acerca de esta edición
                            </h5>
                            
                            <ul class="list-unstyled" style="font-size: 0.85rem; line-height: 2;">
                                <li><strong>Ejemplar:</strong> No. 879</li>
                                <li><strong>Periodo:</strong> Primer quincena de Abril</li>
                                <li><strong>Año:</strong> 2026</li>
                                <li><strong>Sección:</strong> Doctrina Fiscal</li>
                            </ul>

                            <a href="contenido.php" class="btn-ghost w-100 mt-4" style="background: var(--navy); color: white; text-align: center; border: none;">
                                <span>Ver Revista Completa →</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
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
</style>

<script src="<?php echo cf_asset('js/favoritos.js'); ?>"></script>

<?php include 'template/footer.php'; ?>
