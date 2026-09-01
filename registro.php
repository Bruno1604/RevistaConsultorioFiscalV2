<?php
session_start();

$error_registro = '';
$registro_exitoso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $password_confirm = trim($_POST['password_confirm'] ?? '');

    if ($nombre === '' || $email === '' || $password === '' || $password_confirm === '') {
        $error_registro = "Por favor, completa todos los campos obligatorios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_registro = "Ingresa un correo electrónico válido.";
    } elseif ($password !== $password_confirm) {
        $error_registro = "Las contraseñas no coinciden.";
    } else {

        /*
         * Aquí posteriormente se guardará la información
         * en la base de datos y se procesará la credencial.
         */

        header("Location: login.php?registro=exito");
        exit();
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

                    <button type="submit" class="btn-gold-fill">
                        Crear cuenta
                    </button>

                    </form>

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
    grid-template-columns: minmax(0, 700px);
    justify-content: center;
    align-items: start;
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

.login-form .btn-gold-fill {
    display: block;
    width: 100%;
    box-sizing: border-box;
    margin-top: 30px;
    padding: 10px 15px;
    font-size: .8rem;
    text-align: center;
    text-decoration: none;
    background: var(--navy);
    color: #fff;
}

.login-form .btn-gold-fill:hover {
    background: var(--gold);
    color: var(--navy);
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
   RESPONSIVE
================================ */

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

}

</style>


<script>

document.addEventListener("DOMContentLoaded", function() {

    setTimeout(() => {

        document.querySelectorAll('.reveal').forEach(el => {
            el.classList.add('in');
        });

    }, 100);

});

</script>


<?php include 'template/footer.php'; ?>