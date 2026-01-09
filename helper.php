<?php
function cekTanggal($value, $format = 'Y-m-d')
{
    if (empty($value)) {
        return null;
    }
    if ($value instanceof DateTime) {
        return $value->format($format);
    }
    if ($value instanceof DateTimeImmutable) {
        return $value->format($format);
    }
    if (is_string($value)) {
        try {
            $dt = new DateTime($value);
            return $dt->format($format);
        } catch (Exception $e) {
            return null;
        }
    }
    return null;
}

function cekNull($value, $tipe = 'string')
{
    if ($value === null || $value === '') {
        if ($tipe === 'number' || $tipe === 'int' || $tipe === 'float') {
            return 0;
        }
        if ($tipe === 'string') {
            return '';
        }
        return null;
    }

    switch ($tipe) {
        case 'number':
        case 'int':
            return (int)$value;

        case 'float':
            return (float)$value;

        case 'string':
            return (string)$value;

        default:
            return $value;
    }
}
?>