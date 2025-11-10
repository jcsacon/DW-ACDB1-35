<?php
/**
 * Manejo de la lógica del CAPTCHA
 */

// Constantes para el manejo del CAPTCHA
define("SHOW_CAPTCHA_ATTEMPTS", 3);

/**
 * Genera un nuevo CAPTCHA matemático
 * @return array Arreglo con la pregunta y respuesta del CAPTCHA
 */
function generarCaptcha() {
    $num1 = rand(1, 10);
    $num2 = rand(1, 10);
    $operator = rand(0, 1) ? "+" : "-";

    if ($operator === "-" && $num1 < $num2) {
        // Asegurar que el resultado no sea negativo
        list($num1, $num2) = [$num2, $num1];
    }

    $pregunta = "{$num1} {$operator} {$num2} = ?";
    $respuesta = ($operator === "+") ? ($num1 + $num2) : ($num1 - $num2);

    return [
        "pregunta" => $pregunta,
        "respuesta" => $respuesta
    ];
}

/**
 * Verifica si se debe mostrar el CAPTCHA
 * @return bool True si se debe mostrar el CAPTCHA
 */
function debeMostrarCaptcha() {
    return isset($_SESSION["login_attempts"]) && $_SESSION["login_attempts"] >= SHOW_CAPTCHA_ATTEMPTS;
}

/**
 * Verifica si el CAPTCHA ingresado es correcto
 * @param string $captcha_ingresado El valor ingresado por el usuario
 * @return bool True si el CAPTCHA es correcto
 */
function verificarCaptcha($captcha_ingresado) {
    if (!debeMostrarCaptcha()) {
        return true;
    }
    // Verificar si el CAPTCHA es correcto
    return !empty($captcha_ingresado) && 
           isset($_SESSION["captcha_answer"]) && 
           (int)$captcha_ingresado === $_SESSION["captcha_answer"];
}

/**
 * Genera un nuevo CAPTCHA si es necesario
 */
function manejarCaptcha() {
    if (debeMostrarCaptcha() && !isset($_SESSION["captcha_question"])) {
        $captcha = generarCaptcha();
        $_SESSION["captcha_question"] = $captcha["pregunta"];
        $_SESSION["captcha_answer"] = $captcha["respuesta"];
    }

    // Limpiar captcha si ya no se necesita
    if (!debeMostrarCaptcha() && isset($_SESSION["captcha_question"])) {
        unset($_SESSION["captcha_question"]);
        unset($_SESSION["captcha_answer"]);
    }
}

/**
 * Limpia las variables de sesión relacionadas con el CAPTCHA
 */
function limpiarCaptcha() {
    unset($_SESSION["captcha_question"]);
    unset($_SESSION["captcha_answer"]);
}
