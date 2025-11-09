<?php
/**
 * Generador de Imagen de Captcha Numérico
 * Crea una imagen PNG con un código numérico para verificación.
 */

require_once 'config/sesion.php';

// Iniciar sesión para acceder a la variable de sesión del captcha
iniciarSesionSegura();

// Verificar si el código de captcha está en la sesión
if (!isset($_SESSION['captcha_code'])) {
    // Si no hay código, generar uno temporalmente o mostrar un error
    // En este caso, creamos una imagen de error para evitar fallos
    $error_text = "Error";
    $font_size = 5;
    $width = imagefontwidth($font_size) * strlen($error_text);
    $height = imagefontheight($font_size);

    $image = imagecreatetruecolor($width, $height);
    $bg_color = imagecolorallocate($image, 255, 255, 255); // Fondo blanco
    $text_color = imagecolorallocate($image, 0, 0, 0); // Texto negro

    imagefill($image, 0, 0, $bg_color);
    imagestring($image, $font_size, 0, 0, $error_text, $text_color);

    header('Content-Type: image/png');
    imagepng($image);
    imagedestroy($image);
    exit;
}

$captcha_code = $_SESSION['captcha_code'];

// --- Configuración de la imagen ---
$width = 120;
$height = 40;
$font_size = 20;
$font_path = realpath('css/fonts/Poppins-Bold.ttf'); // Asegúrate que esta fuente exista o usa una fuente por defecto

// Crear la imagen
$image = imagecreatetruecolor($width, $height);

// --- Colores ---
$background_color = imagecolorallocate($image, 240, 240, 240); // Fondo gris claro
$text_color = imagecolorallocate($image, 50, 50, 50); // Texto gris oscuro
$noise_color = imagecolorallocate($image, 180, 180, 180); // Color para el ruido

// Rellenar el fondo
imagefill($image, 0, 0, $background_color);

// --- Añadir ruido (líneas y puntos) para dificultar la lectura por bots ---
// Añadir líneas de ruido
for ($i = 0; $i < 5; $i++) {
    imageline(
        $image,
        rand(0, $width),
        rand(0, $height),
        rand(0, $width),
        rand(0, $height),
        $noise_color
    );
}

// Añadir puntos de ruido
for ($i = 0; $i < 500; $i++) {
    imagesetpixel(
        $image,
        rand(0, $width),
        rand(0, $height),
        $noise_color
    );
}

// --- Escribir el código captcha en la imagen ---
// Calcular posición del texto para centrarlo
$text_box = imagettfbbox($font_size, 0, $font_path, $captcha_code);
$text_width = $text_box[2] - $text_box[0];
$text_height = $text_box[1] - $text_box[7];
$x = ($width - $text_width) / 2;
$y = ($height - $text_height) / 2 + $text_height;

// Usar imagettftext para una mejor apariencia de la fuente
if ($font_path && file_exists($font_path)) {
    imagettftext($image, $font_size, 0, (int)$x, (int)$y, $text_color, $font_path, $captcha_code);
} else {
    // Fallback a una fuente simple si la fuente TTF no se encuentra
    $fallback_font_size = 5;
    $x_fallback = ($width - imagefontwidth($fallback_font_size) * strlen($captcha_code)) / 2;
    $y_fallback = ($height - imagefontheight($fallback_font_size)) / 2;
    imagestring($image, $fallback_font_size, (int)$x_fallback, (int)$y_fallback, $captcha_code, $text_color);
}

// --- Salida de la imagen ---
header('Content-Type: image/png');
header('Cache-Control: no-cache, no-store, must-revalidate'); // Evitar cache
header('Pragma: no-cache');
header('Expires: 0');

imagepng($image);
imagedestroy($image);
exit;
