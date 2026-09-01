<?php 
  $page_title = "Artículos más leídos - Consultorio Fiscal";
  $page = "tendencias"; // ID para el menú
  include 'template/header.php'; 
?>

<section class="hero-static bg-navy text-white">
    <div class="cs">
        <span class="lbl text-gold">Ranking Quincenal</span>
        <h1 class="hero-static__title">Tendencias y Artículos más leídos</h1>
        <div class="gold-line"></div>
        <p class="hero-static__excerpt">
            Los temas que están marcando la pauta en el mundo fiscal, analizados por nuestros expertos.
        </p>
    </div>
</section>

<section class="trending-list py-5">
    <div class="cs">
        <div class="row justify-content-center">
            <div class="col-lg-10" id="fullTrendingContainer">
                </div>
        </div>
    </div>
</section>

<script>
// Simulando datos que vendrán de la base de datos
const allTrending = [
    { id: 279, titulo: "Alcance de la no deducibilidad de salarios en la PTU", autor: "Lucía Muñoz", extracto: "Un análisis profundo sobre las reformas de 2014 y su impacto en la base gravable...", visitas: "2,450" },
    { id: 286, titulo: "Nuevo régimen aplicable a pagos de previsión social", autor: "Diana Valerio Pino", extracto: "Investigación sobre las prestaciones que otorga el patrón para satisfacer contingencias...", visitas: "1,890" },
    { id: 123, titulo: "La reforma energética y la política fiscal", autor: "Dr. Pedro Gaytán", extracto: "El papel fundamental del petróleo en la economía mexicana y sus beneficios fiscales...", visitas: "1,520" },
    // ... aquí agregarías los demás del código que me pasaste
];

function renderFullList() {
    const container = document.getElementById('fullTrendingContainer');
    container.innerHTML = allTrending.map((art, index) => `
        <div class="trending-item">
            <div class="trending-item__rank">${index + 1}</div>
            <div class="trending-item__body">
                <span class="trending-item__meta"><i class="fa fa-eye"></i> ${art.visitas} lecturas</span>
                <h3 class="trending-item__title">
                    <a href="contenido.php">${art.titulo}</a>
                </h3>
                <p class="trending-item__author">Por: <strong>${art.autor}</strong></p>
                <p class="trending-item__excerpt">${art.extracto}</p>
                <a href="contenido.php" class="btn-link-gold">Leer artículo completo →</a>
            </div>
        </div>
    `).join('');
}
document.addEventListener('DOMContentLoaded', renderFullList);
</script>

<?php include 'template/footer.php'; ?>