<?php 
  //Configuración de la página
  $page_title = "Buscador de artículos - Consultorio Fiscal | FCA UNAM";
  $page = "historico";
  include 'template/header.php'; 
?>

<style>
  .hero-static {
    padding: 60px 0;
  }
  .hero-static__title {
    margin-bottom: 22px;
  }
</style>

<!-- Hero interno -->
<section class="hero-static">
  <div class="cs">
    <div class="hero-static__grid">
      <div class="hero-static__content reveal reveal--left in">
        <span class="c-ph__tag">Hemeroteca</span>
        <h1 class="hero-static__title">Buscador de artículos</h1>
        <p class="hero-static__excerpt reveal reveal--left">
          Encuentra artículos publicados en el Consultorio Fiscal. Busca por título,
          palabras clave o filtra por año de publicación.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Formulario de búsqueda y filtro -->
<section class="about" style="padding: 40px 0 20px 0;">
  <div class="cs">
    <div class="row g-3 align-items-end">
      <div class="col-md-4">
        <label for="busqueda" class="lbl mb-2">Buscar por título</label>
        <input type="text" id="busqueda" class="form-control" placeholder="Ej. declaración anual">
      </div>
      <div class="col-md-3">
        <label for="palabrasClave" class="lbl mb-2">Palabras clave</label>
        <input type="text" id="palabrasClave" class="form-control" placeholder="Ej. ISR, PTU, salarios">
      </div>
      <div class="col-md-3">
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
    <div id="resultadosContainer">
      <!-- Aquí se insertarán dinámicamente las tarjetas horizontales -->
    </div>
    <div id="noResultados" class="text-center py-5" style="display: none;">
      <p class="text-muted">No se encontraron artículos con los criterios seleccionados.</p>
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
  /* ═══ Tarjeta horizontal de artículo ═══ */
  .articulo-card {
    display: flex;
    flex-direction: row;
    background: #fff;
    border: 1px solid rgba(11,30,61,.10);
    border-radius: 6px;
    overflow: hidden;
    padding: 28px 30px;
    margin-bottom: 16px;
    box-shadow: 0 4px 16px rgba(11,30,61,.04);
    transition: all .4s cubic-bezier(.4,0,.2,1);
    gap: 0;
  }
  .articulo-card:hover {
    transform: translateY(-4px);
    border-color: var(--gold);
    box-shadow: 0 16px 40px rgba(11,30,61,.10);
  }

  .articulo-card__accent {
    width: 4px;
    min-height: 100%;
    background: linear-gradient(to bottom, var(--gold), var(--gold-lt));
    border-radius: 4px;
    flex-shrink: 0;
    margin-right: 24px;
  }

  .articulo-card__body {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 6px;
    min-width: 0;
  }

  .articulo-card__meta {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }
  .articulo-card__tag {
    font-family: var(--sans);
    font-size: .58rem;
    font-weight: 700;
    letter-spacing: .18em;
    text-transform: uppercase;
    color: var(--gold);
  }
  .articulo-card__num {
    font-family: var(--sans);
    font-size: .7rem;
    color: var(--ink-3);
  }

  .articulo-card__section {
    margin-left: auto;
    font-family: var(--sans);
    font-size: .58rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--gold);
    text-align: right;
  }

  .articulo-card__title {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 1.25rem;
    font-weight: 400;
    color: var(--navy);
    line-height: 1.25;
    margin: 4px 0 2px;
    transition: color .25s;
  }
  .articulo-card:hover .articulo-card__title {
    color: var(--gold);
  }

  .articulo-card__autor {
    font-family: var(--sans);
    font-size: .82rem;
    color: var(--ink-2);
    margin: 0;
  }
  .articulo-card__autor strong {
    font-weight: 500;
    color: var(--ink);
  }

  .articulo-card__desc {
    font-family: var(--sans);
    font-size: .85rem;
    color: var(--ink-3);
    line-height: 1.6;
    margin: 4px 0 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .articulo-card__footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 10px;
  }

  .articulo-card__link {
    font-family: var(--sans);
    font-size: .68rem;
    font-weight: 600;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: var(--gold);
    border: 1px solid rgba(176,141,76,.4);
    border-radius: 100px;
    padding: 8px 22px;
    transition: all .3s;
    text-decoration: none;
    display: inline-block;
  }
  .articulo-card__link:hover {
    background: var(--gold);
    border-color: var(--gold);
    color: #fff;
  }

  /* ═══ Palabras clave resaltadas ═══ */
  .kw-tag {
    display: inline-block;
    background: rgba(176,141,76,.10);
    color: var(--gold);
    font-family: var(--sans);
    font-size: .65rem;
    font-weight: 600;
    padding: 2px 10px;
    border-radius: 100px;
    letter-spacing: .04em;
    margin-right: 4px;
    margin-top: 4px;
  }

  /* ═══ Responsive ═══ */
  @media (max-width: 768px) {
    .articulo-card {
      padding: 20px 18px;
    }
    .articulo-card__accent {
      margin-right: 16px;
    }
    .articulo-card__title {
      font-size: 1.1rem;
    }
  }
  @media (max-width: 480px) {
    .articulo-card {
      padding: 16px 14px;
    }
    .articulo-card__accent {
      width: 3px;
      margin-right: 12px;
    }
  }
</style>

<script>
  // ===== DATOS DE ARTÍCULOS =====
  const articulos = [
    {
      id: 1,
      titulo: 'Declaración anual de personas físicas',
      autor: 'Georgina Ivonne Ramírez Esquivel',
      seccion: 'Declaración Anual',
      numero: '879',
      fecha: 'Primera de abril 2026',
      anio: 2026,
      descripcion: 'Requisitos, plazos y mejores prácticas para presentar la declaración anual de personas físicas sin contratiempos ante el SAT.',
      palabrasClave: ['declaración anual', 'personas físicas', 'SAT', 'obligaciones fiscales']
    },
    {
      id: 2,
      titulo: 'Paso a paso para la declaración de personas físicas',
      autor: 'José Julio Solís García',
      seccion: 'Declaración Anual',
      numero: '878',
      fecha: 'Segunda de marzo 2026',
      anio: 2026,
      descripcion: 'Una guía práctica, paso a paso, para completar la declaración anual de personas físicas con ejemplos y recomendaciones.',
      palabrasClave: ['declaración anual', 'guía práctica', 'personas físicas', 'ejemplos']
    },
    {
      id: 3,
      titulo: 'Deducciones personales',
      autor: 'Ignacio Jaramillo Bermúdez',
      seccion: 'Declaración Anual',
      numero: '877',
      fecha: 'Primera de marzo 2026',
      anio: 2026,
      descripcion: 'Un repaso a las deducciones personales permitidas por la LISR y cómo aprovecharlas correctamente en la declaración anual.',
      palabrasClave: ['deducciones personales', 'LISR', 'declaración anual', 'beneficios fiscales']
    },
    {
      id: 4,
      titulo: 'Alcance de la no deducibilidad de salarios en la disminución de la PTU',
      autor: 'Lucía Muñoz',
      seccion: 'Estrategias Fiscales e Investigación Académica',
      numero: '873',
      fecha: 'Primera de enero 2026',
      anio: 2026,
      descripcion: 'Un análisis de las reformas de 2014 y su impacto en la base gravable del ISR para la determinación de la PTU pagada.',
      palabrasClave: ['PTU', 'salarios', 'deducibilidad', 'ISR', 'reformas fiscales']
    },
    {
      id: 5,
      titulo: 'Nuevo régimen aplicable a los pagos de previsión social',
      autor: 'Diana Valerio Pino',
      seccion: 'Estrategias Fiscales e Investigación Académica',
      numero: '870',
      fecha: 'Segunda de noviembre 2025',
      anio: 2025,
      descripcion: 'Un estudio sobre las prestaciones de previsión social y su tratamiento en la base del impuesto sobre la renta.',
      palabrasClave: ['previsión social', 'ISR', 'prestaciones', 'régimen fiscal']
    },
    {
      id: 6,
      titulo: 'Ajuste anual de ISR a salarios 2023. Casos prácticos',
      autor: 'L.C., E.F. y PC.FI. Arturo Morales',
      seccion: 'Régimen Fiscal de Personas Físicas',
      numero: '868',
      fecha: 'Segunda de octubre 2025',
      anio: 2025,
      descripcion: 'Casos prácticos del ajuste anual de ISR a salarios conforme al artículo 97 de la Ley del Impuesto sobre la Renta.',
      palabrasClave: ['ISR', 'salarios', 'ajuste anual', 'casos prácticos', 'artículo 97']
    },
    {
      id: 7,
      titulo: 'Reformas fiscales federales 2026',
      autor: 'Equipo Editorial Consultorio Fiscal',
      seccion: 'Reformas Fiscales',
      numero: '873',
      fecha: 'Primera de enero 2026',
      anio: 2026,
      descripcion: 'Análisis integral de las reformas fiscales federales aprobadas para el ejercicio fiscal 2026, incluyendo cambios en ISR, IVA y CFF.',
      palabrasClave: ['reformas fiscales', 'ISR', 'IVA', 'CFF', '2026']
    },
    {
      id: 8,
      titulo: 'Reglas Generales de Comercio Exterior 2026',
      autor: 'Mtro. Roberto Álvarez del Juncal',
      seccion: 'Comercio Exterior',
      numero: '874',
      fecha: 'Segunda de enero 2026',
      anio: 2026,
      descripcion: 'Resumen de las Reglas Generales de Comercio Exterior publicadas para 2026, con énfasis en cambios operativos y simplificación aduanera.',
      palabrasClave: ['comercio exterior', 'aduanas', 'RGCE', 'importación', 'exportación']
    },
    {
      id: 9,
      titulo: 'Declaración informativa múltiple',
      autor: 'Dra. Patricia Fuentes Garza',
      seccion: 'Obligaciones Fiscales',
      numero: '875',
      fecha: 'Primera de febrero 2026',
      anio: 2026,
      descripcion: 'Todo lo que debes saber sobre la Declaración Informativa Múltiple (DIM): fundamentos legales, plazos de presentación y aspectos prácticos.',
      palabrasClave: ['DIM', 'declaración informativa', 'obligaciones fiscales', 'plazos']
    },
    {
      id: 10,
      titulo: 'Nulidad de juicio concluido. Adiós cosa juzgada',
      autor: 'Lic. Fernando Carrasco Medina',
      seccion: 'Derecho Procesal Fiscal',
      numero: '876',
      fecha: 'Segunda de febrero 2026',
      anio: 2026,
      descripcion: 'Estudio sobre la nulidad de juicios concluidos y sus implicaciones en el principio de cosa juzgada dentro del contencioso administrativo.',
      palabrasClave: ['nulidad', 'cosa juzgada', 'juicio fiscal', 'contencioso administrativo']
    },
    {
      id: 11,
      titulo: 'Seguridad social en personas trabajadoras de plataformas digitales',
      autor: 'Mtra. Carmen Soledad Reyes',
      seccion: 'Seguridad Social',
      numero: '872',
      fecha: 'Segunda de diciembre 2025',
      anio: 2025,
      descripcion: 'Análisis del marco regulatorio de seguridad social aplicable a trabajadores de plataformas digitales en México.',
      palabrasClave: ['seguridad social', 'plataformas digitales', 'IMSS', 'trabajadores']
    },
    {
      id: 12,
      titulo: 'Aguinaldo 2025',
      autor: 'C.P. Andrés Montalvo Reyes',
      seccion: 'Régimen Fiscal de Personas Físicas',
      numero: '871',
      fecha: 'Primera de diciembre 2025',
      anio: 2025,
      descripcion: 'Cálculo del aguinaldo 2025: marco legal, exención fiscal, retención de ISR y ejemplos prácticos para empleadores.',
      palabrasClave: ['aguinaldo', 'ISR', 'retención', 'cálculo', 'exención']
    },
    {
      id: 13,
      titulo: 'Reforma a la Ley de Amparo. Una reforma dedicada',
      autor: 'Dr. Miguel Ángel Pérez Vega',
      seccion: 'Derecho Constitucional',
      numero: '869',
      fecha: 'Primera de noviembre 2025',
      anio: 2025,
      descripcion: 'Revisión de la reforma a la Ley de Amparo y su impacto en la defensa constitucional de los contribuyentes.',
      palabrasClave: ['amparo', 'Ley de Amparo', 'reforma', 'defensa constitucional']
    },
    {
      id: 14,
      titulo: 'Ingresos por intereses en personas físicas',
      autor: 'C.P. y M.I. Laura Estrada Núñez',
      seccion: 'Régimen Fiscal de Personas Físicas',
      numero: '868',
      fecha: 'Segunda de octubre 2025',
      anio: 2025,
      descripcion: 'Tratamiento fiscal de los ingresos por intereses en personas físicas: acumulación, retención y declaración anual.',
      palabrasClave: ['intereses', 'personas físicas', 'retención', 'acumulación', 'ISR']
    },
    {
      id: 15,
      titulo: 'Iniciativa de reforma a la Ley de Amparo',
      autor: 'Lic. Francisco Javier Domínguez',
      seccion: 'Derecho Constitucional',
      numero: '867',
      fecha: 'Primera de octubre 2025',
      anio: 2025,
      descripcion: 'Análisis de la iniciativa de reforma a la Ley de Amparo presentada en el Congreso y sus posibles efectos en la materia fiscal.',
      palabrasClave: ['amparo', 'iniciativa de reforma', 'Congreso', 'materia fiscal']
    },
    {
      id: 16,
      titulo: 'Propuesta de reforma fiscal 2026',
      autor: 'Equipo Editorial Consultorio Fiscal',
      seccion: 'Reformas Fiscales',
      numero: '866',
      fecha: 'Segunda de septiembre 2025',
      anio: 2025,
      descripcion: 'Revisión detallada de la propuesta de reforma fiscal para 2026 presentada por el Ejecutivo Federal.',
      palabrasClave: ['reforma fiscal', 'propuesta', '2026', 'Ejecutivo Federal']
    },
    {
      id: 17,
      titulo: 'Pago de dividendos',
      autor: 'C.P. Marcos Reséndiz Lara',
      seccion: 'Régimen Fiscal de Personas Morales',
      numero: '859',
      fecha: 'Primera de junio 2025',
      anio: 2025,
      descripcion: 'Guía completa sobre el pago de dividendos: retención de ISR, CUFIN, acreditamiento y obligaciones del contribuyente.',
      palabrasClave: ['dividendos', 'ISR', 'CUFIN', 'retención', 'personas morales']
    },
    {
      id: 18,
      titulo: 'Plan Nacional de Desarrollo 2025-2030',
      autor: 'Mtra. Sofía del Carmen Yáñez',
      seccion: 'Política Fiscal',
      numero: '860',
      fecha: 'Segunda de junio 2025',
      anio: 2025,
      descripcion: 'Análisis de las implicaciones fiscales del Plan Nacional de Desarrollo 2025-2030 para empresas y personas físicas.',
      palabrasClave: ['PND', 'Plan Nacional de Desarrollo', 'política fiscal', 'implicaciones']
    },
    {
      id: 19,
      titulo: 'Disminución del coeficiente de utilidad para determinar pagos provisionales del ISR',
      autor: 'Dr. Enrique Gaona Sánchez',
      seccion: 'Régimen Fiscal de Personas Morales',
      numero: '861',
      fecha: 'Primera de julio 2025',
      anio: 2025,
      descripcion: 'Procedimiento y requisitos para solicitar la disminución del coeficiente de utilidad ante el SAT.',
      palabrasClave: ['coeficiente de utilidad', 'pagos provisionales', 'ISR', 'SAT']
    },
    {
      id: 20,
      titulo: 'Régimen Simplificado de Confianza. Personas físicas',
      autor: 'Mtro. Javier Mendoza Ortiz',
      seccion: 'Régimen Fiscal de Personas Físicas',
      numero: '862',
      fecha: 'Segunda de julio 2025',
      anio: 2025,
      descripcion: 'Todo sobre el RESICO para personas físicas: requisitos, obligaciones, tasas aplicables y puntos de atención.',
      palabrasClave: ['RESICO', 'régimen simplificado', 'personas físicas', 'tasas', 'obligaciones']
    }
  ].slice(0, 7);

  // Variables de paginación
  let paginaActual = 1;
  const articulosPorPagina = 5;
  let articulosFiltrados = [...articulos];

  // Elementos DOM
  const container = document.getElementById('resultadosContainer');
  const noResultadosDiv = document.getElementById('noResultados');
  const controlesPaginacion = document.getElementById('paginacionControles');
  const btnAnterior = document.getElementById('btnAnterior');
  const btnSiguiente = document.getElementById('btnSiguiente');
  const paginaInfo = document.getElementById('paginaInfo');
  const limpiarBtn = document.getElementById('limpiarBtn');

  function renderizarPagina() {
    const totalPaginas = Math.ceil(articulosFiltrados.length / articulosPorPagina);
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

    const inicio = (paginaActual - 1) * articulosPorPagina;
    const fin = inicio + articulosPorPagina;
    const articulosPagina = articulosFiltrados.slice(inicio, fin);

    // Renderizar tarjetas horizontales
    container.innerHTML = articulosPagina.map(art => `
      <div class="articulo-card">
        <div class="articulo-card__accent"></div>
        <div class="articulo-card__body">
          <div class="articulo-card__meta">
            <span class="articulo-card__tag">${escapeHtml(art.ejemplar || 'Revista Consultorio Fiscal')}</span>
            <span class="articulo-card__num">· Ejemplar No. ${escapeHtml(art.numero)}</span>
            ${art.seccion ? `<span class="articulo-card__section">${escapeHtml(art.seccion)}</span>` : ''}
          </div>
          <h3 class="articulo-card__title">${escapeHtml(art.titulo)}</h3>
          <p class="articulo-card__autor">Por <strong>${escapeHtml(art.autor)}</strong> · ${escapeHtml(art.fecha)}</p>
          <p class="articulo-card__desc">${escapeHtml(art.descripcion)}</p>
          <div style="margin-top: 4px;">
            ${art.palabrasClave.map(kw => `<span class="kw-tag">${escapeHtml(kw)}</span>`).join('')}
          </div>
          <div class="articulo-card__footer">
            <a href="articulo.php?id=${art.id}" class="articulo-card__link">Ver artículo →</a>
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
    const busqueda = document.getElementById('busqueda').value.trim().toLowerCase();
    const palabrasClave = document.getElementById('palabrasClave').value.trim().toLowerCase();
    const anio = document.getElementById('anio').value;

    articulosFiltrados = articulos.filter(art => {
      // Filtro por título
      const coincideTitulo = busqueda === '' || art.titulo.toLowerCase().includes(busqueda);

      // Filtro por palabras clave: busca en título, sección, autor, descripción y keywords
      let coincidePalabrasClave = true;
      if (palabrasClave !== '') {
        const keywords = palabrasClave.split(',').map(k => k.trim()).filter(k => k.length > 0);
        const textoCompleto = [
          art.titulo, art.autor, art.seccion, art.descripcion,
          ...art.palabrasClave
        ].join(' ').toLowerCase();

        coincidePalabrasClave = keywords.some(kw => textoCompleto.includes(kw));
      }

      // Filtro por año
      const coincideAnio = anio === '' || art.anio == anio;

      return coincideTitulo && coincidePalabrasClave && coincideAnio;
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
    document.getElementById('palabrasClave').value = '';
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
    const totalPaginas = Math.ceil(articulosFiltrados.length / articulosPorPagina);
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
  document.getElementById('palabrasClave').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') actualizarResultados();
  });

  // Carga inicial
  actualizarResultados();
</script>

<?php
include 'template/footer.php';
?>
