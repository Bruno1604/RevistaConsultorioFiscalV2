<?php 
  //Configuración de la página
  $page_title = "Historico - Consultorio Fiscal | FCA UNAM";
  $page = "historico";
  include 'template/header.php'; 
?>

<!-- Hero interno -->
<section class="hero-static">
  <div class="cs">
    <div class="hero-static__grid">
      <div class="hero-static__content reveal reveal--left in">
        <span class="c-ph__tag">Hemeroteca</span>
        <h1 class="hero-static__title">Histórico de revistas</h1>
        <div class="gold-line gold-l"></div>
        <p class="hero-static__excerpt reveal reveal--left">
          Consulta los ejemplares publicados por el Consultorio Fiscal. Encuentra análisis,
          doctrina y jurisprudencia actualizada.
        </p>
      </div>
      <div class="hero-static__visual reveal reveal--right in">
        <div class="hero-static__img-box">
          <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5">
            <path d="M4 4h16v16H4zM8 8h8M8 12h6M8 16h4" />
          </svg>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Formulario de búsqueda y filtro -->
<section class="about" style="padding: 40px 0 20px 0;">
  <div class="cs">
    <div class="row g-3 align-items-end">
      <div class="col-md-6">
        <label for="busqueda" class="lbl mb-2">Buscar por título</label>
        <input type="text" id="busqueda" class="form-control" placeholder="Ej. deducciones personales">
      </div>
      <div class="col-md-4">
        <label for="anio" class="lbl mb-2">Filtrar por año</label>
        <select id="anio" class="form-select">
          <option value="">Todos los años</option>
          <option value="2026">2026</option>
          <option value="2025">2025</option>
          <option value="2024">2024</option>
          <option value="2023">2023</option>
        </select>
      </div>
      <div class="col-md-2">
        <button id="filtrarBtn" class="btn-ghost w-100" style="border-color: var(--navy);">
          <span>Filtrar</span>
        </button>
      </div>
    </div>
  </div>
</section>

<!-- Resultados con espacio superior -->
<section class="eco-section" style="padding-top: 40px;">
  <div class="cs">
    <div id="resultadosContainer" class="row g-4">
      <!-- Aquí se insertarán dinámicamente las tarjetas -->
    </div>
    <div id="noResultados" class="text-center py-5" style="display: none;">
      <p class="text-muted">No se encontraron revistas con los criterios seleccionados.</p>
      <button id="limpiarBtn" class="btn-ghost mt-3"><span>Limpiar filtros</span></button>
    </div>

    <!-- Controles de paginación -->
    <div id="paginacionControles" class="d-flex justify-content-between align-items-center mt-4" style="display: none;">
      <button id="btnAnterior" class="btn-ghost" style="padding: 8px 20px; font-size: 0.7rem;">
        <span>← Anterior</span>
      </button>
      <span id="paginaInfo" class="text-muted" style="font-size: 0.85rem;">Página 1 de 1</span>
      <button id="btnSiguiente" class="btn-ghost" style="padding: 8px 20px; font-size: 0.7rem;">
        <span>Siguiente →</span>
      </button>
    </div>
  </div>
</section>

<style>
  .card-img-portada {
    width: 100%;
    aspect-ratio: 3 / 4;
    object-fit: cover;
    border-bottom: 1px solid rgba(184,150,85,0.2);
  }
  .broadcast-card {
    display: flex;
    flex-direction: column;
    overflow: hidden;
    padding: 0 !important;
  }
  .broadcast-card__content {
    padding: 24px;
  }
  .broadcast-card__head {
    margin-bottom: 12px;
  }
</style>

<script>
  // ===== DATOS ESTÁTICOS DE REVISTAS =====
  const revistas = [
    { id: 295, numero: "879", titulo: "Declaración anual de personas físicas 2026", fecha: "Primera de abril 2026", anio: 2026 },
    { id: 294, numero: "878", titulo: "Deducciones personales en la declaración anual", fecha: "Segunda de marzo 2026", anio: 2026 },
    { id: 293, numero: "877", titulo: "Declaración anual de personas morales del régimen general", fecha: "Primera de marzo 2026", anio: 2026 },
    { id: 292, numero: "876", titulo: "Nulidad de juicio concluido. Adiós cosa juzgada", fecha: "Segunda de febrero 2026", anio: 2026 },
    { id: 291, numero: "875", titulo: "Declaración informativa múltiple", fecha: "Primera de febrero 2026", anio: 2026 },
    { id: 290, numero: "874", titulo: "Reglas Generales de Comercio Exterior 2026", fecha: "Segunda de enero 2026", anio: 2026 },
    { id: 289, numero: "873", titulo: "Reformas fiscales federales 2026", fecha: "Primera de enero 2026", anio: 2026 },
    { id: 288, numero: "872", titulo: "Seguridad social en personas trabajadoras de plataformas digitales", fecha: "Segunda de diciembre 2025", anio: 2025 },
    { id: 287, numero: "871", titulo: "Aguinaldo 2025", fecha: "Primera de diciembre 2025", anio: 2025 },
    { id: 286, numero: "870", titulo: "Reformas Fiscales 2026", fecha: "Segunda de noviembre 2025", anio: 2025 },
    { id: 285, numero: "869", titulo: "Reforma a la Ley de Amparo. Una reforma dedicada", fecha: "Primera de noviembre 2025", anio: 2025 },
    { id: 284, numero: "868", titulo: "Ingresos por intereses en personas físicas", fecha: "Segunda de octubre 2025", anio: 2025 },
    { id: 283, numero: "867", titulo: "Iniciativa de reforma a la Ley de Amparo", fecha: "Primera de octubre 2025", anio: 2025 },
    { id: 282, numero: "866", titulo: "Propuesta de reforma fiscal 2026", fecha: "Segunda de septiembre 2025", anio: 2025 },
    { id: 281, numero: "865", titulo: "Se levanta aplazamiento de amparos 2014, ¿justicia pronta y expedita?", fecha: "Primera de septiembre 2025", anio: 2025 },
    { id: 280, numero: "864", titulo: "Negativa de devolución por forma, ¿debe impugnarse según jurisprudencia?", fecha: "Segunda de agosto 2025", anio: 2025 },
    { id: 279, numero: "863", titulo: "Modificaciones 2025 a la Ley Antilavado", fecha: "Primera de agosto 2025", anio: 2025 },
    { id: 278, numero: "862", titulo: "Régimen Simplificado de Confianza. Personas físicas", fecha: "Segunda de julio 2025", anio: 2025 },
    { id: 277, numero: "861", titulo: "Disminución del coeficiente de utilidad para determinar pagos provisionales del ISR", fecha: "Primera de julio 2025", anio: 2025 },
    { id: 276, numero: "860", titulo: "Plan Nacional de Desarrollo 2025-2030", fecha: "Segunda de junio 2025", anio: 2025 },
    { id: 275, numero: "859", titulo: "Pago de dividendos", fecha: "Primera de junio 2025", anio: 2025 }
  ];

  // Variables de paginación
  let paginaActual = 1;
  const revistasPorPagina = 6;
  let revistasFiltradas = [...revistas];

  // Elementos DOM
  const container = document.getElementById('resultadosContainer');
  const noResultadosDiv = document.getElementById('noResultados');
  const controlesPaginacion = document.getElementById('paginacionControles');
  const btnAnterior = document.getElementById('btnAnterior');
  const btnSiguiente = document.getElementById('btnSiguiente');
  const paginaInfo = document.getElementById('paginaInfo');
  const limpiarBtn = document.getElementById('limpiarBtn');

  function renderizarPagina() {
    const totalPaginas = Math.ceil(revistasFiltradas.length / revistasPorPagina);
    if (totalPaginas === 0) {
      container.innerHTML = '';
      noResultadosDiv.style.display = 'block';
      controlesPaginacion.style.display = 'none';
      return;
    }

    noResultadosDiv.style.display = 'none';
    controlesPaginacion.style.display = 'flex';

    // Asegurar que la página actual esté dentro del rango
    if (paginaActual > totalPaginas) paginaActual = totalPaginas;
    if (paginaActual < 1) paginaActual = 1;

    const inicio = (paginaActual - 1) * revistasPorPagina;
    const fin = inicio + revistasPorPagina;
    const revistasPagina = revistasFiltradas.slice(inicio, fin);

    // Renderizar tarjetas
    container.innerHTML = revistasPagina.map(rev => `
      <div class="col-md-6 col-lg-4">
        <div class="broadcast-card" style="padding: 0; text-align: left;">
          <img src="img/portadas/${rev.numero}.jpg" 
              class="card-img-portada"
              onerror="this.onerror=null; this.style.display='none';">
          <div class="broadcast-card__content">
            <div class="broadcast-card__head" style="margin-bottom: 12px;">
              <span class="broadcast-card__platform">Ejemplar No. ${rev.numero}</span>
            </div>
            <h3 class="broadcast-card__channel" style="font-size: 1.2rem; margin-bottom: 8px;">
              ${escapeHtml(rev.titulo)}
            </h3>
            <p class="broadcast-card__time" style="margin-bottom: 20px;">
              ${escapeHtml(rev.fecha)}
            </p>
            <a href="contenido.php" class="eco-card__btn">
              Leer revista →
            </a>
          </div>
        </div>
      </div>
    `).join('');

    // Actualizar controles
    btnAnterior.disabled = (paginaActual === 1);
    btnSiguiente.disabled = (paginaActual === totalPaginas);
    paginaInfo.textContent = `Página ${paginaActual} de ${totalPaginas}`;
  }

  function actualizarResultados() {
    // Aplicar filtros
    const busqueda = document.getElementById('busqueda').value.trim().toLowerCase();
    const anio = document.getElementById('anio').value;

    revistasFiltradas = revistas.filter(rev => {
      const coincideBusqueda = busqueda === '' || rev.titulo.toLowerCase().includes(busqueda);
      const coincideAnio = anio === '' || rev.anio == anio;
      return coincideBusqueda && coincideAnio;
    });

    // Reiniciar a página 1 y renderizar
    paginaActual = 1;
    renderizarPagina();
  }

  function escapeHtml(str) {
    return str.replace(/[&<>]/g, function(m) {
      if (m === '&') return '&amp;';
      if (m === '<') return '&lt;';
      if (m === '>') return '&gt;';
      return m;
    });
  }

  function limpiarFiltros() {
    document.getElementById('busqueda').value = '';
    document.getElementById('anio').value = '';
    actualizarResultados();
  }

  // Eventos de paginación
  btnAnterior.addEventListener('click', function() {
    if (paginaActual > 1) {
      paginaActual--;
      renderizarPagina();
    }
  });

  btnSiguiente.addEventListener('click', function() {
    const totalPaginas = Math.ceil(revistasFiltradas.length / revistasPorPagina);
    if (paginaActual < totalPaginas) {
      paginaActual++;
      renderizarPagina();
    }
  });

  // Eventos de búsqueda/filtro
  document.getElementById('filtrarBtn').addEventListener('click', actualizarResultados);
  document.getElementById('limpiarBtn').addEventListener('click', limpiarFiltros);
  document.getElementById('busqueda').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') actualizarResultados();
  });

  // Carga inicial
  actualizarResultados();
</script>

<?php
include 'template/footer.php';
?>