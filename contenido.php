<?php

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: accesoRestringido.php");
    exit();
}


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
                                <a href="https://repositorios.fca.unam.mx/RevistaConsultorioFiscal/revista-consultorio/" class="article-link">Declaración anual de personas físicas</a>
                                <p class="article-author">Por Georgina Ivonne Ramírez Esquivel</p>
                                <p class="article-excerpt">Análisis detallado sobre el proceso de declaración anual para personas físicas, incluyendo los requisitos, plazos y las mejores prácticas para cumplir con las obligaciones fiscales de manera eficiente y sin contratiempos.</p>
                            </div>
                        </div>

                        <div class="article-item">
                            <span class="article-num">02</span>
                            <div class="article-body">
                                <a href="https://repositorios.fca.unam.mx/RevistaConsultorioFiscal/revista-consultorio/" class="article-link">Paso a paso para la declaración de personas físicas</a>
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
</script>

<?php include 'template/footer.php'; ?>