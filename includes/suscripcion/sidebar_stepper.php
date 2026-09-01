<!-- SIDEBAR / TIMELINE VERTICAL -->
<aside class="stepper-sidebar" role="navigation" aria-label="Pasos de suscripción">
    <div class="stepper-sidebar__header">
        <span class="stepper-sidebar__tag">SUSCRIPCIÓN</span>
    </div>

    <ul class="stepper-menu" id="stepperMenu">
        <!-- PASO 1: Pre-registro -->
        <li class="stepper-item completed" id="stepItem1">
            <button class="stepper-link" onclick="irAPaso(1)">
                <div class="stepper-icon">✓</div>
                <div class="stepper-text">
                    <span class="stepper-title">Pre-registro</span>
                    <span class="stepper-subtitle">Datos registrados</span>
                </div>
            </button>
        </li>

        <!-- PASO 2: Elegir tipo de suscripción -->
        <li class="stepper-item" id="stepItem2">
            <button class="stepper-link" id="stepBtn2" onclick="irAPaso(2)">
                <div class="stepper-icon" id="stepIcon2">2</div>
                <div class="stepper-text">
                    <span class="stepper-title">Elegir tipo de suscripción</span>
                    <span class="stepper-subtitle" id="stepSub2">Selección de tarifa</span>
                </div>
            </button>
        </li>

        <!-- PASO 3: Credencial UNAM -->
        <li class="stepper-item locked" id="stepItem3">
            <button class="stepper-link" id="stepBtn3" onclick="irAPaso(3)">
                <div class="stepper-icon" id="stepIcon3">3</div>
                <div class="stepper-text">
                    <span class="stepper-title" id="stepTitle3">Cargar Credencial UNAM</span>
                    <span class="stepper-subtitle" id="stepSub3">Validar tarifa UNAM</span>
                </div>
            </button>
        </li>

        <!-- PASO 4: Obtener referencia / Ficha -->
        <li class="stepper-item locked" id="stepItem4">
            <button class="stepper-link" id="stepBtn4" onclick="irAPaso(4)">
                <div class="stepper-icon" id="stepIcon4">4</div>
                <div class="stepper-text">
                    <span class="stepper-title" id="stepTitle4">Generar Ficha de Pago</span>
                </div>
            </button>
        </li>

        <!-- PASO 5: Pago / Comprobante -->
        <li class="stepper-item locked" id="stepItem5">
            <button class="stepper-link" id="stepBtn5" onclick="irAPaso(5)">
                <div class="stepper-icon" id="stepIcon5">5</div>
                <div class="stepper-text">
                    <span class="stepper-title">Cargar Comprobante de Pago</span>
                </div>
            </button>
        </li>

        <!-- PASO 6: Suscripción completada -->
        <li class="stepper-item locked" id="stepItem6">
            <button class="stepper-link" id="stepBtn6" onclick="irAPaso(6)">
                <div class="stepper-icon" id="stepIcon6">6</div>
                <div class="stepper-text">
                    <span class="stepper-title">Suscripción</span>
                    <span class="stepper-subtitle">Completada</span>
                </div>
            </button>
        </li>
    </ul>
</aside>
