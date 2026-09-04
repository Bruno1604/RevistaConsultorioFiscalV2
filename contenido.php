<?php

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: accesoRestringido.php");
    exit();
}

require_once 'data/favoritos.php';

// Datos de esta edición (demo: por ahora la página siempre muestra la 879)
$revista_numero  = '879';
$revista_titulo  = 'Declaración anual de personas físicas';
$revista_periodo = 'Primera quincena de Abril 2026';

// Favoritos es una función exclusiva de suscriptores
$puede_favoritos = isset($_SESSION['rol']) && in_array($_SESSION['rol'], ['suscriptor', 'usuario']);
$es_fav_revista   = $puede_favoritos && es_favorito('revista', $revista_numero);

$page_title = "Edición 879 - Consultorio Fiscal";
$page = "historico";
include 'template/header.php';

?>

<main class="preview-page">
    <section class="rev-header">
        <div class="cs">
            <div class="rev-header__grid">
                <div class="rev-header__visual reveal reveal--left in">
                    <div class="rev-poster">
                        <img src="img/portadas/879.jpg" alt="Portada 879" class="shadow-lg">
                    </div>
                </div>

                <div class="rev-header__info reveal reveal--right">
                    <span class="lbl">Archivo Editorial</span>
                    <h1 class="rev-title">Declaración anual de personas físicas</h1>
                    <p class="hero-static__excerpt">
                        <strong>Número 879</strong> — Primer quincena de Abril 2026
                    </p>
                    <div class="gold-line gold-l"></div>
                    
                    <div class="rev-summary">
                        <h5>Resumen Editorial</h5>
                        <p class="hero-static__excerpt">Esta edición presenta un análisis detallado sobre el proceso de declaración anual para personas físicas, incluyendo los requisitos, plazos y las mejores prácticas para cumplir con las obligaciones fiscales de manera eficiente y sin contratiempos.</p>
                    </div>

                    <div class="rev-actions">
                        <a href="https://repositorios.fca.unam.mx/RevistaConsultorioFiscal/revista-consultorio/" class="btn-ghost btn-ghost--white">
                            <span>Leer Edición Completa</span>
                        </a>

                        <?php if ($puede_favoritos): ?>
                        <button type="button"
                                class="fav-btn fav-btn--dark<?php echo $es_fav_revista ? ' is-activo' : ''; ?>"
                                data-tipo="revista"
                                data-numero="<?php echo htmlspecialchars($revista_numero); ?>"
                                data-titulo="<?php echo htmlspecialchars($revista_titulo); ?>"
                                data-periodo="<?php echo htmlspecialchars($revista_periodo); ?>"
                                data-label-on="En tus favoritos"
                                data-label-off="Agregar a favoritos"
                                aria-pressed="<?php echo $es_fav_revista ? 'true' : 'false'; ?>">
                            <i class="fa-<?php echo $es_fav_revista ? 'solid' : 'regular'; ?> fa-star"></i>
                            <span class="fav-btn__label"><?php echo $es_fav_revista ? 'En tus favoritos' : 'Agregar a favoritos'; ?></span>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="rev-content">
        <div class="cs">
            <div class="row">
                <div class="col-lg-8">
                    <h3 class="section-title-serif">En esta edición</h3>
                    
                    <div class="article-list">
                        <div class="article-item">
                            <span class="article-num">01</span>
                            <div class="article-body">
                                <a href="articulo.php?id=1" class="article-link">Declaración anual de personas físicas</a>
                                <p class="article-author">Por Georgina Ivonne Ramírez Esquivel</p>
                                <p class="article-excerpt">Análisis detallado sobre el proceso de declaración anual para personas físicas, incluyendo los requisitos, plazos y las mejores prácticas para cumplir con las obligaciones fiscales de manera eficiente y sin contratiempos.</p>
                            </div>
                        </div>

                        <div class="article-item">
                            <span class="article-num">02</span>
                            <div class="article-body">
                                <a href="articulo.php?id=2" class="article-link">Paso a paso para la declaración de personas físicas</a>
                                <p class="article-author">Por José Julio Solís García</p>
                                <p class="article-excerpt">Guía práctica para realizar la declaración anual de personas físicas, paso a paso, con ejemplos y recomendaciones.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="rev-sidebar shadow-sm">
                        <h4>Detalles técnicos</h4>
                        <ul class="rev-specs">
                            <li><strong>ISSN:</strong> 0188-7505</li>
                            <li><strong>Páginas:</strong> 84</li>
                            <li><strong>Periodicidad:</strong> Quincenal</li>
                            <li><strong>Editor:</strong> FCA UNAM</li>
                        </ul>

                        <?php if ($puede_favoritos): ?>
                        <div class="fav-pagina-block" style="margin-top: 22px; padding-top: 20px; border-top: 1px solid var(--line);">
                            <h5 style="font-family:'Cormorant Garamond',serif;color:var(--navy);font-weight:700;font-size:1rem;margin-bottom:6px;">
                                Guardar una página
                            </h5>
                            <p style="font-size:.78rem;color:var(--ink-2);margin-bottom:0;">
                                ¿Vas a media lectura? Guarda el número de página para volver directo a ella.
                            </p>
                            <div class="fav-pagina">
                                <input type="number" id="favPaginaInput" min="1" max="84" placeholder="Ej. 24" aria-label="Número de página">
                                <button type="button" id="favPaginaBtn" class="btn-ghost" style="padding: 9px 16px; font-size: .66rem; border-color: var(--navy);">
                                    <span><i class="fa-regular fa-bookmark"></i> Guardar página</span>
                                </button>
                            </div>
                            <p class="fav-msg" id="favPaginaMsg"></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
document.addEventListener("DOMContentLoaded", function() {
    setTimeout(() => {
        document.querySelectorAll('.reveal').forEach(el => {
            el.classList.add('in');
        });
    }, 100);
});

document.getElementById('favPaginaBtn')?.addEventListener('click', function () {
    const boton = this;
    const input = document.getElementById('favPaginaInput');
    const msg = document.getElementById('favPaginaMsg');
    const pagina = parseInt(input.value, 10);

    if (!pagina || pagina < 1) {
        msg.textContent = 'Escribe un número de página válido.';
        msg.classList.add('is-show');
        return;
    }

    boton.disabled = true;
    const payload = new URLSearchParams({
        tipo: 'pagina',
        numero: <?php echo json_encode($revista_numero); ?>,
        pagina: String(pagina),
        titulo: <?php echo json_encode($revista_titulo); ?>,
        seccion: 'Doctrina Fiscal'
    });

    fetch('favoritos_toggle.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: payload.toString()
    })
        .then(r => r.json())
        .then(res => {
            if (!res.ok) {
                msg.textContent = res.error || 'No se pudo guardar la página.';
                msg.classList.add('is-show');
                return;
            }
            msg.textContent = res.activo
                ? ('Página ' + pagina + ' guardada en tus favoritos.')
                : ('Página ' + pagina + ' quitada de tus favoritos.');
            msg.classList.add('is-show');
        })
        .catch(() => {
            msg.textContent = 'No se pudo conectar para guardar la página.';
            msg.classList.add('is-show');
        })
        .finally(() => { boton.disabled = false; });
});
</script>

<script src="<?php echo cf_asset('js/favoritos.js'); ?>"></script>

<?php include 'template/footer.php'; ?>