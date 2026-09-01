<?php
session_start();

// Verificar que el usuario haya iniciado sesión y sea suscriptor o usuario
if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['rol'], ['suscriptor', 'usuario'])) {
    header("Location: login.php");
    exit();
}

$page_title = "Mi Perfil";
$page = "perfil";
include 'template/header.php';
?>

<main class="perfil-page">
    <div class="cs">
        <div class="perfil-container">

            <!-- Encabezado de la página -->
            <div class="perfil-header reveal reveal--left">
                <span class="lbl">Mi cuenta</span>
                <h1 class="perfil-title">Perfil de suscriptor</h1>
                <div class="gold-line gold-l"></div>
                <p class="perfil-desc">Administra tus datos personales y consulta el estado de tu suscripción.</p>
            </div>

            <!-- Datos del perfil -->
            <div class="perfil-card reveal reveal--right">
                <div class="perfil-card__header">
                    <div class="perfil-avatar">
                        <i class="fas fa-user-circle" style="font-size: 4rem; color: var(--gold, #b8860b);"></i>
                    </div>
                    <div class="perfil-nombre">
                        <h2><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Suscriptor'); ?></h2>
                        <span class="badge-suscriptor">Suscriptor activo</span>
                    </div>
                </div>

                <div class="perfil-card__body">
                    <div class="perfil-campo">
                        <span class="perfil-campo__label"><i class="fas fa-envelope"></i> Correo electrónico</span>
                        <span class="perfil-campo__valor"><?php echo htmlspecialchars($_SESSION['email'] ?? 'suscriptor@fca.unam.mx'); ?></span>
                    </div>
                    <div class="perfil-campo">
                        <span class="perfil-campo__label"><i class="fas fa-phone"></i> Número de celular</span>
                        <span class="perfil-campo__valor">55 1234 5678</span>
                    </div>
                    <div class="perfil-campo">
                        <span class="perfil-campo__label"><i class="fas fa-calendar-alt"></i> Fecha de inicio</span>
                        <span class="perfil-campo__valor">01 de enero de 2026</span>
                    </div>
                    <div class="perfil-campo">
                        <span class="perfil-campo__label"><i class="fas fa-calendar-check"></i> Vigencia de suscripción</span>
                        <span class="perfil-campo__valor">31 de diciembre de 2026</span>
                    </div>
                </div>

                <div class="perfil-card__footer">
                    <p class="perfil-ayuda">
                        <i class="fas fa-info-circle"></i> ¿Necesitas actualizar tus datos? Contáctanos en 
                        <a href="mailto:publishing_ti@fca.unam.mx">publishing_ti@fca.unam.mx</a>
                    </p>
                </div>
            </div>

        </div>
    </div>
</main>

<style>
    /* Estilos específicos para perfil.php */
    .perfil-page {
        padding: 40px 0 60px 0;
        background: var(--paper);
    }

    .perfil-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .perfil-header {
        margin-bottom: 30px;
    }

    .perfil-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.2rem;
        font-weight: 300;
        color: var(--navy);
        margin: 0.5rem 0 0.2rem 0;
    }

    .perfil-desc {
        color: var(--ink-2);
        font-size: 0.95rem;
        margin-top: 8px;
    }

    .gold-line {
        background: var(--gold, var(--gold));
        height: 2px;
        width: 60px;
        margin: 8px 0 16px 0;
    }

    .gold-l {
        width: 80px;
    }

    .perfil-card {
        background: #fff;
        border-radius: 6px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.07);
        overflow: hidden;
        border: 1px solid var(--line);
        transition: transform 0.2s;
    }

    .perfil-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.10);
    }

    .perfil-card__header {
        background: var(--paper-2);
        padding: 30px 30px 20px 30px;
        display: flex;
        align-items: center;
        gap: 20px;
        border-bottom: 1px solid var(--line);
    }

    .perfil-avatar {
        flex-shrink: 0;
    }

    .perfil-nombre h2 {
        margin: 0;
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.6rem;
        font-weight: 400;
        color: var(--navy);
    }

    .badge-suscriptor {
        display: inline-block;
        background: var(--status-aprobada-text, #2C5C34);
        color: #fff;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 3px 14px;
        border-radius: 6px;
        letter-spacing: 0.04em;
        margin-top: 4px;
    }

    .perfil-card__body {
        padding: 30px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px 40px;
    }

    .perfil-campo {
        display: flex;
        flex-direction: column;
        gap: 4px;
        border-bottom: 1px solid var(--line);
        padding-bottom: 10px;
    }

    .perfil-campo__label {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #8a9aa8;
    }

    .perfil-campo__label i {
        width: 20px;
        color: var(--gold, var(--gold));
    }

    .perfil-campo__valor {
        font-size: 1.05rem;
        font-weight: 500;
        color: var(--navy);
        padding-left: 24px;
    }

    .perfil-card__footer {
        background: var(--paper);
        padding: 16px 30px;
        border-top: 1px solid var(--line);
    }

    .perfil-ayuda {
        margin: 0;
        font-size: 0.85rem;
        color: var(--ink-2);
    }

    .perfil-ayuda a {
        color: var(--gold, var(--gold));
        text-decoration: none;
        font-weight: 500;
    }

    .perfil-ayuda a:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .perfil-card__body {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .perfil-card__header {
            flex-direction: column;
            text-align: center;
            padding: 20px;
        }

        .perfil-nombre h2 {
            font-size: 1.3rem;
        }

        .perfil-title {
            font-size: 1.8rem;
        }

        .perfil-campo__valor {
            padding-left: 0;
        }
    }

    /* Animación reveal (si ya la tienes en tu CSS global, omite esto) */
    .reveal {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }
    .reveal.in {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<script>
    // Para activar la animación reveal al cargar
    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(() => {
            document.querySelectorAll('.reveal').forEach(el => {
                el.classList.add('in');
            });
        }, 150);
    });
</script>

<?php include 'template/footer.php'; ?>