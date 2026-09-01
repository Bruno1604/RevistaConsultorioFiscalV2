<!-- ══════════════════════════════════════════════
     PASO 6: SUSCRIPCIÓN COMPLETADA
     ══════════════════════════════════════════════ -->
<div class="step-pane" id="stepPane6" style="display: none;">
    <!-- Card de Éxito / Celebración -->
    <div style="background: linear-gradient(135deg, rgba(40,167,69,0.08) 0%, rgba(184,150,85,0.08) 100%); border: 2px solid #28a745; border-radius: 8px; padding: 35px 25px; text-align: center; margin-bottom: 25px;">
        <h3 style="font-family: var(--serif); color: var(--navy); font-size: 2.2rem; margin-bottom: 10px; text-align: center;">
            Te damos la bienvenida a la Revista Consultorio Fiscal
        </h3>
        <p style="font-size: 1rem; color: var(--text-md); max-width: 640px; margin: 0 auto 20px; text-align: center;">
            A partir de este momento cuentas con acceso digital completo a todas las revistas, indicadores y cuadros permanentes.
        </p>
        <span class="badge" style="background: var(--navy); color: #fff; font-size: 0.85rem; padding: 8px 18px; font-family: var(--sans);">
            Número de Suscriptor: <?php echo $p['numero_suscriptor']; ?>
        </span>
    </div>

    <div class="notification-banner alert-warning" style="margin: 0 0 20px; text-align: center;">
        <p style="margin: 0 0 12px; color: var(--text-md); font-size: 0.92rem;">
            Podrás solicitar tu factura desde la página principal siguiendo estos pasos:
        </p>
        <ol style="display: inline-block; margin: 0; padding-left: 24px; text-align: left; color: var(--text-md); font-size: 0.92rem; line-height: 1.8;">
            <li><i class="fa fa-user-circle me-1" aria-hidden="true"></i> Haz clic en <strong>"Mi cuenta"</strong> en la esquina superior derecha.</li>
            <li>Agrega un perfil fiscal en "Perfiles Fiscales".</li>
            <li>Solicita tu factura en "Solicitar Factura".</li>
        </ol>
    </div>

    <!-- Ficha Detallada de la Suscripción -->
    <div class="detail-card">
        <div class="detail-card__title">
            <span><i class="fa fa-id-card-o me-2" style="color: var(--gold);"></i> Detalle de la Suscripción</span>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="validation-label">Titular de la suscripción:</label>
                <div class="validation-value" style="font-size: 1rem; color: var(--navy);"><?php echo $p['usuario_nombre']; ?></div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="validation-label">Correo registrado:</label>
                <div class="validation-value"><?php echo $p['usuario_correo']; ?></div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="validation-label">Modalidad de tarifa:</label>
                <div class="validation-value" id="pane6ModalidadText">Comunidad UNAM ($300.00 MXN)</div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="validation-label">Vigencia anual:</label>
                <div class="validation-value" style="color: #28a745; font-weight: 600;">
                    <?php echo date('d/m/Y'); ?> — <?php echo $p['vigencia_fin']; ?>
                </div>
            </div>
        </div>

        <hr style="margin: 20px 0; border-color: rgba(184,150,85,0.15);">

        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="buscar.php" class="btn-navy-fill" style="padding: 12px 26px;">
                <i class="fa fa-search me-2"></i> Ir al Buscador de Revistas
            </a>
        </div>
    </div>

</div>
