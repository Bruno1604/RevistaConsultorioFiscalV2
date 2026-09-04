<!-- ══════════════════════════════════════════════
     LÓGICA JAVASCRIPT Y CONTROL DE ESTADOS
     Ahora persiste en el servidor (data/proceso_estado.json)
     a través de ajax_proceso.php, en lugar de vivir solo en el navegador.
     ══════════════════════════════════════════════ -->
<script>
const estadoInicial = <?php echo json_encode($p, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;

// Estado global del flujo (reflejo local del estado real guardado en el servidor)
let state = {
    tarifa: estadoInicial.tarifa_seleccionada,
    tarifaConfirmada: !!estadoInicial.tarifa_confirmada,
    modalidadFCA: estadoInicial.modalidad_fca || '',
    pasoActual: estadoInicial.paso_actual,
    fichaGenerada: !!estadoInicial.ficha_generada,
    fichaAsignada: !!estadoInicial.ficha_generada,
    fichaReferencia: estadoInicial.folio_ficha,
    fichaConvenio: estadoInicial.numero_convenio_ficha,
    fichaFechaCreacion: estadoInicial.fecha_creacion_ficha,
    fichaFechaVencimiento: estadoInicial.fecha_vencimiento_ficha,

    // Credencial UNAM
    credSubida: !!estadoInicial.credencial_subida,
    credEstado: estadoInicial.credencial_estado, // 'sin_enviar', 'en_revision', 'aprobada', 'rechazada'
    credFileName: estadoInicial.credencial_nombre_archivo || 'credencial_unam.pdf',
    credMotivoRechazo: estadoInicial.credencial_motivo_rechazo || '',

    // Comprobante y captura manual
    compSubido: !!estadoInicial.comprobante_subido,
    compEstado: estadoInicial.comprobante_estado, // 'sin_enviar', 'en_revision', 'aprobado', 'rechazado'
    compFileName: estadoInicial.comprobante_nombre_archivo || 'comprobante_pago.pdf',
    compMotivoRechazo: estadoInicial.comprobante_motivo_rechazo || '',
    compFechaPago: estadoInicial.comprobante_fecha_pago || '',
    compImporte: estadoInicial.comprobante_importe ? `$${estadoInicial.comprobante_importe} MXN` : '',
    compClaveRastreo: estadoInicial.comprobante_clave_rastreo || '',
    compNumOperacion: estadoInicial.comprobante_num_operacion || ''
};

let pollTimer = null;

document.addEventListener('DOMContentLoaded', () => {
    aplicarSeleccionVisualTarifa(state.tarifa);
    renderUI();
    iniciarPolling();
});

// ── Comunicación con el servidor ──────────────────────────────

// Consulta periódicamente si el admin ya aprobó/rechazó algo mientras
// el alumno tiene la pantalla abierta (sin recargar la página).
function iniciarPolling() {
    if (pollTimer) return;
    pollTimer = setInterval(() => {
        if (state.credEstado === 'en_revision' || state.compEstado === 'en_revision') {
            llamarProceso('consultar_estado', {}).then(resp => { if (resp) renderUI(); });
        }
    }, 8000);
}

function sincronizarEstadoDesdeServidor(e) {
    state.tarifa = e.tarifa_seleccionada;
    state.tarifaConfirmada = !!e.tarifa_confirmada;
    state.modalidadFCA = e.modalidad_fca || '';

    state.pasoActual = e.paso_actual;
    state.fichaGenerada = !!e.ficha_generada;
    state.fichaAsignada = !!e.ficha_generada;
    state.fichaReferencia = e.folio_ficha;
    state.fichaConvenio = e.numero_convenio_ficha;
    state.fichaFechaCreacion = e.fecha_creacion_ficha;
    state.fichaFechaVencimiento = e.fecha_vencimiento_ficha;

    state.credSubida = !!e.credencial_subida;
    state.credEstado = e.credencial_estado;
    state.credFileName = e.credencial_nombre_archivo || state.credFileName;
    state.credMotivoRechazo = e.credencial_motivo_rechazo || '';

    state.compSubido = !!e.comprobante_subido;
    state.compEstado = e.comprobante_estado;
    state.compFileName = e.comprobante_nombre_archivo || state.compFileName;
    state.compMotivoRechazo = e.comprobante_motivo_rechazo || '';
    state.compFechaPago = e.comprobante_fecha_pago || '';
    state.compImporte = e.comprobante_importe ? `$${e.comprobante_importe} MXN` : '';
    state.compClaveRastreo = e.comprobante_clave_rastreo || '';
    state.compNumOperacion = e.comprobante_num_operacion || '';

    aplicarSeleccionVisualTarifa(state.tarifa);
}

// Llama a ajax_proceso.php. `datos` puede ser un objeto plano o un FormData
// (necesario cuando se envía un archivo).
function llamarProceso(accion, datos) {
    let formData;
    if (datos instanceof FormData) {
        formData = datos;
        formData.append('accion', accion);
    } else {
        formData = new FormData();
        formData.append('accion', accion);
        for (const clave in datos) {
            formData.append(clave, datos[clave]);
        }
    }

    return fetch('ajax_proceso.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(resp => {
            if (!resp || resp.ok === false) {
                alert((resp && resp.error) || 'Ocurrió un error al procesar tu solicitud.');
                return null;
            }
            if (resp.estado) sincronizarEstadoDesdeServidor(resp.estado);
            return resp;
        })
        .catch(() => {
            alert('No se pudo conectar con el servidor. Verifica tu conexión e intenta de nuevo.');
            return null;
        });
}

// ── Selección de Tarifa en Paso 2 ─────────────────────────────

function aplicarSeleccionVisualTarifa(tipo) {
    const cardGeneral = document.getElementById('tariffCardGeneral');
    const cardUNAM = document.getElementById('tariffCardUNAM');
    const cardFCA = document.getElementById('tariffCardFCA');
    const radioGeneral = document.getElementById('radioGeneral');
    const radioUNAM = document.getElementById('radioUNAM');
    const radioFCA = document.getElementById('radioFCA');
    const fcaModalidadWrap = document.getElementById('fcaModalidadWrap');

    [cardGeneral, cardUNAM, cardFCA].forEach(c => { if (c) c.classList.remove('selected'); });
    if (radioGeneral) radioGeneral.checked = (tipo === 'GENERAL');
    if (radioUNAM) radioUNAM.checked = (tipo === 'UNAM');
    if (radioFCA) radioFCA.checked = (tipo === 'FCA');

    if (tipo === 'GENERAL' && cardGeneral) cardGeneral.classList.add('selected');
    if (tipo === 'UNAM' && cardUNAM) cardUNAM.classList.add('selected');
    if (tipo === 'FCA' && cardFCA) cardFCA.classList.add('selected');

    if (fcaModalidadWrap) fcaModalidadWrap.style.display = (tipo === 'FCA') ? 'block' : 'none';
    if (tipo === 'FCA' && state.modalidadFCA) {
        const sel = document.getElementById('fcaModalidad');
        if (sel) sel.value = state.modalidadFCA;
    }
}

function seleccionarTarifa(tipo) {
    if (state.fichaGenerada) return; // Si ya se generó la ficha, está bloqueada la tarifa

    aplicarSeleccionVisualTarifa(tipo);
    // No se llama a renderUI()
    llamarProceso('seleccionar_tarifa', { tarifa: tipo });
}

function seleccionarModalidadFCA(valor) {
    state.modalidadFCA = valor;
    llamarProceso('seleccionar_modalidad_fca', { modalidad_fca: valor });
}

function confirmarPasoTarifa() {
    if (state.tarifa === 'FCA' && !state.modalidadFCA) {
        alert('Selecciona tu modalidad (SUAyED, Escolarizado o Posgrado) antes de continuar.');
        return;
    }

    llamarProceso('confirmar_tarifa', {}).then(resp => {
        if (!resp) return;
        if (state.tarifa === 'GENERAL') {
            // Al seleccionar Público General, se omite el Paso 3 (Credencial) y se salta al Paso 4
            irAPaso(4);
        } else {
            // UNAM y FCA pasan por el Paso 3 de validación (credencial UNAM o de alumno FCA)
            irAPaso(3);
        }
    });
}

function solicitarGeneracionFicha() {
    const modal = document.getElementById('modalAdvertenciaFichaGeneral');
    if (modal) modal.classList.add('show');
}

function cerrarModalAdvertenciaFicha() {
    const modal = document.getElementById('modalAdvertenciaFichaGeneral');
    if (modal) modal.classList.remove('show');
}

function confirmarGeneracionFicha() {
    if (state.fichaGenerada) return;

    cerrarModalAdvertenciaFicha();
    llamarProceso('generar_ficha', {}).then(resp => { if (resp) renderUI(); });
}

function regresarDesdePaso4() {
    if (state.tarifa === 'GENERAL') {
        if (!state.fichaGenerada) {
            irAPaso(2);
        } else {
            irAPaso(1);
        }
    } else {
        if (!state.fichaGenerada) {
            irAPaso(3);
        } else {
            irAPaso(1);
        }
    }
}

// Navegación entre pasos
function irAPaso(pasoTarget) {
    if (!esPasoPermitido(pasoTarget)) {
        return;
    }

    state.pasoActual = pasoTarget;
    renderUI();
}

function esPasoPermitido(paso) {
    if (paso === 1) return true; // Paso 1 siempre permitido

    if (paso === 2) {
        // La selección de tarifa se bloquea ÚNICAMENTE cuando la ficha de pago ha sido generada y confirmada
        if (state.fichaGenerada) return false;
        return true;
    }

    if (paso === 3) {
        // Paso 3 (Validación de credencial): se habilita para UNAM y FCA (no para GENERAL), si la ficha no ha sido generada
        if (state.tarifa === 'GENERAL') return false;
        if (state.fichaGenerada) return false;
        return true;
    }

    if (paso === 4) {
        // Paso 4 (Ficha de pago):
        // Si es Público General, accesible directo desde Paso 2.
        // Si es UNAM o FCA, accesible si la credencial fue aprobada.
        if (state.tarifa === 'GENERAL' && state.tarifaConfirmada) return true;
        if ((state.tarifa === 'UNAM' || state.tarifa === 'FCA') && state.credEstado === 'aprobada') return true;
        return false;
    }

    if (paso === 5) {
        // Paso 5 (Comprobante): accesible ÚNICAMENTE tras generar la ficha de pago
        return state.fichaGenerada === true;
    }

    if (paso === 6) {
        // Paso 6 (Suscripción completada): solo si el comprobante está Aprobado
        return state.compEstado === 'aprobado';
    }

    return false;
}

// Renderizado reactivo de la UI
function renderUI() {
    const paso = state.pasoActual;

    // 1. Mostrar/ocultar paneles (del 1 al 6)
    for (let i = 1; i <= 6; i++) {
        const pane = document.getElementById(`stepPane${i}`);
        if (pane) pane.style.display = (i === paso) ? 'block' : 'none';
    }

    // 2. Control de estado en Paso 4 (Ficha Generada vs No Generada)
    const noGen = document.getElementById('fichaStateNoGenerada');
    const gen = document.getElementById('fichaStateGenerada');
    const stepTitle4 = document.getElementById('stepTitle4');
    if (noGen && gen) {
        if (state.fichaGenerada) {
            noGen.style.display = 'none';
            gen.style.display = 'block';
        } else {
            noGen.style.display = 'block';
            gen.style.display = 'none';
        }
    }
    if (stepTitle4) {
        stepTitle4.textContent = state.fichaGenerada
            ? '4. Consultar Ficha de Pago'
            : '4. Generar Ficha de Pago';
    }

    // 3. Actualizar Sidebar Stepper
    for (let i = 1; i <= 6; i++) {
        const item = document.getElementById(`stepItem${i}`);
        if (!item) continue;

        item.classList.remove('active', 'completed', 'locked');

        // Si el paso es el 3 y es Público General, SE DESHABILITA COMPLETAMENTE
        if (i === 3 && state.tarifa === 'GENERAL') {
            item.classList.add('locked');
            const sub = document.getElementById('stepSub3');
            if (sub) sub.textContent = 'No requerido';
            continue;
        } else if (i === 3 && state.tarifa === 'UNAM') {
            const sub = document.getElementById('stepSub3');
            if (sub) sub.textContent = 'Validar tarifa UNAM';
            const tit = document.getElementById('stepTitle3');
            if (tit) tit.textContent = 'Cargar Credencial UNAM';
        } else if (i === 3 && state.tarifa === 'FCA') {
            const sub = document.getElementById('stepSub3');
            if (sub) sub.textContent = 'Validar alumno FCA';
            const tit = document.getElementById('stepTitle3');
            if (tit) tit.textContent = 'Cargar Credencial de Alumno FCA';
        }

        const estaPermitido = esPasoPermitido(i);
        const esPasado = (i < paso) || (i === 3 && state.credEstado === 'aprobada') || (i === 5 && state.compEstado === 'aprobado');

        if (!estaPermitido) {
            item.classList.add('locked');
            const icon = document.getElementById(`stepIcon${i}`);
            if (icon && i !== 1) icon.innerHTML = i;
        } else if (i === paso) {
            item.classList.add('active');
        } else if (esPasado) {
            item.classList.add('completed');
            const icon = document.getElementById(`stepIcon${i}`);
            if (icon && i !== 1) icon.innerHTML = '✓';
        }
    }

    // Adaptar textos e importes en Paso 4 y 6
    const tarifaNombre = document.getElementById('pane4TarifaNombre');
    const tarifaDesc = document.getElementById('pane4TarifaDesc');
    const montoTotal = document.getElementById('pane4MontoTotal');
    const dispImporteFicha = document.getElementById('dispImporteFicha');
    const dispConceptoFicha = document.getElementById('dispConceptoFicha');
    const dispFechaCreacionFicha = document.getElementById('dispFechaCreacionFicha');
    const dispFechaVencimientoFicha = document.getElementById('dispFechaVencimientoFicha');
    const dispNumConvenioFicha = document.getElementById('dispNumConvenioFicha');
    const dispReferenciaFicha = document.getElementById('dispReferenciaFicha');
    const dispMontoComprobar = document.getElementById('dispMontoComprobar');
    const pane6ModalidadText = document.getElementById('pane6ModalidadText');
    const modalFichaTarifa = document.getElementById('modalFichaTarifa');
    const modalFichaMonto = document.getElementById('modalFichaMonto');

    if (state.tarifa === 'GENERAL') {
        if (tarifaNombre) tarifaNombre.textContent = 'Público general';
        if (tarifaDesc) tarifaDesc.textContent = 'Tarifa completa sin requerimiento de credencial';
        if (montoTotal) montoTotal.innerHTML = '$600.00 <span style="font-size: 0.9rem; font-family: var(--sans); color: var(--text-soft); font-weight: normal;">MXN</span>';
        if (dispImporteFicha) dispImporteFicha.textContent = '$600.00 MXN';
        if (dispConceptoFicha) dispConceptoFicha.textContent = 'Suscripción Anual Revista Consultorio Fiscal - Público General';
        if (dispMontoComprobar) dispMontoComprobar.textContent = '$600.00 MXN';
        if (pane6ModalidadText) pane6ModalidadText.textContent = 'Público General ($600.00 MXN)';
               if (modalFichaTarifa) modalFichaTarifa.textContent = 'Ficha de Pago de Público General';
        if (modalFichaMonto) modalFichaMonto.textContent = '$600.00 MXN';
    } else if (state.tarifa === 'FCA') {
        const modalidadTexto = { SUAYED: 'SUAyED', ESCOLARIZADO: 'Escolarizado', POSGRADO: 'Posgrado' }[state.modalidadFCA] || '';
        if (tarifaNombre) tarifaNombre.textContent = 'Alumnos FCA';
        if (tarifaDesc) tarifaDesc.textContent = 'Gratuita por validación de alumno activo de la FCA' + (modalidadTexto ? ` (${modalidadTexto})` : '');
        if (montoTotal) montoTotal.innerHTML = '$0.00 <span style="font-size: 0.9rem; font-family: var(--sans); color: var(--text-soft); font-weight: normal;">MXN</span>';
        if (dispImporteFicha) dispImporteFicha.textContent = '$0.00 MXN';
        if (dispConceptoFicha) dispConceptoFicha.textContent = 'Suscripción Anual Revista Consultorio Fiscal - Alumnos FCA';
        if (dispMontoComprobar) dispMontoComprobar.textContent = '$0.00 MXN';
        if (pane6ModalidadText) pane6ModalidadText.textContent = 'Alumnos FCA' + (modalidadTexto ? ` - ${modalidadTexto}` : '') + ' ($0.00 MXN)';
        if (modalFichaTarifa) modalFichaTarifa.textContent = 'Ficha de Pago de Alumnos FCA';
        if (modalFichaMonto) modalFichaMonto.textContent = '$0.00 MXN';
    } else {
        if (tarifaNombre) tarifaNombre.textContent = 'Comunidad UNAM';
        if (tarifaDesc) tarifaDesc.textContent = 'Descuento del 50% por validación de credencial UNAM';
        if (montoTotal) montoTotal.innerHTML = '$300.00 <span style="font-size: 0.9rem; font-family: var(--sans); color: var(--text-soft); font-weight: normal;">MXN</span>';
        if (dispImporteFicha) dispImporteFicha.textContent = '$300.00 MXN';
        if (dispConceptoFicha) dispConceptoFicha.textContent = 'Suscripción Anual Revista Consultorio Fiscal - Comunidad UNAM';
        if (dispMontoComprobar) dispMontoComprobar.textContent = '$300.00 MXN';
        if (pane6ModalidadText) pane6ModalidadText.textContent = 'Comunidad UNAM ($300.00 MXN)';
        if (modalFichaTarifa) modalFichaTarifa.textContent = 'Ficha de Pago de Comunidad UNAM';
        if (modalFichaMonto) modalFichaMonto.textContent = '$300.00 MXN';
    }

    if (dispFechaCreacionFicha) dispFechaCreacionFicha.textContent = state.fichaFechaCreacion || '-';
    if (dispFechaVencimientoFicha) dispFechaVencimientoFicha.textContent = state.fichaFechaVencimiento || '-';
    if (dispNumConvenioFicha) dispNumConvenioFicha.textContent = state.fichaConvenio || '-';
    if (dispReferenciaFicha) dispReferenciaFicha.textContent = state.fichaReferencia || '-';

    renderSubTimelineCredencial();
    renderSubTimelineComprobante();
}

// Sub-timeline de Credencial UNAM (Paso 3)
function renderSubTimelineCredencial() {
    const valStep1 = document.getElementById('valStepCred1');
    const valStep2 = document.getElementById('valStepCred2');
    const valStep3 = document.getElementById('valStepCred3');
    const valNode1 = document.getElementById('valNodeCred1');
    const valNode2 = document.getElementById('valNodeCred2');
    const valNode3 = document.getElementById('valNodeCred3');
    const valLabel3 = document.getElementById('valLabelCred3');

    const stateSinEnviar = document.getElementById('credStateSinEnviar');
    const stateRevision = document.getElementById('credStateRevision');
    const stateRechazada = document.getElementById('credStateRechazada');
    const stateAprobada = document.getElementById('credStateAprobada');
    const pane3Badge = document.getElementById('pane3Badge');
    const avisoInicial = document.getElementById('credencialAvisoInicial');

    if (!valStep1) return;

    // Textos dinámicos: "Credencial UNAM" vs "Credencial de Alumno FCA"
    const esFCA = state.tarifa === 'FCA';
    const nombreCred = esFCA ? 'Credencial de Alumno FCA' : 'Credencial UNAM';
    const perteneceA = esFCA ? 'tu calidad de alumno activo de la FCA' : 'tu pertenencia a la Comunidad UNAM';
    const setText = (id, txt) => { const el = document.getElementById(id); if (el) el.textContent = txt; };
    setText('credTituloPrincipal', 'Cargar ' + nombreCred);
    setText('credAvisoTexto', `Tu ${nombreCred.toLowerCase()} será revisada y validada. El proceso de suscripción continuará una vez haya sido aprobada.`);
    setText('credTituloAdjuntar', `Adjunta un PDF de tu ${nombreCred} Vigente`);
    setText('credTituloDropzone', nombreCred.toLowerCase());
    setText('credTituloRevision', nombreCred + ' en Revisión');
    setText('credTextoRevision', `Podrás continuar con el proceso una vez que se valide ${perteneceA}.`);
    setText('credTituloRechazada', nombreCred + ' Rechazada');
    setText('credBotonReintentar', 'Subir nueva ' + nombreCred.toLowerCase());
    setText('credTituloAprobada', nombreCred + ' Validada');
    setText('credTextoAprobada', `Se validó ${perteneceA}. Ya puedes continuar con el proceso de pago.`);

    if (avisoInicial) avisoInicial.style.display = state.credEstado === 'sin_enviar' ? 'flex' : 'none';

    [valStep1, valStep2, valStep3].forEach(s => s.classList.remove('done', 'current', 'rejected'));
    valNode1.innerHTML = '1'; valNode2.innerHTML = '2'; valNode3.innerHTML = '3';
    valLabel3.textContent = 'Resultado';
    if (pane3Badge) pane3Badge.innerHTML = '';

    [stateSinEnviar, stateRevision, stateRechazada, stateAprobada].forEach(d => d.style.display = 'none');

    if (state.credEstado === 'sin_enviar') {
        stateSinEnviar.style.display = 'block';
    } else if (state.credEstado === 'en_revision') {
        valStep1.classList.add('done'); valNode1.innerHTML = '✓';
        valStep2.classList.add('current');
        stateRevision.style.display = 'block';
        const dispCredFile = document.getElementById('dispCredFile');
        if (dispCredFile) dispCredFile.textContent = state.credFileName;
    } else if (state.credEstado === 'rechazada') {
        valStep1.classList.add('done'); valNode1.innerHTML = '✓';
        valStep2.classList.add('done'); valNode2.innerHTML = '✓';
        valStep3.classList.add('rejected'); valNode3.innerHTML = '✕';
        valLabel3.textContent = 'Rechazada';
        stateRechazada.style.display = 'block';
        document.getElementById('dispCredMotivoRechazo').textContent = `"${state.credMotivoRechazo}"`;
    } else if (state.credEstado === 'aprobada') {
        valStep1.classList.add('done'); valNode1.innerHTML = '✓';
        valStep2.classList.add('done'); valNode2.innerHTML = '✓';
        valStep3.classList.add('done'); valNode3.innerHTML = '✓';
        valLabel3.textContent = 'Aprobada';
        stateAprobada.style.display = 'block';
        if (pane3Badge) pane3Badge.innerHTML = '<span class="status-badge status-approved">Aprobada</span>';
    }
}

// Interacción de archivos Credencial (previsualización local, aún no se envía nada)
function previewCredFile(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        document.getElementById('credFileName').textContent = file.name;
        document.getElementById('credFileSize').textContent = (file.size / (1024*1024)).toFixed(2) + ' MB • Listo para enviar';
        document.getElementById('credFilePreview').style.display = 'flex';
        document.getElementById('btnEnviarCred').disabled = false;
    }
}

function cancelarCredFile() {
    document.getElementById('fileCredInput').value = '';
    document.getElementById('credFilePreview').style.display = 'none';
    document.getElementById('btnEnviarCred').disabled = true;
}

function enviarCredencial() {
    const fileInput = document.getElementById('fileCredInput');
    if (!fileInput || !fileInput.files || !fileInput.files[0]) return;

    const fd = new FormData();
    fd.append('archivo', fileInput.files[0]);

    const btn = document.getElementById('btnEnviarCred');
    if (btn) btn.disabled = true;

    llamarProceso('enviar_credencial', fd).then(resp => {
        if (btn) btn.disabled = false;
        if (resp) renderUI();
    });
}

function reintentarCargaCredencial() {
    llamarProceso('reintentar_credencial', {}).then(resp => { if (resp) renderUI(); });
}

// Sub-timeline de Comprobante (Paso 5)
function renderSubTimelineComprobante() {
    const valStep1 = document.getElementById('valStepComp1');
    const valStep2 = document.getElementById('valStepComp2');
    const valStep3 = document.getElementById('valStepComp3');
    const valNode1 = document.getElementById('valNodeComp1');
    const valNode2 = document.getElementById('valNodeComp2');
    const valNode3 = document.getElementById('valNodeComp3');
    const valLabel3 = document.getElementById('valLabelComp3');

    const stateSinEnviar = document.getElementById('compStateSinEnviar');
    const stateRevision = document.getElementById('compStateRevision');
    const stateRechazado = document.getElementById('compStateRechazado');
    const pane5Badge = document.getElementById('pane5Badge');

    if (!valStep1) return;

    validarFormularioComprobante();

    [valStep1, valStep2, valStep3].forEach(s => s.classList.remove('done', 'current', 'rejected'));
    valNode1.innerHTML = '1'; valNode2.innerHTML = '2'; valNode3.innerHTML = '3';
    valLabel3.textContent = 'Resultado';

    [stateSinEnviar, stateRevision, stateRechazado].forEach(d => d.style.display = 'none');

    if (state.compEstado === 'sin_enviar') {
        stateSinEnviar.style.display = 'block';
        if (pane5Badge) pane5Badge.innerHTML = '<span class="status-badge status-assigned">Pendiente de pago</span>';
    } else if (state.compEstado === 'en_revision') {
        valStep1.classList.add('done'); valNode1.innerHTML = '✓';
        valStep2.classList.add('current');
        stateRevision.style.display = 'block';
        if (pane5Badge) pane5Badge.innerHTML = '<span class="status-badge status-review">En Revisión</span>';

        const dispCompFile = document.getElementById('dispCompFile');
        const dispFecha = document.getElementById('dispCompFechaPago');
        const dispImporte = document.getElementById('dispCompImporteCapturado');
        const dispClave = document.getElementById('dispCompClaveRastreo');
        const dispOp = document.getElementById('dispCompNumOperacion');
        if (dispCompFile) dispCompFile.textContent = state.compFileName;
        if (dispFecha) dispFecha.textContent = state.compFechaPago || '-';
        if (dispImporte) dispImporte.textContent = state.compImporte || '-';
        if (dispClave) dispClave.textContent = state.compClaveRastreo || '-';
        if (dispOp) dispOp.textContent = state.compNumOperacion || '-';
    } else if (state.compEstado === 'rechazado') {
        valStep1.classList.add('done'); valNode1.innerHTML = '✓';
        valStep2.classList.add('done'); valNode2.innerHTML = '✓';
        valStep3.classList.add('rejected'); valNode3.innerHTML = '✕';
        valLabel3.textContent = 'Rechazado';
        stateRechazado.style.display = 'block';
        document.getElementById('dispCompMotivoRechazo').textContent = `"${state.compMotivoRechazo}"`;
        if (pane5Badge) pane5Badge.innerHTML = '<span class="status-badge status-rejected">Rechazado</span>';
    } else if (state.compEstado === 'aprobado') {
        valStep1.classList.add('done'); valNode1.innerHTML = '✓';
        valStep2.classList.add('done'); valNode2.innerHTML = '✓';
        valStep3.classList.add('done'); valNode3.innerHTML = '✓';
        valLabel3.textContent = 'Aprobado';
        if (pane5Badge) pane5Badge.innerHTML = '<span class="status-badge status-approved">Aprobado</span>';
    }
}

// Validación de campos del comprobante y archivo
function validarFormularioComprobante() {
    const fileInput = document.getElementById('fileCompInput');
    const fecha = document.getElementById('compFechaPago')?.value.trim();
    const importe = document.getElementById('compImporte')?.value.trim();
    const clave = document.getElementById('compClaveRastreo')?.value.trim();
    const operacion = document.getElementById('compNumOperacion')?.value.trim();
    const btn = document.getElementById('btnEnviarComp');

    const tieneArchivo = (fileInput && fileInput.files && fileInput.files[0]) || state.compSubido;
    const tieneCampos = !!(fecha && importe && clave && operacion);

    if (btn) btn.disabled = false;

    const aviso = document.getElementById('compValidationNotice');
    if (aviso && tieneArchivo && tieneCampos) aviso.style.display = 'none';
}

function mostrarAvisoComprobante(mensaje) {
    const aviso = document.getElementById('compValidationNotice');
    if (!aviso) return;

    aviso.innerHTML = `${mensaje}`;
    aviso.style.display = 'block';
}

// Interacción de archivo Comprobante (previsualización local)
function previewCompFile(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        document.getElementById('compFileName').textContent = file.name;
        document.getElementById('compFileSize').textContent = (file.size / (1024*1024)).toFixed(2) + ' MB • Listo para enviar';
        document.getElementById('compFilePreview').style.display = 'flex';
        validarFormularioComprobante();
    }
}

function cancelarCompFile() {
    document.getElementById('fileCompInput').value = '';
    document.getElementById('compFilePreview').style.display = 'none';
    validarFormularioComprobante();
}

function enviarComprobante() {
    const fileInput = document.getElementById('fileCompInput');
    const fecha = document.getElementById('compFechaPago')?.value.trim();
    const importe = document.getElementById('compImporte')?.value.trim();
    const clave = document.getElementById('compClaveRastreo')?.value.trim();
    const operacion = document.getElementById('compNumOperacion')?.value.trim();

    if (!fecha || !importe || !clave || !operacion) {
        mostrarAvisoComprobante('Hay uno o más campos vacíos. Ingresa todos los datos del Comprobante de Pago para continuar.');
        validarFormularioComprobante();
        return;
    }

    if (!fileInput?.files?.[0]) {
        mostrarAvisoComprobante('Debes adjuntar el comprobante de pago en formato PDF para continuar.');
        validarFormularioComprobante();
        return;
    }

    const fd = new FormData();
    fd.append('archivo', fileInput.files[0]);
    fd.append('fecha_pago', fecha);
    fd.append('importe', importe);
    fd.append('clave_rastreo', clave);
    fd.append('num_operacion', operacion);

    const btn = document.getElementById('btnEnviarComp');
    if (btn) btn.disabled = true;

    llamarProceso('enviar_comprobante', fd).then(resp => {
        if (btn) btn.disabled = false;
        if (!resp) return;
        const aviso = document.getElementById('compValidationNotice');
        if (aviso) aviso.style.display = 'none';
        renderUI();
    });
}

function reintentarCargaComprobante() {
    llamarProceso('reintentar_comprobante', {}).then(resp => { if (resp) renderUI(); });
}

// Panel de Simulación de Estados (Demostración interactiva)
// Sigue funcionando igual que antes, pero ahora cada modo se persiste
// en el servidor, así que también se refleja en credenciales.php / revisarComprobante.php.
function simularEstado(modo) {
    llamarProceso('simular', { modo: modo }).then(resp => { if (resp) renderUI(); });
}
</script>
