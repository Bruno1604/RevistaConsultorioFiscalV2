<!-- BARRA DE SIMULACIÓN Y CONTROL DE PRUEBAS (Permite probar las 25 reglas al instante) -->
<div class="demo-control-bar">
    <div class="demo-control-title">
        <i class="fa fa-sliders"></i> Panel de Simulación y Control de Estados (Pruebas del Sistema)
    </div>
    <div class="demo-btn-group">
        <button type="button" class="demo-btn" onclick="simularEstado('general')">
            <i class="fa fa-user"></i> 1. Simular Público General ($600)
        </button>
        <button type="button" class="demo-btn" onclick="simularEstado('unam_revision')">
            <i class="fa fa-clock-o"></i> 2. UNAM en Revisión
        </button>
        <button type="button" class="demo-btn" onclick="simularEstado('unam_rechazada')">
            <i class="fa fa-times-circle"></i> 3. UNAM Rechazada (con Motivo)
        </button>
        <button type="button" class="demo-btn" onclick="simularEstado('unam_aprobada')">
            <i class="fa fa-check-circle"></i> 4. UNAM Aprobada ($300)
        </button>
        <button type="button" class="demo-btn" onclick="simularEstado('comprobante_revision')">
            <i class="fa fa-file-text"></i> 5. Comprobante en Revisión
        </button>
        <button type="button" class="demo-btn" onclick="simularEstado('comprobante_rechazado')">
            <i class="fa fa-exclamation-triangle"></i> 6. Comprobante Rechazado (con Motivo)
        </button>
        <button type="button" class="demo-btn" onclick="simularEstado('completada')">
            <i class="fa fa-trophy"></i> 7. Suscripción Completada
        </button>
        <button type="button" class="demo-btn" style="background: rgba(217,83,79,0.3); border-color: #d9534f;" onclick="simularEstado('reset')">
            <i class="fa fa-refresh"></i> Reiniciar Proceso
        </button>
    </div>
</div>
