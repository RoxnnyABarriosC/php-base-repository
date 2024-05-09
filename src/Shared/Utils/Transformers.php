<?php


/**
 * @throws Exception
 */
function Parse($value)
{
    // Intenta decodificar como JSON
    $jsonDecoded = json_decode($value, true);

    if (json_last_error() == JSON_ERROR_NONE) {
        $value = $jsonDecoded;
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

function Sanitize(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}


function ToUpperCase(string $value): string
{
    return strtoupper($value);
}

function ToLowerCase(string $value): string
{
    return strtolower($value);
}

function CleanSpaces(string $value): string
{
    return trim($value);
}