<!-- ══════════════════════════════════════════════
     PASO 1: PRE-REGISTRO - CAPTURA DE DATOS
     ══════════════════════════════════════════════ -->
<div class="step-pane" id="stepPane1">
    <div class="step-header">
        <div>
            <h2 class="step-header__title">Pre-registro</h2>
        </div>
        <span class="status-badge status-approved" style="font-size: 0.8rem; padding: 8px 14px;">
            <i class="fa fa-check-circle me-1"></i> Completado
        </span>
    </div>

    <!-- Mensaje informativo institucional -->
    <div class="notification-banner alert-success" style="display: flex; gap: 15px; align-items: center;">
        <div>
            <strong style="display: block; margin-bottom: 2px;">Tus datos han sido registrados</strong>
        </div>
    </div>

    <!-- Ficha resumen del usuario -->
    <div class="detail-card" style="margin: 25px 0;">
        <div class="detail-card__title">
            <span>Datos personales</span>
            <span style="font-size: 0.75rem; color: var(--text-soft); font-family: var(--sans);">ID Solicitud: <?php echo $p['folio_preregistro']; ?></span>
        </div>
        <div class="row">
            <div class="col-12 mb-3">
                <label class="validation-label">Nombre completo:</label>
                <div class="validation-value" style="font-size: 0.95rem; color: var(--navy);"><?php echo $p['usuario_nombre']; ?></div>
            </div>
            <div class="col-12 mb-3">
                <label class="validation-label">Correo electrónico:</label>
                <div class="validation-value"><?php echo $p['usuario_correo']; ?></div>
            </div>
            <div class="col-12 mb-2">
                <label class="validation-label">Fecha de captura:</label>
                <div class="validation-value"><?php echo $p['fecha_preregistro']; ?></div>
            </div>
        </div>
    </div>

    <div style="display: flex; justify-content: flex-end; margin-top: 30px;">
        <button type="button" class="btn-navy-fill" style="padding: 12px 28px;" onclick="irAPaso(2)">
            Continuar a la selección de Tarifa <i class="fa fa-arrow-right ms-2"></i>
        </button>
    </div>
</div>
