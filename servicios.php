<?php
$page_title = "Servicios - Consultorio Fiscal | FCA UNAM"; // Título de la pestaña
  $page = "servicios"; // ID para que el menú marque "Servicios" como activo
    include 'template/header.php'; 
?>

<!-- Hero de página interna -->
<section class="hero-static">
  <div class="cs">
    <div class="hero-static__grid">
      <!-- Columna izquierda: título y botón -->
      <div class="hero-static__content reveal reveal--left in">
        <span class="c-ph__tag">Consultorio Fiscal UNAM</span>
        <h1 class="hero-static__title">Servicios de Asesoría Fiscal</h1>
        <div class="gold-line gold-l"></div>
        <div class="hero-static__actions">
          <a href="https://asesoriafiscal.fca.unam.mx" class="btn-ghost btn-ghost--white" target="_blank" rel="noopener noreferrer">
            <span>Contactar Asesoría</span>
          </a>
        </div>
      </div>
      <!-- Columna derecha: texto descriptivo -->
      <div class="hero-static__excerpt-col reveal reveal--right in">
        <p class="hero-static__excerpt reveal reveal--right">
          Apoyo gratuito en materia fiscal, laboral y de seguridad social para personas de escasos recursos, 
          a través de estudiantes de la carrera de Contaduría que realizan su servicio social.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Sección: Requisitos de los estudiantes -->
<section class="about">
  <div class="cs">
    <div class="about__grid">
      <div class="about__intro reveal reveal--left">
        <span class="lbl">Participación estudiantil</span>
        <h2>Formación práctica con impacto social</h2>
        <div class="gold-line gold-l"></div>
        <p>
          Los estudiantes de la Licenciatura en Contaduría que colaboran en el Consultorio Fiscal 
          desarrollan habilidades profesionales mientras brindan un servicio esencial a la comunidad.
        </p>
      </div>
      <div class="about__body reveal reveal--right">
        <p>
          La Asesoría Fiscal Gratuita es proporcionada por estudiantes que cubren los siguientes requisitos:
        </p>
        <ul class="activ-list">
          <li><span class="dot"></span> Tener más del 50% de los créditos de la carrera.</li>
          <li><span class="dot"></span> Aprobar un examen de conocimientos fiscales y una entrevista de selección.</li>
          <li><span class="dot"></span> Realizar su servicio social en esta área, una de las más demandadas.</li>
          <li><span class="dot"></span> Contar con un fuerte espíritu de servicio.</li>
        </ul>
        <p>
          El servicio se intensifica durante la temporada de declaraciones anuales, extendiéndose a toda la 
          comunidad universitaria.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Sección: Impacto y colaboración (parallax) -->
<section class="mv-parallax" style="background-image: linear-gradient(to bottom, rgba(0,14,35,0.5) 0%, rgba(0,14,35,0.5) 100%), url('img/fondo_impacto.jpg') !important; background-size: cover !important; background-position: center !important; background-attachment: fixed !important;">
  <div class="cs">
    <div class="mv-parallax__inner">
      <div class="row">
        <div class="col-md-6 mv-parallax__col">
          <h3>Colaboración con el SAT</h3>
          <div class="gold-line gold-l"></div>
          <p>
            Apoyamos al Servicio de Administración Tributaria (SAT) en sus programas de asesoría 
            a contribuyentes. Nuestra participación ha sido reconocida tanto para la Facultad como 
            para los estudiantes participantes.
          </p>
        </div>
        <div class="col-md-6 mv-parallax__col mv-parallax__col--right">
          <h3>Atención directa y telefónica</h3>
          <div class="gold-line gold-l"></div>
          <p>
            Ofrecemos atención presencial en los cubículos 32 y 33 del segundo piso del área de Posgrado 
            de la FCA, así como atención telefónica al <strong>55-50-79-98</strong>.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Sección: Coordinador (Crew Wide) -->
<section class="about">
  <div class="cs">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="text-center mb-5">
          <span class="lbl">Coordinación</span>
          <h2>Director del Consultorio Fiscal</h2>
          <div class="gold-line"></div>
        </div>
        <div class="crew-wide-centered">
          <div class="crew-wide">
            <div class="crew-wide__circle">
              <img src="img/mtro-padilla.jpg" alt="Mtro. José Padilla"
                   onerror="this.style.display='none';this.parentElement.querySelector('.crew-wide__initial').style.display='flex'">
              <span class="crew-wide__initial">JP</span>
            </div>
            <div class="crew-wide__info">
              <span class="crew-wide__role">Coordinador</span>
              <span class="crew-wide__name">Mtro. José Padilla</span>
              <p class="small mt-2">Especialista en derecho fiscal con amplia trayectoria en la FCA UNAM.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Sección: Ligas de interés (Eco cards adaptadas) -->
<section class="eco-section">
  <div class="cs">
    <div class="eco-header text-center">
      <span class="lbl">Recursos externos</span>
      <h2>Ligas de interés</h2>
      <div class="gold-line"></div>
      <p class="text-muted">Organismos y sitios oficiales para profundizar en materia fiscal.</p>
    </div>

    <div class="eco-outer">
      <!-- Un solo track con todas las tarjetas (solo imagen) -->
      <div class="eco-track" style="display: flex; gap: 20px; overflow-x: auto; scroll-snap-type: x mandatory; padding: 10px 0 20px; -webkit-overflow-scrolling: touch;">
        
        <!-- SAT (fondo azul marino) -->
        <a href="https://www.sat.gob.mx" target="_blank" class="eco-card" style="flex: 0 0 200px; aspect-ratio: 1 / 1; scroll-snap-align: start; background-color: #002855; border-radius: 12px; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: transform 0.3s ease;">
          <img src="img/logo_sat.png" alt="SAT" style="width: 80%; height: auto; object-fit: contain; filter: brightness(0) invert(1);">
        </a>

        <!-- DOF (fondo verde oscuro) -->
        <a href="https://www.dof.gob.mx" target="_blank" class="eco-card" style="flex: 0 0 200px; aspect-ratio: 1 / 1; scroll-snap-align: start; background-color: #1a3a2a; border-radius: 12px; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: transform 0.3s ease;">
          <img src="img/dof.png" alt="DOF" style="width: 80%; height: auto; object-fit: contain; filter: brightness(0) invert(1);">
        </a>

        <!-- INEGI (fondo terracota) -->
        <a href="https://www.inegi.org.mx" target="_blank" class="eco-card" style="flex: 0 0 200px; aspect-ratio: 1 / 1; scroll-snap-align: start; background-color: #b85c1a; border-radius: 12px; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: transform 0.3s ease;">
          <img src="img/inegi.png" alt="INEGI" style="width: 80%; height: auto; object-fit: contain; filter: brightness(0) invert(1);">
        </a>

        <!-- PRODECON (fondo púrpura oscuro) -->
        <a href="https://www.prodecon.gob.mx" target="_blank" class="eco-card" style="flex: 0 0 200px; aspect-ratio: 1 / 1; scroll-snap-align: start; background-color: #4a235a; border-radius: 12px; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: transform 0.3s ease;">
          <img src="img/prodecon.png" alt="PRODECON" style="width: 80%; height: auto; object-fit: contain; filter: brightness(0) invert(1);">
        </a>

        <!-- SE (fondo azul petróleo) -->
        <a href="https://www.economia.gob.mx" target="_blank" class="eco-card" style="flex: 0 0 200px; aspect-ratio: 1 / 1; scroll-snap-align: start; background-color: #1f618d; border-radius: 12px; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: transform 0.3s ease;">
          <img src="img/economia.png" alt="Secretaría de Economía" style="width: 80%; height: auto; object-fit: contain; filter: brightness(0) invert(1);">
        </a>

        <!-- Radio UNAM (fondo dorado oscuro) -->
        <a href="https://www.radiounam.unam.mx" target="_blank" class="eco-card" style="flex: 0 0 200px; aspect-ratio: 1 / 1; scroll-snap-align: start; background-color: #b8860b; border-radius: 12px; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: transform 0.3s ease;">
          <img src="img/radio-unam.png" alt="Radio UNAM" style="width: 80%; height: auto; object-fit: contain; filter: brightness(0) invert(1);">
        </a>

      </div>
    </div>
  </div>
</section>

<?php 
  // 3. Importar el Footer (trae los logos, legal, scripts y cierra el <body>)
  include 'template/footer.php'; 
?>