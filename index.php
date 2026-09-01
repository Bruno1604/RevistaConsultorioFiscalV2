<?php
  $page_title = "Consultorio Fiscal | Revista de la Facultad de Contaduría y Administración, UNAM";
  $page = "inicio";

  // ── Edición en curso ──
  $edicion       = 879;
  $edicion_fecha = "Primera quincena de abril 2026";
  $edicion_tema  = "Declaración anual de personas físicas";
  $edicion_texto = "Un análisis detallado sobre el proceso de declaración anual para personas físicas, incluyendo los requisitos, plazos y las mejores prácticas para cumplir con las obligaciones fiscales de manera eficiente y sin contratiempos.";

  // ── Artículos de la edición / más leídos ──
  $articulos = array(
    array('titulo'=>'Declaración anual de personas físicas',
          'autor' =>'Georgina Ivonne Ramírez Esquivel',
          'lecturas'=>'1,352', 'portada'=>'879'),
    array('titulo'=>'Paso a paso para la declaración de personas físicas',
          'autor' =>'José Julio Solis García',
          'lecturas'=>'1,240', 'portada'=>'878'),
    array('titulo'=>'Deducciones personales',
          'autor' =>'Ignacio Jaramillo Bermúdez',
          'lecturas'=>'980',   'portada'=>'877'),
  );

  // ── Cuadros permanentes ──
  $cuadros = array(
    array('ico'=>'fa-calendar-check','nombre'=>'Tarifas de cálculo de ISR anual','meta'=>'Cuadro permanente'),
    array('ico'=>'fa-money-check-dollar','nombre'=>'Retenciones por ingresos de salarios','meta'=>'Cuadro permanente'),
    array('ico'=>'fa-briefcase','nombre'=>'Pagos provisionales por actividad empresarial','meta'=>'Cuadro permanente'),
  );

  // ── Servicios del ecosistema ──
  $servicios = array(
    array('img'=>'fondo_impacto.jpg', 'etiqueta'=>'Servicio FCA', 'nombre'=>'Asesoría Fiscal',
          'texto'=>'Orientación fiscal especializada de la Facultad de Contaduría y Administración.',
          'accion'=>'Ir al servicio', 'url'=>'https://asesoriafiscal.fca.unam.mx', 'externo'=>true),
    array('img'=>'Consultorio Fiscal Banners-01.jpg', 'etiqueta'=>'Publicación hermana', 'nombre'=>'Revista Emprendedores',
          'texto'=>'Contenido de negocios y emprendimiento publicado por la Facultad.',
          'accion'=>'Visitar', 'url'=>'https://emprendedores.unam.mx/', 'externo'=>true),
    array('img'=>'portadas/874.jpg', 'etiqueta'=>'Sitio institucional', 'nombre'=>'Consultorio Fiscal FCA',
          'texto'=>'El sitio oficial de la revista dentro del portal de la Facultad.',
          'accion'=>'Visitar', 'url'=>'https://fca.unam.mx/consultorioFiscal', 'externo'=>true, 'pos'=>'top'),
  );

  // ── Enlaces de consulta oficial ──
  $organismos = array(
    array('img'=>'logo_sat.png',   'alt'=>'SAT',                    'url'=>'https://www.sat.gob.mx'),
    array('img'=>'dof.png',        'alt'=>'Diario Oficial de la Federación', 'url'=>'https://www.dof.gob.mx'),
    array('img'=>'inegi.png',      'alt'=>'INEGI',                  'url'=>'https://www.inegi.org.mx'),
    array('img'=>'prodecon.png',   'alt'=>'PRODECON',               'url'=>'https://www.prodecon.gob.mx'),
    array('img'=>'economia.png',   'alt'=>'Secretaría de Economía', 'url'=>'https://www.gob.mx/se'),
    array('img'=>'radio-unam.png', 'alt'=>'Radio UNAM',             'url'=>'https://www.radio.unam.mx'),
    array('img'=>'publishing.png',  'alt'=>'Librería electrónica UNAM-FCA', 'url'=>'https://publishing.fca.unam.mx/'),
  );

  include 'template/header.php';
?>

<!-- ══════════════ HÉROE ══════════════ -->
<section class="hero">
  <div class="hero__bg"></div>
  <div class="hero__glow"></div>

  <div class="hero__in wrap">
    <div class="hero__grid">

      <div class="hero__copy">
        <div class="hero__flag rise rise--1">
          <span class="hero__flag-dot"></span>
          <span>Edición <?php echo $edicion; ?> · en circulación</span>
        </div>

        <h1 class="hero__title rise rise--2"><?php echo $edicion_tema; ?></h1>
        <div class="hero__rule rise rise--3"></div>
        <p class="hero__lead rise rise--4"><?php echo $edicion_texto; ?></p>

        <div class="hero__cta rise rise--5">
          <a href="contenido.php" class="cfbtn cfbtn--gold">Leer la edición <i class="fa fa-arrow-right" style="font-size:.7rem"></i></a>
          <a href="login.php" class="cfbtn cfbtn--ghost">Suscribirse</a>
        </div>
      </div>

      <div class="hero__cover rise rise--3">
        <img src="img/portadas/<?php echo $edicion; ?>.jpg"
             alt="Portada de Consultorio Fiscal, número <?php echo $edicion; ?>"
             class="hero__cover-img">
      </div>

    </div>
  </div>

</section>

<!-- ══════════════ LO MÁS LEÍDO — paneles expansibles ══════════════ -->
<section class="folio" id="lectura">
  <div class="wrap">

    <div class="folio__head rv">
      <div>
        <span class="eyebrow">Lo más leído</span>
        <h2>Lectura de la quincena</h2>
      </div>
      <a href="tendencias.php" class="block-link">Repositorio completo <i class="fa fa-arrow-right"></i></a>
    </div>

    <div class="folio__deck" id="folioDeck">
      <?php $i = 1; foreach ($articulos as $a): ?>
      <a href="previewArticulo.php"
         class="folio__panel<?php echo ($i === 1) ? ' on' : ''; ?>"
         data-panel="<?php echo $i; ?>">

        <span class="folio__bg" style="background-image:url('img/portadas/<?php echo $a['portada']; ?>.jpg')"></span>
        <span class="folio__scrim"></span>

        <!-- Estado recogido: número y título en vertical -->
        <span class="folio__spine">
          <span class="folio__spine-num"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></span>
          <span class="folio__spine-text"><?php echo $a['titulo']; ?></span>
        </span>

        <!-- Estado abierto -->
        <span class="folio__open">
          <span class="folio__num"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></span>
          <span class="folio__body">
            <span class="folio__title"><?php echo $a['titulo']; ?></span>
            <span class="folio__author"><?php echo $a['autor']; ?></span>
            <span class="folio__foot">
              <span class="folio__reads"><?php echo $a['lecturas']; ?> lecturas</span>
              <span class="folio__go">Leer <i class="fa fa-arrow-right"></i></span>
            </span>
          </span>
        </span>

      </a>
      <?php $i++; endforeach; ?>
    </div>

  </div>
</section>

<!-- ══════════════ CUADROS FISCALES — calculadora interactiva ══════════════ -->
<section class="tool">
  <div class="wrap tool__in">

    <div class="tool__head rv">
      <span class="eyebrow">Herramienta de consulta</span>
      <h2>Cuadros e indicadores, ahora en cálculo directo</h2>
      <p>Escribe un ingreso y la tarifa vigente 2026 se resuelve sola: se ilumina el rango que te aplica y se calcula el impuesto. Sin abrir el PDF, sin buscar el renglón a mano.</p>
    </div>

    <div class="calc rv">

      <!-- Entrada -->
      <div class="calc__panel">
        <span class="calc__label">Periodo del ingreso</span>
        <div class="calc__chips" id="calcChips">
          <button type="button" class="calc__chip on" data-period="mensual">Mensual</button>
          <button type="button" class="calc__chip" data-period="quincenal">Quincenal</button>
          <button type="button" class="calc__chip" data-period="anual">Anual</button>
        </div>

        <span class="calc__label">Ingreso gravable</span>
        <div class="calc__field">
          <span class="calc__currency">$</span>
          <input type="text" inputmode="decimal" class="calc__input" id="calcInput"
                 placeholder="25,000" value="25,000" aria-label="Ingreso gravable">
        </div>
        <p class="calc__note">Cálculo de referencia con la tarifa del artículo 96 de la LISR vigente en 2026. No sustituye la determinación oficial ni considera subsidio al empleo.</p>

        <div class="calc__out">
          <div class="calc__stat">
            <span>Tasa marginal</span>
            <b id="outRate">—</b>
          </div>
          <div class="calc__stat calc__stat--gold">
            <span>ISR a cargo</span>
            <b id="outTax">—</b>
          </div>
          <div class="calc__stat">
            <span>Cuota fija</span>
            <b id="outFixed">—</b>
          </div>
          <div class="calc__stat">
            <span>Tasa efectiva</span>
            <b id="outEff">—</b>
          </div>
        </div>
      </div>

      <!-- Tabla -->
      <div class="calc__table-wrap">
        <div class="calc__table-head">
          <span class="calc__table-title" id="tableTitle">Tarifa mensual · ISR 2026</span>
          <span class="calc__table-meta" id="tableMeta">11 rangos</span>
        </div>
        <div class="calc__scroll">
          <table class="calc__table">
            <thead>
              <tr>
                <th>Límite inferior</th>
                <th>Límite superior</th>
                <th>Cuota fija</th>
                <th>% excedente</th>
              </tr>
            </thead>
            <tbody id="calcBody"></tbody>
          </table>
        </div>
        <div class="calc__foot">
          <small>Fuente: tarifas ISR 2026 publicadas por la Facultad de Contaduría y Administración.</small>
          <a href="cuadrosPermanentes.php" class="cfbtn cfbtn--line cfbtn--sm">Ver todos los cuadros</a>
        </div>
      </div>

    </div>

    <!-- Accesos a los cuadros permanentes -->
    <div class="tool__links">
      <?php foreach ($cuadros as $c): ?>
      <a href="cuadrosPermanentes.php" class="tool__link rv">
        <span class="tool__link-ico"><i class="fa <?php echo $c['ico']; ?>"></i></span>
        <span>
          <b><?php echo $c['nombre']; ?></b>
          <small><?php echo $c['meta']; ?></small>
        </span>
      </a>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<!-- ══════════════ SERVICIOS Y ECOSISTEMA ══════════════ -->
<section class="beyond">
  <div class="wrap">

    <div class="block-head rv">
      <div>
        <span class="eyebrow">Servicios y ecosistema</span>
        <h2>Más allá de la revista</h2>
      </div>
    </div>

    <div class="beyond__grid">
      <?php foreach ($servicios as $sv): ?>
      <a href="<?php echo $sv['url']; ?>" class="cfcard rv"
         <?php if (!empty($sv['externo'])): ?>target="_blank" rel="noopener"<?php endif; ?>>

        <span class="cfcard__media">
          <img src="img/<?php echo $sv['img']; ?>" alt="" loading="lazy"
               <?php if (!empty($sv['pos'])): ?>style="object-position:center <?php echo $sv['pos']; ?>"<?php endif; ?>>
          <span class="cfcard__label"><?php echo $sv['etiqueta']; ?></span>
        </span>

        <span class="cfcard__body">
          <span class="cfcard__title"><?php echo $sv['nombre']; ?></span>
          <span class="cfcard__rule"></span>
          <span class="cfcard__desc"><?php echo $sv['texto']; ?></span>
          <span class="cfcard__go"><?php echo $sv['accion']; ?> <i class="fa fa-arrow-right"></i></span>
        </span>

      </a>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<!-- ══════════════ ENLACES INSTITUCIONALES ══════════════ -->
<section class="orgs">
  <div class="wrap">
    <span class="orgs__label rv">Consulta oficial</span>
    <div class="orgs__row">
      <?php foreach ($organismos as $o): ?>
      <a href="<?php echo $o['url']; ?>" target="_blank" rel="noopener" class="orgs__item rv" title="<?php echo $o['alt']; ?>">
        <img src="img/<?php echo $o['img']; ?>" alt="<?php echo $o['alt']; ?>">
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include 'template/footer.php'; ?>
