<!-- ══════════════════════════════════════════════
     PASO 5: PAGO - COMPROBANTE DE PAGO
     ══════════════════════════════════════════════ -->
<div class="step-pane" id="stepPane5" style="display: none;">
    <div class="step-header">
        <div>
            <h2 class="step-header__title">Cargar Comprobante de Pago</h2>
        </div>
    </div>

    <!-- SUB-TIMELINE DEL COMPROBANTE DE PAGO -->
    <div class="validation-stepper">
        <div class="val-step" id="valStepComp1">
            <div class="val-step-node" id="valNodeComp1">1</div>
            <span class="val-step-label">Comprobante enviado</span>
        </div>
        <div class="val-step" id="valStepComp2">
            <div class="val-step-node" id="valNodeComp2">2</div>
            <span class="val-step-label">En revisión</span>
        </div>
        <div class="val-step" id="valStepComp3">
            <div class="val-step-node" id="valNodeComp3">3</div>
            <span class="val-step-label" id="valLabelComp3">Resultado</span>
        </div>
    </div>

    <!-- ESTADO 5.A: SIN ENVIAR / RE-SUBIR -->
    <div id="compStateSinEnviar">
        <div class="detail-card" style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <strong style="color: var(--navy); display: block;">Monto a comprobar:</strong>
                    <span style="font-size: 1.4rem; font-family: var(--serif); color: var(--gold); font-weight: bold;" id="dispMontoComprobar">$300.00 MXN</span>
                </div>
                <div style="text-align: right;">
                    <span class="validation-label">Referencia:</span>
                    <div class="validation-value" id="dispFolioComprobar">FIC-2026-9921</div>
                </div>
            </div>
        </div>

        <!-- FORMULARIO DE DATOS DEL PAGO (ESTILO REGISTRO.PHP) -->
        <div class="detail-card" style="margin-bottom: 20px;">
            <h4 style="font-family: var(--serif); color: var(--navy); font-size: 1.2rem; margin-bottom: 15px; border-bottom: 1px solid rgba(184,150,85,0.15); padding-bottom: 8px;">
                Captura los datos que aparecen en tu Comprobante de Pago
            </h4>

            <div class="login-form" style="margin-top: 0;">
                <div class="registro-apellidos comprobante-fields">
                    <div class="form-group">
                        <label for="compFormaPago" class="form-label">
                            Forma de pago <span style="color: #c0392b;">*</span>
                        </label>
                        <select
                            id="compFormaPago"
                            name="compFormaPago"
                            class="form-control-custom"
                            onchange="validarFormularioComprobante()"
                            required
                        >
                            <option value="">Selecciona una opción</option>
                            <option value="TRANSFERENCIA">Transferencia</option>
                            <option value="DEPOSITO">Depósito</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="compFechaPago" class="form-label">
                            Fecha y hora del pago <span style="color: #c0392b;">*</span>
                        </label>
                        <input
                            type="datetime-local"
                            id="compFechaPago"
                            name="compFechaPago"
                            class="form-control-custom"
                            oninput="validarFormularioComprobante()"
                            onchange="validarFormularioComprobante()"
                            required
                        />
                    </div>

                    <div class="form-group">
                        <label for="compImporte" class="form-label">
                            Importe ($ MXN) <span style="color: #c0392b;">*</span>
                        </label>
                        <input
                            type="number"
                            step="0.01"
                            id="compImporte"
                            name="compImporte"
                            class="form-control-custom"
                            placeholder="Ej. 600.00"
                            oninput="validarFormularioComprobante()"
                            required
                        />
                    </div>
                </div>

                <div class="registro-apellidos comprobante-fields comprobante-fields--reference" style="margin-top: 10px;">
                    <div class="form-group">
                        <label for="compReferencia" class="form-label">
                            Referencia <span style="color: #c0392b;">*</span>
                        </label>
                        <input
                            type="text"
                            id="compReferencia"
                            name="compReferencia"
                            class="form-control-custom"
                            placeholder="Ingresa la referencia de tu comprobante"
                            oninput="validarFormularioComprobante()"
                            required
                        />
                    </div>
                </div>
            </div>
        </div>

        <h4 style="font-family: var(--serif); color: var(--navy); font-size: 1.25rem; margin-bottom: 10px;">
            Adjunta un PDF de tu Comprobante de Pago
        </h4>

        <!-- Dropzone de comprobante -->
        <div class="file-upload-box" onclick="document.getElementById('fileCompInput').click()">
            <i class="fa fa-upload file-upload-icon"></i>
            <div class="file-upload-text">
                <strong>Haz clic aquí</strong> o arrastra tu comprobante de pago
            </div>
            <span style="font-size: 0.75rem; color: var(--text-soft);">Formatos permitidos: PDF (Máx. 5 MB)</span>
            <input type="file" id="fileCompInput" style="display: none;" accept=".pdf" onchange="previewCompFile(this)">
        </div>

        <!-- Vista previa del comprobante seleccionado -->
        <div id="compFilePreview" style="display: none;" class="file-preview-card">
            <div class="file-preview-info">
                <i class="fa fa-file-text-o"></i>
                <div>
                    <div class="file-preview-name" id="compFileName">comprobante_pago_bbva.pdf</div>
                    <div class="file-preview-size" id="compFileSize">920 KB • Listo para enviar</div>
                </div>
            </div>
            <button type="button" class="btn-outline-custom" style="padding: 4px 10px; font-size: 0.75rem;" onclick="cancelarCompFile()">
                <i class="fa fa-trash text-danger me-1"></i> Eliminar
            </button>
        </div>

        <div style="display: flex; justify-content: space-between; margin-top: 25px;">
            <button type="button" class="btn-outline-custom" onclick="irAPaso(4)">
                <i class="fa fa-arrow-left me-1"></i> Regresar a Ficha de Pago
            </button>
            <button type="button" class="btn-gold-fill" id="btnEnviarComp" onclick="enviarComprobante()">
                Enviar Comprobante a Revisión <i class="fa fa-paper-plane ms-2"></i>
            </button>
        </div>
        <div id="compValidationNotice" class="notification-banner alert-danger" style="display: none; margin-top: 15px;" role="alert"></div>
    </div>

    <!-- ESTADO 5.B: EN REVISIÓN -->
    <div id="compStateRevision" style="display: none;">
        <div class="notification-banner alert-warning" style="padding: 20px 24px; text-align: left;">
            <h4 style="font-family: var(--serif); color: #075985; font-size: 1.4rem; margin-bottom: 6px;">
                Comprobante de Pago En Revisión
            </h4>
            <p style="font-size: 0.92rem; margin: 0; color: var(--text-md);">
                Tu Comprobante de Pago fue enviado correctamente y está siendo revisado. Te notificaremos cuando exista una resolución.
            </p>
        </div>
        <div class="detail-card" style="margin-top: 20px;">
            <div class="validation-row"><span class="validation-label">Comprobante enviado:</span> <span class="validation-value" id="dispCompFile">comprobante_pago_2026.pdf</span></div>
            <div class="validation-row"><span class="validation-label">Fecha de recepción:</span> <span class="validation-value"><?php echo date('d/m/Y H:i'); ?></span></div>
            <div class="validation-row"><span class="validation-label">Forma de pago:</span> <span class="validation-value" id="dispCompFormaPago">-</span></div>
            <div class="validation-row"><span class="validation-label">Fecha y hora del pago:</span> <span class="validation-value" id="dispCompFechaPago">-</span></div>
            <div class="validation-row"><span class="validation-label">Importe capturado:</span> <span class="validation-value" id="dispCompImporteCapturado">-</span></div>
            <div class="validation-row"><span class="validation-label">Referencia:</span> <span class="validation-value" id="dispCompReferencia">-</span></div>
            <div class="validation-row"><span class="validation-label">Estado de pago:</span> <span class="validation-value">Validación en proceso</span></div>
        </div>
        <div style="display: flex; justify-content: flex-start; margin-top: 20px;">
            <button type="button" class="btn-outline-custom" onclick="irAPaso(4)">
                <i class="fa fa-arrow-left me-1"></i> Volver a consultar Ficha de Pago
            </button>
        </div>
    </div>

    <!-- ESTADO 5.C: RECHAZADO -->
    <div id="compStateRechazado" style="display: none;">
        <div class="notification-banner alert-danger" style="padding: 25px;">
            <div style="display: flex; gap: 15px; align-items: flex-start;">
                <div>
                    <h4 style="font-family: var(--serif); color: #721c24; font-size: 1.4rem; margin-bottom: 6px;">
                        Comprobante de Pago Rechazado
                    </h4>
                    <div style="background: rgba(255,255,255,0.7); border-left: 4px solid #721c24; padding: 12px 15px; font-weight: 500; color: #721c24; font-size: 0.9rem;" id="dispCompMotivoRechazo">
                        "El comprobante no corresponde al importe indicado en la ficha de pago ni muestra el número de convenio."
                    </div>
                </div>
            </div>
        </div>
        <div style="text-align: center; margin-top: 25px;">
            <button type="button" class="btn-navy-fill" onclick="reintentarCargaComprobante()">
                <i class="fa fa-upload me-2"></i> Subir nuevo comprobante corregido
            </button>
        </div>
    </div>

</div>

