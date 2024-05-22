<?php


/**
 * @throws Exception
 */
function parse($value)
{
    // Intenta decodificar como JSON

    try {
        $value = json_decode(json_encode($value));
    } catch (Exception $e) {
    }


    // Si es una cadena, intenta convertir a número o fecha
    if (is_string($value)) {
        if (is_numeric($value)) {
            return floatval($value);
        }

        $date = strtotime($value);
        if ($date !== false) {
            return new DateTime('@' . $date);
        }
    }
    return $value;
}