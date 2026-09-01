<!-- ══════════════════════════════════════════════
     MODAL INTERACTIVO: VISTA PREVIA FICHA PDF
     ══════════════════════════════════════════════ -->
<div class="custom-modal-overlay" id="modalPdfFicha">
    <div class="custom-modal" style="max-width: 680px;">
        <div class="custom-modal-header">
            <h3 class="custom-modal-title">
                <i class="fa fa-file-pdf-o text-danger me-2"></i> Ficha de Pago Oficial (PDF)
            </h3>
            <button type="button" class="btn-close-modal" onclick="cerrarModalPdfFicha()">&times;</button>
        </div>
        <div class="custom-modal-body" style="background: #f8f9fa;">
            <!-- Documento simulado PDF -->
            <div class="doc-preview-pdf" style="margin: 0 auto; max-width: 100%;">
                <div class="pdf-header">
                    <div>
                        <strong style="font-family: var(--serif); font-size: 1.1rem; color: var(--navy); display: block;">FACULTAD DE CONTADURÍA Y ADMINISTRACIÓN</strong>
                        <span style="font-size: 0.75rem; color: var(--text-soft);">UNIVERSIDAD NACIONAL AUTÓNOMA DE MÉXICO</span>
                    </div>
                    <span class="pdf-watermark">FICHA OFICIAL DE PAGO</span>
                </div>

                <div style="text-align: center; margin: 15px 0;">
                    <span class="lbl" style="font-size: 0.7rem;">REVISTA CONSULTORIO FISCAL</span>
                    <h4 style="font-family: var(--serif); color: var(--navy); margin: 2px 0;">Comprobante de Referencia Bancaria</h4>
                </div>

                <div style="background: var(--bg-warm); padding: 15px; border-radius: 4px; border: 1px solid rgba(184,150,85,0.2); margin-bottom: 15px;">
                    <div class="row">
                        <div class="col-6 mb-2"><span class="validation-label">Folio:</span> <strong id="pdfModalFolio">FIC-2026-9921</strong></div>
                        <div class="col-6 mb-2"><span class="validation-label">Fecha Emisión:</span> <span><?php echo date('d/m/Y'); ?></span></div>
                        <div class="col-6 mb-2"><span class="validation-label">Suscriptor:</span> <span><?php echo $p['usuario_nombre']; ?></span></div>
                        <div class="col-6 mb-2"><span class="validation-label">Límite Pago:</span> <strong class="text-danger" id="pdfModalVencimiento"><?php echo $p['fecha_vencimiento_ficha']; ?></strong></div>
                    </div>
                </div>

                <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; margin-bottom: 15px;">
                    <thead>
                        <tr style="background: var(--navy); color: #fff; text-align: left;">
                            <th style="padding: 8px;">Concepto</th>
                            <th style="padding: 8px; text-align: right;">Importe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #ddd;" id="pdfModalConcepto">Suscripción Anual - Comunidad UNAM (50% desc.)</td>
                            <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: right; font-weight: bold;" id="pdfModalMonto">$300.00 MXN</td>
                        </tr>
                    </tbody>
                </table>

                <div style="text-align: center; border: 1px dashed #999; padding: 15px; background: #fff;">
                    <div style="font-family: monospace; font-size: 1.2rem; letter-spacing: 0.2em; color: var(--navy); font-weight: bold;">
                        ||||||||||||||||||||||||||||||||||||||||
                    </div>
                    <span style="font-size: 0.75rem; color: var(--text-soft);">Código de barras para sucursal BBVA / Convenio CIE: 1840291</span>
                </div>
            </div>
        </div>
        <div class="custom-modal-footer">
            <button type="button" class="btn-outline-custom" onclick="cerrarModalPdfFicha()">Cerrar</button>
            <button type="button" class="btn-navy-fill" onclick="alert('Descargando Ficha de Pago en PDF...'); cerrarModalPdfFicha();">
                <i class="fa fa-download me-1"></i> Descargar PDF
            </button>
        </div>
    </div>
</div>
