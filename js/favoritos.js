/* ══════════════════════════════════════════════════════════════
   Consultorio Fiscal · FCA UNAM
   Módulo de Favoritos — botón de estrella (agregar/quitar)
   Se usa en contenido.php, previewArticulo.php y articulo.php
   ══════════════════════════════════════════════════════════════ */

function favActualizarBoton(boton, activo) {
  boton.classList.toggle('is-activo', activo);
  boton.setAttribute('aria-pressed', activo ? 'true' : 'false');

  const icono = boton.querySelector('i');
  if (icono) {
    icono.classList.toggle('fa-solid', activo);
    icono.classList.toggle('fa-regular', !activo);
  }

  const texto = boton.querySelector('.fav-btn__label');
  if (texto) {
    texto.textContent = activo
      ? (boton.dataset.labelOn || 'En tus favoritos')
      : (boton.dataset.labelOff || 'Agregar a favoritos');
  }
}

function favInitBoton(boton) {
  boton.addEventListener('click', function () {
    if (boton.disabled) return;
    boton.disabled = true;

    const payload = new URLSearchParams();
    Object.keys(boton.dataset).forEach(function (clave) {
      // Los data-* propios del componente (labelOn/labelOff) no son campos del favorito
      if (clave === 'labelOn' || clave === 'labelOff') return;
      payload.set(clave, boton.dataset[clave]);
    });

    fetch('favoritos_toggle.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: payload.toString()
    })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        if (!res.ok) {
          alert(res.error || 'No se pudo actualizar tus favoritos.');
          return;
        }
        favActualizarBoton(boton, res.activo);
      })
      .catch(function () {
        alert('No se pudo conectar para actualizar tus favoritos.');
      })
      .finally(function () {
        boton.disabled = false;
      });
  });
}

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.fav-btn[data-tipo]').forEach(favInitBoton);
});
