<!-- ══════════════════════════════════════════════
     PASO 3: DOCUMENTACIÓN - CREDENCIAL UNAM
     ══════════════════════════════════════════════ -->
<div class="step-pane" id="stepPane3" style="display: none;">
    <div class="step-header">
        <div>
            <h2 class="step-header__title">Cargar Credencial UNAM</h2>
        </div>
        <div id="pane3Badge"></div>
    </div>

    <!-- AVISO PREVIO OBLIGATORIO A LA CARGA DE CREDENCIAL -->
    <div class="notification-banner alert-warning" id="credencialAvisoInicial" style="display: flex; gap: 15px; align-items: flex-start; margin-bottom: 25px;">
        <div>
            <strong style="display: block; margin-bottom: 4px; font-size: 0.95rem; color: #075985;">
                ¡Importante!
            </strong>
            Tu credencial UNAM será revisada y validada. El proceso de suscripción continuará una vez haya sido aprobada.
        </div>
    </div>

    <!-- SUB-TIMELINE DE VALIDACIÓN DE CREDENCIAL -->
    <div class="validation-stepper">
        <div class="val-step" id="valStepCred1">
            <div class="val-step-node" id="valNodeCred1">1</div>
            <span class="val-step-label">Credencial enviada</span>
        </div>
        <div class="val-step" id="valStepCred2">
            <div class="val-step-node" id="valNodeCred2">2</div>
            <span class="val-step-label">En revisión</span>
        </div>
        <div class="val-step" id="valStepCred3">
            <div class="val-step-node" id="valNodeCred3">3</div>
            <span class="val-step-label" id="valLabelCred3">Resultado</span>
        </div>
    </div>

    <!-- ESTADO 3.A: SIN ENVIAR / RE-SUBIR -->
    <div id="credStateSinEnviar">
        <h4 style="font-family: var(--serif); color: var(--navy); font-size: 1.25rem; margin-bottom: 10px;">
            Adjunta un PDF de tu Credencial UNAM Vigente
        </h4>

        <!-- Dropzone de archivo -->
        <div class="file-upload-box" onclick="document.getElementById('fileCredInput').click()">
            <i class="fa fa-cloud-upload file-upload-icon"></i>
            <div class="file-upload-text">
                <strong>Haz clic aquí</strong> o arrastra tu credencial UNAM
            </div>
            <span style="font-size: 0.75rem; color: var(--text-soft);">Formatos permitidos: PDF (Máx. x MB)</span>
            <input type="file" id="fileCredInput" style="display: none;" accept=".pdf" onchange="previewCredFile(this)">
        </div>

        <!-- Vista previa del archivo seleccionado -->
        <div id="credFilePreview" style="display: none;" class="file-preview-card">
            <div class="file-preview-info">
                <i class="fa fa-file-pdf-o"></i>
                <div>
                    <div class="file-preview-name" id="credFileName">credencial_unam_vigente.pdf</div>
                    <div class="file-preview-size" id="credFileSize">1.4 MB • Listo para enviar</div>
                </div>
            </div>
            <button type="button" class="btn-outline-custom" style="padding: 4px 10px; font-size: 0.75rem;" onclick="cancelarCredFile()">
                <i class="fa fa-trash text-danger me-1"></i> Eliminar
            </button>
        </div>

        <div style="display: flex; justify-content: space-between; margin-top: 25px;">
            <button type="button" class="btn-outline-custom" onclick="irAPaso(2)">
                <i class="fa fa-arrow-left me-1"></i> Regresar a Selección de Tarifa
            </button>
            <button type="button" class="btn-gold-fill" id="btnEnviarCred" disabled onclick="enviarCredencial()">
                Enviar Credencial a Revisión <i class="fa fa-paper-plane ms-2"></i>
            </button>
        </div>
    </div>

    <!-- ESTADO 3.B: EN REVISIÓN -->
    <div id="credStateRevision" style="display: none;">
        <div class="notification-banner alert-warning" style="padding: 25px; text-align: center;">
            <h4 style="font-family: var(--serif); color: #075985; font-size: 1.5rem; margin-bottom: 8px;">
                Credencial UNAM en Revisión
            </h4>
            <p style="font-size: 0.92rem; max-width: 600px; margin: 0 auto; color: var(--text-md);">
                Podrás continuar con el proceso una vez que se valide tu pertenencia a la Comunidad UNAM.
            </p>
        </div>
        <div class="detail-card" style="margin-top: 20px;">
            <div class="validation-row"><span class="validation-label">Archivo enviado:</span> <span class="validation-value" id="dispCredFile">credencial_unam_2026.pdf</span></div>
            <div class="validation-row"><span class="validation-label">Fecha de envío:</span> <span class="validation-value"><?php echo date('d/m/Y H:i'); ?></span></div>
            <div class="validation-row"><span class="validation-label">Estatus actual:</span> <span class="validation-value">En revisión</span></div>
        </div>
        <div style="display: flex; justify-content: flex-start; margin-top: 20px;">
            <button type="button" class="btn-outline-custom" onclick="irAPaso(2)">
                <i class="fa fa-arrow-left me-1"></i> Regresar a Selección de Tarifa
            </button>
        </div>
    </div>

    <!-- ESTADO 3.C: RECHAZADA -->
    <div id="credStateRechazada" style="display: none;">
        <div class="notification-banner alert-danger" style="padding: 25px;">
            <div style="display: flex; gap: 15px; align-items: flex-start;">
                <div>
                    <h4 style="font-family: var(--serif); color: #721c24; font-size: 1.4rem; margin-bottom: 6px;">
                        Credencial UNAM Rechazada
                    </h4>
                    <div style="background: rgba(255,255,255,0.7); border-left: 4px solid #721c24; padding: 12px 15px; font-weight: 500; color: #721c24; font-size: 0.9rem;" id="dispCredMotivoRechazo">
                        "La imagen de la credencial no permite verificar los datos ni la vigencia actual."
                    </div>
                </div>
            </div>
        </div>
        <div style="text-align: center; margin-top: 25px;">
            <button type="button" class="btn-navy-fill" onclick="reintentarCargaCredencial()">
                <i class="fa fa-upload me-2"></i> Subir nueva credencial UNAM
            </button>
        </div>
    </div>

    <!-- ESTADO 3.D: APROBADA -->
    <div id="credStateAprobada" style="display: none;">
        <div class="notification-banner alert-success" style="padding: 25px; text-align: center;">
            <h4 style="font-family: var(--serif); color: #155724; font-size: 1.6rem; margin-bottom: 8px;">
                Credencial UNAM Validada
            </h4>
            <p style="font-size: 0.95rem; max-width: 600px; margin: 0 auto; color: #155724;">
                Se validó tu pertenencia a la Comunidad UNAM. Ya puedes continuar con el proceso de pago.
            </p>
        </div>
        <div style="display: flex; justify-content: flex-end; margin-top: 25px;">
            <button type="button" class="btn-gold-fill" style="padding: 12px 28px;" onclick="irAPaso(4)">
                Continuar a Ficha de Pago <i class="fa fa-arrow-right ms-2"></i>
            </button>
        </div>
    </div>

</div>
