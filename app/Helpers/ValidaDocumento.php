<?php

namespace App\Helpers;

class ValidaDocumento
{
    public static function cpf(string $cpf): bool
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) !== 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($i = 9; $i < 11; $i++) {
            $j = 0;
            for ($k = 0; $k < $i; $k++) {
                $j += $cpf[$k] * (($i + 1) - $k);
            }
            $j = ((10 * $j) % 11) % 10;
            if ($cpf[$k] != $j) {
                return false;
            }
        }

        return true;
    }

    public static function cnpj(string $cnpj): bool
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        if (strlen($cnpj) !== 14) {
            return false;
        }

        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        for ($i = 12; $i < 14; $i++) {
            $j = 0;
            $k = ($i === 12) ? 5 : 6;
            for ($l = 0; $l < $i; $l++) {
                $j += $cnpj[$l] * $k;
                $k = ($k === 2) ? 9 : $k - 1;
            }
            $j = ((10 * $j) % 11) % 10;
            if ($cnpj[$l] != $j) {
                return false;
            }
        }

        return true;
    }
}