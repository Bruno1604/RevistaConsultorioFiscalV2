<!-- ══════════════════════════════════════════════
     PASO 2: ELEGIR TIPO DE SUSCRIPCIÓN (SELECCIÓN DE TARIFA)
     ══════════════════════════════════════════════ -->
<div class="step-pane" id="stepPane2" style="display: none;">
    <div class="step-header">
        <div>
            <h2 class="step-header__title">Elegir tipo de suscripción</h2>
        </div>
    </div>

    <div class="tariff-grid">
        <!-- Opción 1: Público General -->
        <div class="tariff-card" id="tariffCardGeneral" onclick="seleccionarTarifa('GENERAL')">
            <div>
                <div class="tariff-card__header">
                    <input type="radio" name="tarifa" id="radioGeneral" class="tariff-card__radio" value="GENERAL">
                    <h4 class="tariff-card__title">Público general</h4>
                </div>
                <span class="tariff-card__badge">Sin credencial</span>
                <div class="tariff-card__price">$600.00 <span style="font-size: 0.85rem; font-weight: normal; color: var(--text-soft);">MXN / año</span></div>
                <p class="tariff-card__desc">
                    Acceso completo anual a todas las ediciones digitales y consulta en línea de la Revista Consultorio Fiscal.
                </p>
            </div>
        </div>

        <!-- Opción 2: Comunidad UNAM -->
        <div class="tariff-card" id="tariffCardUNAM" onclick="seleccionarTarifa('UNAM')">
            <div>
                <div class="tariff-card__header">
                    <input type="radio" name="tarifa" id="radioUNAM" class="tariff-card__radio" value="UNAM">
                    <h4 class="tariff-card__title">Comunidad UNAM</h4>
                </div>
                <span class="tariff-card__badge" style="background: rgba(40,167,69,0.15); color: #155724;">50% Descuento</span>
                <div class="tariff-card__price">$300.00 <span style="font-size: 0.85rem; font-weight: normal; color: var(--text-soft);">MXN / año</span></div>
                <p class="tariff-card__desc">
                    Tarifa especial reducida para alumnos, académicos, investigadores y exalumnos de la UNAM. Requiere validar credencial vigente.
                </p>
            </div>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; margin-top: 30px;">
        <button type="button" class="btn-outline-custom" onclick="irAPaso(1)">
            <i class="fa fa-arrow-left me-1"></i> Regresar a Pre-registro
        </button>
        <button type="button" class="btn-navy-fill" style="padding: 12px 28px;" onclick="confirmarPasoTarifa()">
            Continuar <i class="fa fa-arrow-right ms-2"></i>
        </button>
    </div>
</div>
