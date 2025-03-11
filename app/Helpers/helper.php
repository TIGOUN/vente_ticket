<?php

use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

if (!function_exists('conditionalAction')) {
    /**
     * Retourne une action basée sur une condition.
     *
     * @param mixed $condition La condition à évaluer.
     * @param string $trueAction L'action à retourner si la condition est vraie.
     * @param string $falseAction L'action à retourner si la condition est fausse.
     * @return string
     */
    function conditionalAction($condition, $trueAction, $falseAction)
    {
        return $condition ? $trueAction : $falseAction;
    }
}

if (!function_exists('generateUniqueReference')) {
    function generateUniqueReference()
    {
        do {
            // Générer un code aléatoire avec 3 sections de 3 caractères chacune
            $reference = strtoupper(Str::random(3)) . '-' . strtoupper(Str::random(3)) . '-' . strtoupper(Str::random(3));
            // Vérifier si le code existe déjà dans la table deposit_requests
            $exists = DB::table('events')->where('code', $reference)->exists();
        } while ($exists);
        return $reference;
    }
}


if (!function_exists('generateCodeTicket')) {
    function generateCodeTicket()
    {
        $anneeActuelle = substr(date('Y'), -2);
        $codePrefixe = 'TKT-FAST' . $anneeActuelle . '-';

        $dernierCode = Ticket::where('code', 'LIKE', 'TKT-FAST' . $anneeActuelle . '-%')
            ->orderBy('code', 'desc')
            ->first();

        if ($dernierCode) {
            $dernierNumero = intval(substr($dernierCode->code, -4));
            $nouveauNumero = $dernierNumero + 1;
        } else {
            $nouveauNumero = 1;
        }

        $nouveauNumero = str_pad($nouveauNumero, 4, '0', STR_PAD_LEFT);
        $codeFinal = $codePrefixe . $nouveauNumero;
        return $codeFinal;
    }
}