<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cargar solicitudes
if (file_exists('data/solicitudes.php')) {
    require_once 'data/solicitudes.php';
}

$sidebar_sols = isset($_SESSION['solicitudes']) ? $_SESSION['solicitudes'] : [];

$cnt_total = count($sidebar_sols);
$cnt_revision = 0;

foreach ($sidebar_sols as $s) {

    if (isset($s['estado'])) {

        if ($s['estado'] === 'Comprobante cargado') {
            $cnt_revision++;
        }
    }
}

// Página actual
$current_uri = basename($_SERVER['PHP_SELF']);
?>

<aside class="admin-sidebar"
       role="navigation"
       aria-label="Menú de administración de suscripciones">

    <!-- TÍTULO -->
    <div class="sidebar-title">
        Solicitudes
    </div>

    <ul class="sidebar-menu">

        <!-- TODAS LAS SOLICITUDES -->
        <li>
            <a href="solicitudes.php"
               class="<?php echo ($current_uri === 'solicitudes.php') ? 'active' : ''; ?>">

                <span>
                    <i class="fa-solid fa-list-check"></i>
                    Todas las solicitudes
                </span>

            </a>
        </li>

        <!-- REVISAR CREDENCIAL -->
        <li>
            <a href="credenciales.php"
               class="<?php echo ($current_uri === 'credenciales.php') ? 'active' : ''; ?>">

                <span>
                    <i class="fa-solid fa-file-zipper"></i>
                    Revisar credencial UNAM
                </span>

            </a>
        </li>

        <!-- FICHAS CON COMPROBANTE POR REVISAR -->
        <li>
            <a href="revisarComprobante.php"
               class="<?php echo ($current_uri === 'revisarComprobante.php') ? 'active' : ''; ?>">

                <span>
                    <i class="fa-solid fa-magnifying-glass-dollar"></i>
                    Solicitudes con comprobante por revisar
                </span>

                <span class="badge bg-warning text-dark">
                    <?php echo $cnt_revision; ?>
                </span>

            </a>
        </li>

    </ul>

</aside>