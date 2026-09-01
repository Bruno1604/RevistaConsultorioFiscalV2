<?php
session_start();

// Simulación de base de datos con usuarios y roles
$usuarios = [
    'admin@fca.unam.mx' => [
        'password' => 'fca2026',
        'rol' => 'admin',
        'nombre' => 'Administrador'
    ],
    'suscriptor@fca.unam.mx' => [
        'password' => 'suscriptor2026',
        'rol' => 'suscriptor',
        'nombre' => 'Suscriptor de Prueba'
    ],
    'proceso@fca.unam.mx' => [
        'password' => 'proceso2026',
        'rol' => 'usuario',
        'nombre' => 'Proceso'
    ]
];

// Si ya está logueado, redirigir según su rol
if (isset($_SESSION['usuario_id'])) {
    if ($_SESSION['rol'] === 'admin') {
        header("Location: admin.php");
    } elseif ($_SESSION['rol'] === 'usuario') {
        header("Location: proceso.php");
    } else {
        header("Location: buscar.php");
    }
    exit();
}

$error_login = '';
$mensaje_registro = '';
if (isset($_GET['registro']) && $_GET['registro'] === 'exito') {
    $mensaje_registro = "Tu cuenta ha sido creada correctamente. Ahora puedes iniciar sesión.";
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (isset($usuarios[$email]) && $usuarios[$email]['password'] === $password) {
        // Credenciales correctas
        $_SESSION['usuario_id'] = 1; // valor fijo
        $_SESSION['nombre'] = $usuarios[$email]['nombre'];
        $_SESSION['rol'] = $usuarios[$email]['rol'];
        $_SESSION['correo'] = $email;

        // Redirigir según rol
        if ($_SESSION['rol'] === 'admin') {
            header("Location: admin.php");
        } elseif ($_SESSION['rol'] === 'usuario') {
            header("Location: proceso.php");
        } else {
            header("Location: buscar.php");
        }
        exit();
    } else {
        $error_login = "Credenciales incorrectas.";
    }
}

$page_title = "Acceso a Suscriptores";
$page = "suscripciones";
include 'template/header.php';
?>

<main class="login-page">
    <div class="cs">
        <div class="login-grid">
            
            <div class="login-card reveal reveal--left">
                <div class="login-card__header">
                    <span class="lbl">Acceso exclusivo</span>
                    <h2 class="login-card__title">Miembros Suscriptores</h2>
                    <div class="gold-line"></div>
                </div>

                <?php if ($mensaje_registro): ?>
                    <div style="margin: 20px 0 10px; padding: 12px 15px; color: #155724; background: rgba(40,167,69,.12); border-left: 4px solid #28a745; font-size: .85rem; border-radius: 4px; display: flex; align-items: center; gap: 8px;">
                        <i class="fa fa-check-circle" style="font-size: 1.1rem; color: #28a745;"></i>
                        <span><?php echo $mensaje_registro; ?></span>
                    </div>
                <?php endif; ?>

                <form action="" method="post" class="login-form">
                    <div class="form-group mb-4">
                        <label for="username" class="form-label">Correo Electrónico</label>
                        <input name="username" type="email" id="username" class="form-control-custom" placeholder="ejemplo@correo.com" required />
                    </div>

                    <div class="form-group mb-4">
                        <label for="password" class="form-label">Contraseña</label>
                        <input name="password" type="password" id="password" class="form-control-custom" placeholder="••••••••" required />
                    </div>

                    <div class="login-actions">
                        <?php if($error_login): ?>
                            <p style="color: #ff4d4d; font-size: 0.8rem; margin-bottom: 15px;">
                                <?php echo $error_login; ?>
                            </p>
                        <?php endif; ?>

                        <button type="submit" class="btn-gold-fill w-100">
                            <span>Iniciar Sesión</span>
                        </button>

                        <div style="margin-top: 25px; text-align: center;">
                            <span class="register-text">¿Aún no eres suscriptor?</span>
                            <a href="registro.php" class="register-link"><strong>Regístrate aquí</strong></a>
                        </div>

                        <a href="#" class="forgot-link">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                </form>
            </div>

            <div class="login-info reveal reveal--right">
                <div class="info-box mb-5">
                    <h4 class="info-box__title">¿Tienes problemas con tu cuenta?</h4>
                    <p class="info-box__text"> Contáctanos enviando un correo a:</p>
                    <a href="mailto:publishing_ti@fca.unam.mx" class="contact-mail">
                        <i class="fa fa-envelope"></i> publishing_ti@fca.unam.mx
                    </a>
                </div>
                <div class="info-box">
                    <h4 class="info-box__title">Beneficios de ser suscriptor</h4>
                    <ul class="info-list">
                        <li>Acceso completo a todas las ediciones y artículos.</li>
                        <li>Contenido exclusivo para miembros.</li>
                        <li>Actualizaciones periódicas sobre reformas fiscales.</li>
                        <li>Soporte personalizado para consultas.</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</main>

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