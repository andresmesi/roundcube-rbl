<?php
/**
 * Módulo de verificación de listas negras (RBL) para Roundcube.
 *
 * Este script consulta múltiples RBLs y, si la IP del usuario está listada,
 * bloquea el acceso mostrando un mensaje de error personalizado.
 *
 * Funcionalidad:
 * - Obtiene la IP del usuario, incluso si está detrás de Cloudflare o un proxy inverso.
 * - Consulta cada RBL configurada en `config.inc.php`.
 * - Si la IP está listada, responde con HTTP 403 Forbidden y muestra el mensaje correspondiente.
 * - Registra intentos y bloqueos en el log del servidor para depuración.
 */

// Cargar configuración de listas negras
require_once __DIR__ . '/config.inc.php';

/**
 * Obtiene la dirección IP real del usuario, incluso si está detrás de Cloudflare o un proxy.
 *
 * @return string IP del usuario.
 */
function get_user_ip() {
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        // IP real del usuario si está detrás de Cloudflare
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // En caso de proxy inverso, obtener la primera IP de la lista
        $ip_list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ip_list[0]);
    } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

/**
 * Verifica si la IP del usuario está listada en alguna RBL.
 *
 * @param string $ip Dirección IP del usuario.
 * @param array  $rbls Lista de RBLs a consultar.
 * @return array RBLs en las que la IP está listada y sus mensajes de error.
 */
function check_rbl($ip, $rbls) {
    $listed_rbls = array();
    $reverse_ip = implode('.', array_reverse(explode('.', $ip)));

    foreach ($rbls as $rbl => $settings) {
        $lookup = $reverse_ip . '.' . $rbl;
        $result = gethostbyname($lookup);

        // Registrar en logs la consulta a la RBL
        error_log("rbl.php: Consultando $lookup - Respuesta: $result");

        // Si la consulta devuelve un valor listado en la configuración, la IP está en la lista negra
        if ($result !== $lookup && in_array($result, $settings['listed_values'])) {
            $listed_rbls[$rbl] = $settings['message'];
            error_log("rbl.php: IP $ip detectada en RBL $rbl con respuesta $result");
        }
    }

    return $listed_rbls;
}

// Obtener la IP del usuario
$user_ip = get_user_ip();
error_log("rbl.php: Verificando IP del usuario: $user_ip");

// Consultar las listas negras configuradas
$blacklisted_rbls = check_rbl($user_ip, $config['rbls']);

if (!empty($blacklisted_rbls)) {
    // Bloquear acceso con HTTP 403 Forbidden
    header('HTTP/1.1 403 Forbidden');
    error_log("rbl.php: Acceso denegado para IP $user_ip, bloqueado por RBLs.");

    // Generar página de error
    echo "<html><head><title>Acceso Denegado</title></head><body>";
    echo "<h2 style='color:red;'>IP $user_ip bloqueada</h2>";

    foreach ($blacklisted_rbls as $rbl => $message) {
        echo "<p><b>Lista negra:</b> $rbl</p>";
        echo "<p>$message</p>";
        echo "<hr>";
    }

    echo "<p>Si cree que esto es un error, puede verificar su estado en cada enlace proporcionado.</p>";
    echo "</body></html>";

    // Detener ejecución sin cargar Roundcube
    exit();
}

// Si la IP no está en una RBL, permitir el acceso a Roundcube.
error_log("rbl.php: IP $user_ip no listada en RBLs. Acceso permitido.");
?>