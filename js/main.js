/* ══════════════════════════════════════════════════════════════
   Consultorio Fiscal · FCA UNAM
   Interacciones de la portada
   ══════════════════════════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', function () {

  /* ───── Barra: pasa de translúcida a sólida al bajar ───── */
  const nav = document.getElementById('siteNav');
  if (nav && !nav.classList.contains('cfnav--static')) {
    const onScroll = () => nav.classList.toggle('cfnav--solid', window.scrollY > 60);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* ───── Panel lateral en móvil ───── */
  const burger = document.getElementById('burger');
  const panel = document.getElementById('navPanel');
  if (burger && panel) {
    burger.addEventListener('click', () => {
      const open = !panel.classList.contains('open');
      panel.classList.toggle('open', open);
      burger.classList.toggle('open', open);
      burger.setAttribute('aria-expanded', open);
      panel.setAttribute('aria-hidden', !open);
      document.body.style.overflow = open ? 'hidden' : '';
    });
    document.addEventListener('click', (e) => {
      if (!panel.classList.contains('open')) return;
      if (panel.contains(e.target) || burger.contains(e.target)) return;
      panel.classList.remove('open');
      burger.classList.remove('open');
      burger.setAttribute('aria-expanded', 'false');
      panel.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
    });
  }

  /* ───── Lo más leído: paneles que se abren al pasar el cursor ───── */
  initFolio();

  /* ───── Carruseles: arrastre con el ratón + flechas ───── */
  document.querySelectorAll('.rail__track').forEach(initRail);

  function initRail(track) {
    let down = false, startX = 0, startLeft = 0, moved = 0;

    track.addEventListener('mousedown', (e) => {
      down = true; moved = 0;
      startX = e.pageX;
      startLeft = track.scrollLeft;
      track.classList.add('dragging');
    });
    window.addEventListener('mouseup', () => {
      if (!down) return;
      down = false;
      track.classList.remove('dragging');
    });
    track.addEventListener('mouseleave', () => {
      down = false;
      track.classList.remove('dragging');
    });
    track.addEventListener('mousemove', (e) => {
      if (!down) return;
      e.preventDefault();
      const dx = e.pageX - startX;
      moved = Math.abs(dx);
      track.scrollLeft = startLeft - dx;
    });
    // Un arrastre no debe abrir el enlace
    track.addEventListener('click', (e) => {
      if (moved > 6) { e.preventDefault(); e.stopPropagation(); }
    }, true);

    updateArrows(track);
    track.addEventListener('scroll', () => updateArrows(track), { passive: true });
    window.addEventListener('resize', () => updateArrows(track));
  }

  function step(track) {
    const card = track.querySelector(':scope > *');
    const gap = parseFloat(getComputedStyle(track).gap) || 24;
    const w = card ? card.getBoundingClientRect().width + gap : 300;
    const visible = Math.max(1, Math.floor(track.clientWidth / w));
    return w * visible;
  }

  function updateArrows(track) {
    const key = track.id.replace('rail-', '');
    const max = track.scrollWidth - track.clientWidth - 2;
    document.querySelectorAll('.rail__btn[data-rail="' + key + '"]').forEach((btn) => {
      const dir = parseInt(btn.dataset.dir, 10);
      btn.disabled = dir < 0 ? track.scrollLeft <= 2 : track.scrollLeft >= max;
    });
  }

  document.querySelectorAll('.rail__btn').forEach((btn) => {
    btn.addEventListener('click', () => {
      const track = document.getElementById('rail-' + btn.dataset.rail);
      if (!track) return;
      track.scrollBy({ left: step(track) * parseInt(btn.dataset.dir, 10), behavior: 'smooth' });
    });
  });

  /* ───── Aparición al hacer scroll ───── */
  const io = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      entry.target.classList.add('in');
      io.unobserve(entry.target);
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

  document.querySelectorAll('.rv, .reveal').forEach((el) => io.observe(el));

  document.querySelectorAll('.tool__links .rv').forEach((el, i) => {
    el.style.transitionDelay = (i * 110) + 'ms';
  });
  document.querySelectorAll('.mosaic__grid .rv').forEach((el, i) => {
    el.style.transitionDelay = (i * 90) + 'ms';
  });

  /* ───── Calculadora de ISR ───── */
  initCalc();
});


/* ══════════════════════════════════════════════════════════════
   LO MÁS LEÍDO — paneles expansibles
   ══════════════════════════════════════════════════════════════ */
function initFolio() {
  const deck = document.getElementById('folioDeck');
  if (!deck) return;

  const panels = deck.querySelectorAll('.folio__panel');
  if (!panels.length) return;

  // En móvil los paneles se muestran abiertos: no hay estado que alternar
  const compacto = () => window.matchMedia('(max-width: 900px)').matches;

  function abrir(panel) {
    if (compacto()) return;
    panels.forEach((p) => p.classList.toggle('on', p === panel));
  }

  panels.forEach((panel) => {
    panel.addEventListener('mouseenter', () => abrir(panel));
    panel.addEventListener('focusin', () => abrir(panel));
  });

  // Al salir del bloque, vuelve a abrirse el primero
  deck.addEventListener('mouseleave', () => abrir(panels[0]));
}

/* ══════════════════════════════════════════════════════════════
   CALCULADORA ISR 2026
   Tarifas vigentes publicadas por la FCA.
   Cada renglón: [límite inferior, límite superior, cuota fija, % excedente]
   ══════════════════════════════════════════════════════════════ */
const TARIFAS = {
  mensual: {
    titulo: 'Tarifa mensual · ISR 2026',
    filas: [
      [0.01,        844.59,      0.00,       1.92],
      [844.60,      7168.51,     16.22,      6.40],
      [7168.52,     12598.02,    420.95,     10.88],
      [12598.03,    14644.64,    1011.68,    16.00],
      [14644.65,    17533.63,    1339.14,    17.92],
      [17533.64,    35362.83,    1856.84,    21.36],
      [35362.84,    55736.68,    5665.16,    23.52],
      [55736.69,    106410.50,   10457.09,   30.00],
      [106410.51,   141880.66,   25659.23,   32.00],
      [141880.67,   425641.99,   37009.69,   34.00],
      [425642.00,   null,        133488.54,  35.00]
    ]
  },
  quincenal: {
    titulo: 'Tarifa quincenal · ISR 2026',
    filas: [
      [0.01,        416.70,      0.00,       1.92],
      [416.71,      3537.15,     7.95,       6.40],
      [3537.16,     6216.15,     207.75,     10.88],
      [6216.16,     7225.95,     499.20,     16.00],
      [7225.96,     8651.40,     660.75,     17.92],
      [8651.41,     17448.75,    916.20,     21.36],
      [17448.76,    27501.60,    2795.25,    23.52],
      [27501.61,    52505.25,    5159.70,    30.00],
      [52505.26,    70006.95,    12660.75,   32.00],
      [70006.96,    210020.70,   18261.30,   34.00],
      [210020.71,   null,        65866.05,   35.00]
    ]
  },
  anual: {
    titulo: 'Tarifa del ejercicio · ISR 2026',
    filas: [
      [0.01,        10135.11,    0.00,       1.92],
      [10135.12,    86022.11,    194.59,     6.40],
      [86022.12,    151176.19,   5051.37,    10.88],
      [151176.20,   175735.66,   12140.13,   16.00],
      [175735.67,   210403.69,   16069.64,   17.92],
      [210403.70,   424353.97,   22282.14,   21.36],
      [424353.98,   668840.14,   67981.92,   23.52],
      [668840.15,   1276925.98,  125485.07,  30.00],
      [1276925.99,  1702567.97,  307910.81,  32.00],
      [1702567.98,  5107703.92,  444116.23,  34.00],
      [5107703.93,  null,        1601862.46, 35.00]
    ]
  }
};

let periodo = 'mensual';
// La tabla sólo se autodesplaza después de la primera carga
let scrollBox = false;

function money(n) {
  return '$' + n.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function moneyShort(n) {
  return '$' + n.toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
}

function parseAmount(raw) {
  const n = parseFloat(String(raw).replace(/[^0-9.]/g, ''));
  return isNaN(n) ? 0 : n;
}

function initCalc() {
  const input = document.getElementById('calcInput');
  const chips = document.getElementById('calcChips');
  if (!input || !chips) return;

  chips.addEventListener('click', (e) => {
    const chip = e.target.closest('.calc__chip');
    if (!chip) return;
    chips.querySelectorAll('.calc__chip').forEach((c) => c.classList.remove('on'));
    chip.classList.add('on');
    periodo = chip.dataset.period;
    renderTable();
    compute();
  });

  input.addEventListener('input', () => {
    const n = parseAmount(input.value);
    const caretAtEnd = input.selectionStart === input.value.length;
    if (n > 0) {
      input.value = n.toLocaleString('es-MX');
      if (caretAtEnd) input.setSelectionRange(input.value.length, input.value.length);
    }
    compute();
  });

  renderTable();
  compute();
  scrollBox = true;
}

function renderTable() {
  const body = document.getElementById('calcBody');
  const title = document.getElementById('tableTitle');
  const meta = document.getElementById('tableMeta');
  if (!body) return;

  const t = TARIFAS[periodo];
  title.textContent = t.titulo;
  meta.textContent = t.filas.length + ' rangos';

  body.innerHTML = t.filas.map((f, i) => `
    <tr data-row="${i}">
      <td>${money(f[0])}</td>
      <td>${f[1] === null ? 'En adelante' : money(f[1])}</td>
      <td>${money(f[2])}</td>
      <td>${f[3].toFixed(2)}%</td>
    </tr>
  `).join('');
}

function compute() {
  const input = document.getElementById('calcInput');
  if (!input) return;

  const monto = parseAmount(input.value);
  const filas = TARIFAS[periodo].filas;

  const outRate = document.getElementById('outRate');
  const outTax = document.getElementById('outTax');
  const outFixed = document.getElementById('outFixed');
  const outEff = document.getElementById('outEff');

  document.querySelectorAll('#calcBody tr').forEach((tr) => tr.classList.remove('hit'));

  if (monto <= 0) {
    outRate.textContent = '—';
    outTax.textContent = '—';
    outFixed.textContent = '—';
    outEff.textContent = '—';
    return;
  }

  let idx = filas.findIndex((f) => monto >= f[0] && (f[1] === null || monto <= f[1]));
  if (idx === -1) idx = filas.length - 1;

  const [inf, , cuota, tasa] = filas[idx];
  const excedente = monto - inf;
  const impuesto = cuota + excedente * (tasa / 100);
  const efectiva = (impuesto / monto) * 100;

  outRate.textContent = tasa.toFixed(2) + '%';
  outTax.textContent = moneyShort(impuesto);
  outFixed.textContent = moneyShort(cuota);
  outEff.textContent = efectiva.toFixed(1) + '%';

  const row = document.querySelector('#calcBody tr[data-row="' + idx + '"]');
  if (!row) return;
  row.classList.add('hit');

  // Centrar el renglón dentro de la tabla, sin mover la página
  const box = row.closest('.calc__scroll');
  if (box && scrollBox) {
    const target = row.offsetTop - (box.clientHeight / 2) + (row.offsetHeight / 2);
    box.scrollTo({ top: Math.max(0, target), behavior: 'smooth' });
  }
}

