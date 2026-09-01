<!-- ══════════════════════════════════════════════
     PASO 4: OBTENER REFERENCIA - FICHA DE PAGO
     ══════════════════════════════════════════════ -->
<div class="step-pane" id="stepPane4" style="display: none;">

    <!-- ESTADO 4.A: FICHA NO GENERADA -->
    <div id="fichaStateNoGenerada">
        <div class="step-header">
            <div>
                <h2 class="step-header__title">Generar Ficha de Pago</h2>
            </div>
        </div>

        <div class="detail-card">
            <div class="validation-row" style="margin-top: 15px;">
                <span class="validation-label">Tipo de suscripción:</span>
                <span class="validation-value" id="pane4TarifaNombre">Público General</span>
            </div>
            
            <div class="validation-row" style="margin-top: 10px; border-top: 1px solid rgba(184,150,85,0.15); padding-top: 10px;">
                <span class="validation-label" style="font-size: 1rem; font-weight: 600; color: var(--navy);">Monto a pagar:</span>
                <span class="validation-value" style="font-size: 1.4rem; font-family: var(--serif); color: var(--gold); font-weight: bold;" id="pane4MontoTotal">$600.00 MXN</span>
            </div>
        </div>

        <div class="notification-banner alert-warning" style="display: flex; gap: 15px; align-items: flex-start;">
            <div>
                Haz clic en el botón a continuación para generar tu ficha de pago. Una vez generada, no se podrá modificar el tipo de suscripción.
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; margin-top: 30px;">
            <button type="button" class="btn-outline-custom" onclick="regresarDesdePaso4()">
                <i class="fa fa-arrow-left me-1"></i> Regresar
            </button>
            <button type="button" class="btn-gold-fill" style="padding: 12px 28px;" onclick="solicitarGeneracionFicha()">
                <i class="fa fa-file-text-o me-2"></i> Generar la ficha de pago
            </button>
        </div>
    </div>

    <!-- ESTADO 4.B: FICHA GENERADA -->
    <div id="fichaStateGenerada" style="display: none;">
        <div class="step-header">
            <div>
                <h2 class="step-header__title">Consultar Ficha de Pago</h2>
            </div>
        </div>

        <div class="detail-card">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; margin-bottom: 20px;">
                <div class="validation-box">
                    <span class="validation-label" style="display: block; font-size: 0.75rem; text-transform: uppercase;">Fecha de creación</span>
                    <strong style="font-size: 0.95rem; color: var(--navy);" id="dispFechaCreacionFicha">-</strong>
                </div>
                <div class="validation-box">
                    <span class="validation-label" style="display: block; font-size: 0.75rem; text-transform: uppercase;">Fecha de vencimiento</span>
                    <strong style="font-size: 0.95rem; color: #c0392b;" id="dispFechaVencimientoFicha"><?php echo date('d/m/Y', strtotime('+30 days')); ?></strong>
                </div>
                <div class="validation-box">
                    <span class="validation-label" style="display: block; font-size: 0.75rem; text-transform: uppercase;">Número de convenio</span>
                    <strong style="font-size: 0.95rem; color: var(--navy);" id="dispNumConvenioFicha">-</strong>
                </div>
                <div class="validation-box">
                    <span class="validation-label" style="display: block; font-size: 0.75rem; text-transform: uppercase;">Referencia</span>
                    <strong style="font-size: 0.95rem; color: var(--navy);" id="dispReferenciaFicha">-</strong>
                </div>
                <div class="validation-box">
                    <span class="validation-label" style="display: block; font-size: 0.75rem; text-transform: uppercase;">Importe</span>
                    <strong style="font-size: 1.1rem; color: var(--gold);" id="dispImporteFicha">$600.00 MXN</strong>
                </div>
                <div class="validation-box">
                    <span class="validation-label" style="display: block; font-size: 0.75rem; text-transform: uppercase;">Concepto</span>
                    <strong style="font-size: 0.9rem; color: var(--navy);" id="dispConceptoFicha">Suscripción Revista Consultorio Fiscal</strong>
                </div>
            </div>

            <div style="display: flex; gap: 12px; flex-wrap: wrap; justify-content: center; margin-top: 20px;">
                <a href="docs/ficha_pago_demo.pdf" target="_blank" class="btn-navy-fill" style="padding: 10px 22px; text-decoration: none;">
                    <i class="fa fa-eye me-2"></i> Ver PDF
                </a>
                <a href="docs/ficha_pago_demo.pdf" download="Ficha_Pago_Consultorio_Fiscal.pdf" class="btn-outline-custom" style="padding: 10px 22px; text-decoration: none;">
                    <i class="fa fa-download me-2"></i> Descargar PDF
                </a>
            </div>
        </div>

        <div class="notification-banner alert-warning" style="display: flex; gap: 15px; align-items: flex-start;">
            <div style="font-size: 0.9rem; color: #075985; line-height: 1.5;">
                Puedes revisar aquí la ficha de pago. Una vez que realices tu depósito o transferencia y tengas tu comprobante de pago, cárgalo en la siguiente sección.
                <br>
                <br>
                Para dudas o aclaraciones, contáctanos en <strong><a href="mailto:publishing_ti@fca.unam.mx">publishing_ti@fca.unam.mx</a></strong>.
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; margin-top: 30px;">
            <button type="button" class="btn-outline-custom" onclick="regresarDesdePaso4()">
                <i class="fa fa-arrow-left me-1"></i> Regresar
            </button>
            <button type="button" class="btn-gold-fill" style="padding: 12px 28px;" onclick="irAPaso(5)">
                Cargar Comprobante de Pago <i class="fa fa-arrow-right ms-2"></i>
            </button>
        </div>
    </div>

</div>

<!-- MODAL DE ADVERTENCIA PARA GENERAR FICHA -->
<div class="custom-modal-overlay" id="modalAdvertenciaFichaGeneral">
    <div class="custom-modal generation-modal">

        <div class="custom-modal-header">
            <button type="button" class="btn-close-modal" onclick="cerrarModalAdvertenciaFicha()">&times;</button>
        </div>

        <div class="custom-modal-body">

            <p class="generation-modal__lead">
                Estás a punto de generar tu
                <strong id="modalFichaTarifa">Ficha de Pago de Público General</strong>
                por un monto de <strong id="modalFichaMonto">$600.00 MXN</strong>.
            </p>

            <p class="generation-modal__notice" style="color: #c0392b; text-transform: uppercase;">
                <strong>NO PODRÁS GENERAR UNA NUEVA.</strong>
            </p>

            <p class="generation-modal__question">
                ¿Estás seguro de que deseas continuar?
            </p>

        </div>

        <div class="custom-modal-footer">
            <button type="button" class="btn-outline-custom" onclick="cerrarModalAdvertenciaFicha()">
                Cancelar
            </button>

            <button type="button" class="btn-gold-fill generation-modal__confirm" onclick="confirmarGeneracionFicha()">
                <i class="fa fa-check me-1"></i> Generar ficha
            </button>
        </div>

    </div>
</div>

