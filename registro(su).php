<?php
session_start();

$error_registro = '';
$registro_exitoso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $password_confirm = trim($_POST['password_confirm'] ?? '');
    $tipo = $_POST['tipo_suscriptor'] ?? '';

    if ($nombre === '' || $email === '' || $password === '' || $password_confirm === '' || $tipo === '') {
        $error_registro = "Por favor, completa todos los campos obligatorios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_registro = "Ingresa un correo electrónico válido.";
    } elseif ($password !== $password_confirm) {
        $error_registro = "Las contraseñas no coinciden.";
    } elseif ($tipo === 'unam' && !isset($_FILES['credencial'])) {
        $error_registro = "Debes subir tu credencial de la Comunidad UNAM.";
    } else {

        /*
         * Aquí posteriormente se guardará la información
         * en la base de datos y se procesará la credencial.
         */

        $registro_exitoso = true;
    }
}

$page_title = "Registro de Suscriptores";
$page = "suscripciones";

include 'template/header.php';
?>

<main class="login-page registro-page">

    <div class="cs">

        <div class="registro-grid">

            <!-- FORMULARIO -->
            <div class="login-card reveal reveal--left">

                <div class="login-card__header">
                    <span class="lbl">Nuevo suscriptor</span>

                    <h2 class="login-card__title">
                        Crear una cuenta
                    </h2>

                    <div class="gold-line"></div>

                </div>

                <?php if ($error_registro): ?>

                    <div class="registro-error">
                        <?php echo $error_registro; ?>
                    </div>

                <?php endif; ?>

                <?php if ($registro_exitoso): ?>

                    <div class="registro-success">
                        <strong>Registro recibido correctamente.</strong>
                        <br>
                        Revisaremos la información proporcionada y, en caso de ser
                        necesario, validaremos tu credencial antes de continuar con
                        el proceso de pago.
                    </div>

                <?php endif; ?>

                <form action="" method="post" enctype="multipart/form-data" class="login-form">

                    <!-- NOMBRE -->
                    <div class="form-group">
                        <label for="nombre" class="form-label">
                            Nombre
                        </label>

                        <input
                            type="text"
                            name="nombre"
                            id="nombre"
                            class="form-control-custom"
                            placeholder="Nombre"
                            required
                        />
                    </div>

                    <!-- APELLIDOS -->
                    <div class="registro-apellidos">

                        <div class="form-group">
                            <label for="apellido_paterno" class="form-label">
                                Apellido paterno
                            </label>

                            <input
                                type="text"
                                name="apellido_paterno"
                                id="apellido_paterno"
                                class="form-control-custom"
                                placeholder="Apellido paterno"
                                required
                            />
                        </div>

                        <div class="form-group">
                            <label for="apellido_materno" class="form-label">
                                Apellido materno
                            </label>

                            <input
                                type="text"
                                name="apellido_materno"
                                id="apellido_materno"
                                class="form-control-custom"
                                placeholder="Apellido materno"
                            />
                        </div>

                    </div>

                    <!-- CORREO -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            Correo electrónico
                        </label>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control-custom"
                            placeholder="ejemplo@correo.com"
                            required
                        />
                    </div>

                    <!-- TELÉFONO -->
                    <div class="form-group">
                        <label for="telefono" class="form-label">
                            Teléfono
                        </label>

                        <input
                            type="tel"
                            name="telefono"
                            id="telefono"
                            class="form-control-custom"
                            placeholder="10 dígitos"
                            maxlength="10"
                            pattern="[0-9]{10}"
                            required
                        />
                    </div>

                    <!-- CONTRASEÑA -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            Contraseña
                        </label>

                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control-custom"
                            placeholder="••••••••"
                            required
                        />
                    </div>

                    <!-- CONFIRMAR CONTRASEÑA -->
                    <div class="form-group">
                        <label for="password_confirm" class="form-label">
                            Confirmar contraseña
                        </label>

                        <input
                            type="password"
                            name="password_confirm"
                            id="password_confirm"
                            class="form-control-custom"
                            placeholder="••••••••"
                            required
                        />
                    </div>

                    <!-- TIPO DE SUSCRIPTOR -->
                    <div class="form-group subscription-section">

                        <label for="tipo_suscriptor" class="form-label">
                            Selecciona el tipo de suscripción
                        </label>

                        <select
                            id="tipo_suscriptor"
                            name="tipo_suscriptor"
                            class="form-control-custom"
                            onchange="mostrarCredencial()"
                            required
                        >
                            <option value="" selected disabled>
                                Selecciona una opción
                            </option>

                            <option value="unam">
                                Comunidad UNAM — $300
                            </option>

                            <option value="publico">
                                Público General — $600
                            </option>
                        </select>

                    </div>


                    <!-- CREDENCIAL UNAM -->
                    <div id="credencial-section" class="credential-box">

                        <div class="credential-header">
                            <span class="credential-title">
                                Credencial de la UNAM
                            </span>
                        </div>

                        <p class="credential-description">
                            Para acceder al precio de <strong>$600</strong> de Comunidad UNAM,
                            sube una credencial vigente que compruebe tu pertenencia a la UNAM.
                        </p>

                        <label for="credencial" class="upload-area">

                            <span class="upload-icon">↑</span>

                            <span class="upload-title">
                                Subir credencial UNAM en formato
                            </span>

                            <span class="upload-text">
                                PDF
                            </span>

                            <input
                                type="file"
                                id="credencial"
                                name="credencial"
                                accept=".pdf"
                            >

                        </label>

                        <div class="validation-notice">
                            <strong>Importante:</strong>
                            La credencial estará sujeta a validación.
                            Una vez comprobada su veracidad, se enviará la ficha de pago.
                        </div>

                    </div>

                    </form>

                    </div>

            <!-- INFORMACIÓN -->
            <div class="login-info reveal reveal--right">

                <div class="info-box">

                    <h4 class="info-box__title">
                        Elige tu tipo de suscripción
                    </h4>

                    <ul class="info-list">

                        <li>
                            <strong>Comunidad UNAM — $300</strong>
                            <br>
                            Alumnos, exalumnos, académicos y trabajadores con credencial vigente.
                        </li>

                        <li>
                            <strong>Público General — $600</strong>
                            <br>
                            Para personas externas a la Universidad.
                        </li>

                    </ul>

                </div>


                <div class="info-box">

                    <h4 class="info-box__title">
                        ¿Cómo funciona el proceso?
                    </h4>

                    <ul class="info-list">

                        <li>
                            Registra tus datos.
                        </li>

                        <li>
                            Selecciona tu tipo de suscripción.
                        </li>

                        <li>
                            Si perteneces a la Comunidad UNAM, proporciona tu
                            credencial para validación.
                        </li>

                        <li>
                            Una vez validada la información, recibirás tu ficha
                            de pago.
                        </li>

                    </ul>

                </div>

            </div>

        </div>

    </div>

</main>


<style>

/* ================================
   REGISTRO
================================ */

.registro-page {
    padding-top: 85px;
    padding-bottom: 45px;
}

.registro-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.15fr) minmax(300px, .85fr);
    gap: 45px;
    align-items: start;
}

.login-info {
    grid-column: 2;
    position: relative;
    left: 80px;
    display: flex;
    flex-direction: column;
    gap: 70px;
}

.info-box {
    width: 100%;
    box-sizing: border-box;
}

.info-box__title {
    min-height: 2.6em;
    margin: 0 0 6px;
    display: flex;
    align-items: flex-start;
}

.info-list {
    line-height: 2;
    margin-top: 0;
    padding-left: 22px;
}

.info-list li + li {
    margin-top: 18px;
}


/* INTRO */

.registro-intro {
    margin: 15px 0 0;
    color: rgba(255,255,255,.72);
    font-size: .9rem;
    line-height: 1.6;
}


/* FORMULARIOS */

.login-form {
    margin-top: 25px;
}

.login-form .form-group {
    margin-bottom: 14px;
}

.registro-apellidos {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    color: #11043b;
    font-weight: 600;
    font-size: .85rem;
}

.form-control-custom {
    width: 100%;
    box-sizing: border-box;
}


/* OPCIONES DE SUSCRIPCIÓN */

.subscription-options {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.subscription-option {
    position: relative;
    display: block;
    cursor: pointer;
}

.subscription-option input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.subscription-option-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;

    padding: 17px 20px;

    border: 1px solid rgba(212,166,74,.35);
    border-radius: 6px;

    background: rgba(255,255,255,.035);

    transition: all .25s ease;
}

.subscription-option-content div {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.subscription-option-content strong {
    color: #fff;
}

.subscription-option-content span {
    color: rgba(255,255,255,.55);
    font-size: .75rem;
}

.subscription-price {
    color: #d4a64a !important;
    font-size: 1.15rem;
}

.subscription-option input:checked + .subscription-option-content {
    border-color: #d4a64a;
    background: rgba(212,166,74,.10);
    box-shadow: 0 0 0 1px rgba(212,166,74,.15);
}


/* CREDENCIAL */

.credencial-section {
    display: none;
    margin-top: 5px;
    padding: 20px;

    border: 1px solid rgba(212,166,74,.3);
    border-radius: 6px;

    background: rgba(255,255,255,.025);

    animation: fadeIn .25s ease;
}

.upload-box {
    border: 1px dashed rgba(212,166,74,.55);
    border-radius: 6px;

    padding: 20px;
    text-align: center;

    background: rgba(212,166,74,.035);
}

.upload-box i {
    color: #d4a64a;
    font-size: 1.5rem;
    margin-bottom: 8px;
}

.upload-box p {
    color: rgba(255,255,255,.65);
    font-size: .78rem;
    margin: 0 0 15px;
}

.upload-box input[type="file"] {
    width: 100%;
    color: rgba(255,255,255,.75);
    font-size: .75rem;
}


/* AVISO */

.validation-notice {
    margin-top: 15px;
    padding: 12px 14px;

    border-left: 3px solid #d4a64a;

    background: rgba(212,166,74,.06);
}

.validation-notice strong {
    color: #d4a64a;
    font-size: .8rem;
}

.validation-notice p {
    color: rgba(255,255,255,.65);
    font-size: .75rem;
    line-height: 1.5;
    margin: 5px 0 0;
}


/* MENSAJES */

.registro-error {
    margin-top: 20px;
    padding: 12px 15px;

    color: #ff6b6b;
    background: rgba(255,77,77,.08);

    border-left: 3px solid #ff4d4d;

    font-size: .8rem;
}

.registro-success {
    margin-top: 20px;
    padding: 14px 16px;

    color: #fff;
    background: rgba(40,167,69,.10);

    border-left: 3px solid #28a745;

    font-size: .8rem;
    line-height: 1.5;
}


/* LOGIN */

.login-redirect {
    margin-top: 25px;
    text-align: center;

    color: rgba(255,255,255,.65);
    font-size: .82rem;
}

.register-link {
    color: #d4a64a;
    text-decoration: none;
    margin-left: 4px;
}

.register-link:hover {
    color: #fff;
}

/* ================================
   COMBO BOX
================================ */

.subscription-section {
    margin-top: 38px;
    margin-bottom: 30px;
}

.subscription-section .form-control-custom {
    cursor: pointer;
}


/* ================================
   PROCESO DE SUSCRIPCIÓN
================================ */

.process-box {
    margin: 10px 0 35px;
    padding: 25px;

    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 5px;

    background: rgba(255,255,255,0.025);
}

.process-title {
    color: #d4a64a;
    font-size: 0.95rem;
    margin-bottom: 22px;
}

.process-steps {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px 30px;
}

.process-step {
    display: flex;
    gap: 14px;
    align-items: flex-start;
}

.process-number {
    min-width: 27px;
    height: 27px;

    display: flex;
    align-items: center;
    justify-content: center;

    border: 1px solid #d4a64a;
    border-radius: 50%;

    color: #d4a64a;
    font-size: 0.75rem;
    font-weight: 700;
}

.process-step p {
    margin: 0;

    color: rgba(255,255,255,0.62);
    font-size: 0.77rem;
    line-height: 1.55;
}

.process-step strong {
    color: #fff;
}


/* ================================
   CREDENCIAL
================================ */

.credential-box {
    display: none;

    margin: 10px 0 35px;
    padding: 25px;

    border: 1px solid rgba(212,166,74,0.45);
    border-radius: 5px;

    background: rgba(212,166,74,0.035);
}

.credential-title {
    color: #d4a64a;
    font-size: 0.95rem;
    font-weight: 700;
}

.credential-description {
    color: rgba(255,255,255,0.68);
    font-size: 0.82rem;
    line-height: 1.6;
    margin: 12px 0 20px;
}

.credential-description strong {
    color: #d4a64a;
}


/* ================================
   RESPONSIVE
================================ */

@media (max-width: 700px) {

    .process-steps {
        grid-template-columns: 1fr;
    }

}


/* ANIMACIÓN */

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-5px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}


/* RESPONSIVE */

@media (max-width: 900px) {

    .registro-grid {
        grid-template-columns: 1fr;
    }

    .login-info {
        grid-column: 1;
        left: 0;
    }

}

</style>


<script>

function mostrarCredencial() {

    const tipo = document.getElementById('tipo_suscriptor').value;
    const section = document.getElementById('credencial-section');
    const input = document.getElementById('credencial');

    if (tipo === 'unam') {

        section.style.display = 'block';
        input.required = true;

    } else {

        section.style.display = 'none';
        input.required = false;
        input.value = '';

    }
}

document.addEventListener("DOMContentLoaded", function() {

    setTimeout(() => {

        document.querySelectorAll('.reveal').forEach(el => {
            el.classList.add('in');
        });

    }, 100);

});

</script>


<?php include 'template/footer.php'; ?>