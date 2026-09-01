<section class="footer-a py-4"> <div class="footer-a__in container"> <div class="footer-a__stats">
      <span class="footer-a__stat">Número de visitas: <strong id="contador" class="text-navy">0</strong></span>
      <span class="footer-a__stat">Desde: 13/03/2026</span>
    </div>

    <div class="footer-a__social">
      <span class="footer-a__social-label">Síguenos en</span>
      <div class="footer-a__social-icons">
        <a href="https://www.facebook.com/FCAUNAMOFICIAL" class="footer-a__social-icon" aria-label="Facebook FCA UNAM" target="_blank" rel="noopener">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
        </a>
      </div>
    </div>

    <div class="footer-a__unam">
      <img src="img/unam_gran.png" alt="UNAM" class="footer-a__unam-img"
           onerror="this.outerHTML='<span style=\'font-family:var(--serif);font-size:1.6rem;font-weight:600;color:var(--navy)\'>UNAM</span>'">
    </div>
    
  </div>
</section>

<footer class="footer-b" role="contentinfo">
  <div class="footer-b__in">
    <div class="footer-b__left">
      <p>Hecho en México<br>D.R. &copy; <?php echo date('Y'); ?></p>
    </div>
    <div class="footer-b__right">
      <p>Esta página puede ser reproducida con fines no lucrativos, siempre y cuando no se mutile,
      se cite la fuente completa y su dirección electrónica. De otra forma requiere permiso previo
      por escrito de la institución.
      <br>
      <a href="https://www.fca.unam.mx/docs/aviso_privacidad.pdf" target="_blank">AVISO DE PRIVACIDAD</a>.
      Sitio web administrado por el Centro de Informática de la Facultad de Contaduría y Administración (CIFCA).
      <br class="d-md-none"> <a href="https://www.fca.unam.mx/docs/permanentes/seguridad.pdf" target="_blank">Seguridad</a><span class="footer-b__sep"> | </span>
      <a href="https://www.fca.unam.mx/docs/permanentes/aws.pdf" target="_blank">Jurídico</a><span class="footer-b__sep"> | </span>
      <a href="https://www.fca.unam.mx/docs/permanentes/aviso_simplificado.pdf" target="_blank">Privacidad simplificado</a></p>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo cf_asset('js/main.js'); ?>"></script>

<script>  
// ===== Lógica del Contador de Visitas =====
(function() {
    const el = document.getElementById('contador');
    if (el) {
        fetch('contador.php?t=' + Date.now())
          .then(r => r.ok ? r.text() : '---')
          .then(n => { el.textContent = n; })
          .catch(() => { el.textContent = '---'; });
    }
})();
</script>

</body>
</html>