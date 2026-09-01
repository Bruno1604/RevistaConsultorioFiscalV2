<?php 
  $page_title = "Acceso Restringido - Consultorio Fiscal";
  $page = "error"; 
  include 'template/header.php'; 
?>

<main class="error-page">
    <div class="cs">
        <div class="error-container reveal reveal--up in">
            <div class="error-icon">
                <i class="fa fa-lock"></i>
            </div>

            <span class="lbl">Contenido Protegido</span>
            <h1 class="error-title">Inicia sesión para continuar</h1>
            <div class="gold-line"></div>
            
            <p class="error-text">
                Lo sentimos, el artículo o ejemplar que intentas consultar es exclusivo para 
                <strong>miembros suscriptores</strong> con una sesión activa.
            </p>

            <div class="error-actions">
                <a href="login.php" class="btn-gold-fill">
                    <span>Ir al Login</span>
                </a>
                <a href="index.php" class="btn-ghost">
                    <span>Volver al Inicio</span>
                </a>
            </div>

            <div class="error-footer">
                <p>¿Aún no eres suscriptor? Suscríbete a nuestra revista enviando un correo a:
                    <a href="mailto:publishing_ti@fca.unam.mx" class="contact-mail">
                        <i class="fa fa-envelope"></i> publishing_ti@fca.unam.mx
                    </a>
                </p>
            </div>
        </div>
    </div>
</main>

<?php include 'template/footer.php'; ?>