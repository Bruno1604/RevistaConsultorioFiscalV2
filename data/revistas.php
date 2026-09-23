<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
 * Antes, esta lista de revistas solo existía dentro de admin_revistas.php
 * ($_SESSION['revistas']), y ninguna otra página la leía -- contenido.php,
 * articulo.php, etc. mostraban datos fijos de ejemplo, sin conexión real
 * con lo que el admin sube. Este archivo es la fuente compartida: tanto
 * el admin como las páginas de suscriptores (como el visor de PDF) usan
 * las mismas funciones de aquí.
 */

if (!isset($_SESSION['revistas']) || empty($_SESSION['revistas'])) {
    $_SESSION['revistas'] = [
        [
            'id' => 1,
            'titulo' => 'Deducciones personales en la declaración anual',
            'numero' => '878',
            'anio' => '2026',
            'fecha' => 'Segunda de marzo 2026',
            'pdf' => 'revista878.pdf',
            'pdf_ruta' => null, // null = todavía no se subió el archivo real
            'imagenes' => ['pag1.jpg', 'pag2.jpg'],
            'articulos' => [
                ['titulo' => 'Artículo 1', 'inicio' => 1, 'fin' => 5],
                ['titulo' => 'Artículo 2', 'inicio' => 6, 'fin' => 10]
            ]
        ],
        [
            'id' => 2,
            'titulo' => 'Declaración anual de personas morales del régimen general',
            'numero' => '877',
            'anio' => '2026',
            'fecha' => 'Primera de marzo 2026',
            'pdf' => 'revista877.pdf',
            'pdf_ruta' => null,
            'imagenes' => ['pag1.jpg'],
            'articulos' => [
                ['titulo' => 'Artículo A', 'inicio' => 1, 'fin' => 8]
            ]
        ],
        [
            'id' => 3,
            'titulo' => 'Participación de utilidades para personas físicas',
            'numero' => '884',
            'anio' => '2026',
            'fecha' => 'Segunda de junio 2026',
            'pdf' => 'revista884.pdf',
            'pdf_ruta' => null,
            'imagenes' => [],
            'articulos' => [
                ['titulo' => 'Artículo X', 'inicio' => 2, 'fin' => 7]
            ]
        ],
        [
            'id' => 4,
            'titulo' => 'Intereses por pago indebido',
            'numero' => '886',
            'anio' => '2026',
            'fecha' => 'Segunda de julio 2026',
            'pdf' => 'revista886.pdf',
            'pdf_ruta' => null,
            'imagenes' => ['pag1.jpg', 'pag2.jpg', 'pag3.jpg'],
            'articulos' => [
                ['titulo' => 'Artículo 1', 'inicio' => 1, 'fin' => 4],
                ['titulo' => 'Artículo 2', 'inicio' => 5, 'fin' => 9]
            ]
        ],
        [
            'id' => 5,
            'titulo' => 'Revisiones de las autoridades fiscales y de seguridad social',
            'numero' => '885',
            'anio' => '2026',
            'fecha' => 'Primera de julio 2026',
            'pdf' => 'revista885.pdf',
            'pdf_ruta' => null,
            'imagenes' => ['pag1.jpg'],
            'articulos' => [
                ['titulo' => 'Artículo Único', 'inicio' => 1, 'fin' => 12]
            ]
        ],
        [
            'id' => 6,
            'titulo' => 'Modalidades de dividendos en ISR',
            'numero' => '887',
            'anio' => '2026',
            'fecha' => 'Primera de agosto 2026',
            'pdf' => 'revista887.pdf',
            'pdf_ruta' => null,
            'imagenes' => [],
            'articulos' => [
                ['titulo' => 'Artículo A', 'inicio' => 1, 'fin' => 6],
                ['titulo' => 'Artículo B', 'inicio' => 7, 'fin' => 10]
            ]
        ],
        [
            'id' => 7,
            'titulo' => 'Reforma a la LFT',
            'numero' => '882',
            'anio' => '2026',
            'fecha' => 'Segunda de mayo 2026',
            'pdf' => 'revista882.pdf',
            'pdf_ruta' => null,
            'imagenes' => ['pag1.jpg', 'pag2.jpg'],
            'articulos' => [
                ['titulo' => 'Artículo 1', 'inicio' => 1, 'fin' => 3]
            ]
        ],
        [
            'id' => 8,
            'titulo' => 'PTU: preguntas y respuestas',
            'numero' => '881',
            'anio' => '2026',
            'fecha' => 'Primera de mayo 2026',
            'pdf' => 'revista881.pdf',
            'pdf_ruta' => null,
            'imagenes' => ['pag1.jpg'],
            'articulos' => [
                ['titulo' => 'Artículo Único', 'inicio' => 1, 'fin' => 15]
            ]
        ],
        [
            'id' => 9,
            'titulo' => 'El nuevo régimen de confianza',
            'numero' => '888',
            'anio' => '2026',
            'fecha' => 'Segunda de agosto 2026',
            'pdf' => 'revista888.pdf',
            'pdf_ruta' => null,
            'imagenes' => ['pag1.jpg', 'pag2.jpg', 'pag3.jpg'],
            'articulos' => [
                ['titulo' => 'Introducción', 'inicio' => 1, 'fin' => 2],
                ['titulo' => 'Desarrollo', 'inicio' => 3, 'fin' => 10]
            ]
        ],
        // Edición 879 -- la que ya se mostraba de ejemplo en contenido.php.
        // Aquí sí trae 'pdf_ruta' apuntando a un PDF real de prueba, para
        // poder probar el visor de punta a punta.
        [
            'id' => 10,
            'titulo' => 'Declaración anual de personas físicas',
            'numero' => '879',
            'anio' => '2026',
            'fecha' => 'Primera quincena de Abril 2026',
            'pdf' => 'revista879.pdf',
            'pdf_ruta' => 'uploads/revistas/revista879.pdf',
            'imagenes' => [
                'uploads/revistas/paginas/879/pagina-1.jpg',
                'uploads/revistas/paginas/879/pagina-2.jpg',
                'uploads/revistas/paginas/879/pagina-3.jpg'
            ],
            'articulos' => [
                ['titulo' => 'Declaración anual de personas físicas', 'inicio' => 1, 'fin' => 40],
                ['titulo' => 'Paso a paso para la declaración de personas físicas', 'inicio' => 41, 'fin' => 84]
            ]
        ]
    ];
}

function get_revistas() {
    return $_SESSION['revistas'];
}

/**
 * Convierte cada página de un PDF en una imagen JPG, y las guarda en
 * uploads/revistas/paginas/{numero}/pagina-N.jpg
 *
 * Regresa un arreglo con las rutas relativas de las imágenes generadas
 * (para guardarlas en el campo 'imagenes' de la revista), o false si
 * Imagick/Ghostscript no están disponibles en el servidor o algo falló.
 *
 * Necesita que el servidor tenga instalados: la extensión Imagick de PHP
 * y Ghostscript. En Linux (Ubuntu/Mint) se instalan así:
 *   sudo apt install ghostscript php-imagick
 * (y reiniciar `php -S` después de instalarlos)
 */
function convertir_pdf_a_imagenes($rutaPdfAbsoluta, $numero) {
    if (!extension_loaded('imagick')) {
        return false;
    }

    $carpetaDestino = __DIR__ . '/../uploads/revistas/paginas/' . preg_replace('/[^a-zA-Z0-9]/', '', $numero);
    if (!is_dir($carpetaDestino)) {
        mkdir($carpetaDestino, 0755, true);
    }

    try {
        $imagick = new Imagick();
        $imagick->setResolution(150, 150); // suficiente para leerse bien, sin pesar demasiado
        $imagick->readImage($rutaPdfAbsoluta);

        $rutasGeneradas = [];
        foreach ($imagick as $numPagina => $pagina) {
            $pagina->setImageFormat('jpg');
            $pagina->setImageCompressionQuality(85);

            $nombreArchivo = 'pagina-' . ($numPagina + 1) . '.jpg';
            $rutaCompleta = $carpetaDestino . '/' . $nombreArchivo;
            $pagina->writeImage($rutaCompleta);

            $rutasGeneradas[] = 'uploads/revistas/paginas/' . preg_replace('/[^a-zA-Z0-9]/', '', $numero) . '/' . $nombreArchivo;
        }

        $imagick->clear();
        return $rutasGeneradas;
    } catch (\Throwable $e) {
        return false;
    }
}

function get_revista_por_numero($numero) {
    foreach ($_SESSION['revistas'] as $r) {
        if ((string) $r['numero'] === (string) $numero) {
            return $r;
        }
    }
    return null;
}

function get_next_revista_id() {
    $max = 0;
    foreach ($_SESSION['revistas'] as $r) {
        if ($r['id'] > $max) {
            $max = $r['id'];
        }
    }
    return $max + 1;
}