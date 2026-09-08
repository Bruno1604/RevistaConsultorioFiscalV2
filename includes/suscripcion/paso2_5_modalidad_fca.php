<!-- ══════════════════════════════════════════════
     PASO 2.5: SELECCIÓN DE CATEGORÍA DE LA COMUNIDAD FCA
     ══════════════════════════════════════════════ -->
<div class="step-pane" id="stepPaneFCA" style="display: none;">
    <div class="step-header">
        <div>
            <h2 class="step-header__title">Selecciona tu categoría en la Comunidad FCA</h2>
        </div>
    </div>

    <!-- Grid de Categorías FCA -->
    <div class="fca-category-grid">
        <!-- 1. Alumno escolarizado -->
        <div class="fca-category-card" id="fcaCardESCOLARIZADO" onclick="seleccionarCardModalidadFCA('ESCOLARIZADO')">
            <div class="fca-category-card__icon">
                <i class="fa fa-graduation-cap"></i>
            </div>
            <h4 class="fca-category-card__title">Alumno escolarizado</h4>
            <p class="fca-category-card__desc">
                Alumno activo matriculado en la modalidad presencial / escolarizada de la FCA.
            </p>
        </div>

        <!-- 2. Alumno SUAyED -->
        <div class="fca-category-card" id="fcaCardSUAYED" onclick="seleccionarCardModalidadFCA('SUAYED')">
            <div class="fca-category-card__icon">
                <i class="fa fa-laptop"></i>
            </div>
            <h4 class="fca-category-card__title">Alumno SUAyED</h4>
            <p class="fca-category-card__desc">
                Alumno activo inscrito en el Sistema Universidad Abierta y Educación a Distancia.
            </p>
        </div>

        <!-- 3. Alumno Posgrado -->
        <div class="fca-category-card" id="fcaCardPOSGRADO" onclick="seleccionarCardModalidadFCA('POSGRADO')">
            <div class="fca-category-card__icon">
                <i class="fa fa-book"></i>
            </div>
            <h4 class="fca-category-card__title">Alumno Posgrado</h4>
            <p class="fca-category-card__desc">
                Alumno activo en programas de posgrado de la División de Estudios de Posgrado FCA.
            </p>
        </div>

        <!-- 4. Docente -->
        <div class="fca-category-card" id="fcaCardDOCENTE" onclick="seleccionarCardModalidadFCA('DOCENTE')">
            <div class="fca-category-card__icon">
                <i class="fa fa-users"></i>
            </div>
            <h4 class="fca-category-card__title">Docente FCA</h4>
            <p class="fca-category-card__desc">
                Personal académico y docente en activo adscrito a la Facultad de Contaduría y Administración.
            </p>
        </div>
    </div>

    <!-- Navegación -->
    <div style="display: flex; justify-content: space-between; margin-top: 30px;">
        <button type="button" class="btn-outline-custom" onclick="regresarAPaso2DesdeFCA()">
            <i class="fa fa-arrow-left me-1"></i> Regresar a Selección de Tarifa
        </button>
        <button type="button" class="btn-navy-fill" id="btnContinuarFCA" style="padding: 12px 28px;" disabled onclick="confirmarModalidadFCAYContinuar()">
            Continuar a Carga de Documentación <i class="fa fa-arrow-right ms-2"></i>
        </button>
    </div>
</div>
