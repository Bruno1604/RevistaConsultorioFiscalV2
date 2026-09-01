<?php
// 1. Iniciar el manejo de sesiones para poder destruirla
session_start();

// 2. Limpiar todas las variables de sesión
$_SESSION = array();

// 3. Si se desea destruir la sesión completamente, borramos también la cookie de sesión.
// Nota: Esto es opcional pero muy recomendado por seguridad.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Finalmente, destruir la sesión.
session_destroy();

// 5. Redirigir al login o al inicio con un mensaje (opcional)
header("Location: login.php?status=logged_out");
exit();