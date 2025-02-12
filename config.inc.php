<?php
/**
 * Configuración del módulo de listas negras (RBL) para Roundcube.
 *
 * Este archivo define las listas negras a consultar y los mensajes de error 
 * personalizados en caso de que la IP del usuario esté bloqueada.
 *
 * - Cada RBL tiene una lista de valores que indican si una IP está bloqueada.
 * - Se puede configurar un mensaje específico por cada RBL.
 * - Si una IP está en múltiples listas negras, se mostrarán múltiples mensajes.
 */

// Definición de las RBLs a consultar y sus respuestas esperadas.
$config['rbls'] = array(
    'dnsbl-1.uceprotect.net' => array(
        'listed_values' => array('127.0.0.2'), // Códigos de respuesta que indican que la IP está bloqueada
        'message' => "Su IP ha sido bloqueada por <b>UCEPROTECT</b>. <br>
                      Para solicitar su eliminación, visite: 
                      <a href='https://www.uceprotect.net/en/rblcheck.php' target='_blank'>UCEPROTECT</a>."
    ),
    'ips.backscatterer.org' => array(
        'listed_values' => array('127.0.0.2', '127.0.0.4'), // Algunos RBLs pueden devolver múltiples valores
        'message' => "Su IP está en la lista de <b>Backscatterer</b>. <br>
                      Para solicitar su eliminación, visite: 
                      <a href='https://www.backscatterer.org/?target=removal' target='_blank'>Backscatterer</a>."
    ),
    'psbl.surriel.com' => array(
        'listed_values' => array('127.0.0.3'), 
        'message' => "Su IP ha sido detectada en <b>PSBL</b>. <br>
                      Para más detalles y remoción, visite: 
                      <a href='http://psbl.org/' target='_blank'>PSBL</a>."
    )
);

// Mensaje genérico en caso de estar listado en múltiples RBLs.
$config['rbl_general_message'] = "Su IP ha sido bloqueada por múltiples listas negras. 
                                  Consulte los enlaces proporcionados para más detalles.";
?>