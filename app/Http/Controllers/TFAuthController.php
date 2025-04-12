<?php

namespace App\Http\Controllers;

use App\Mail\TFAuthMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class TFAuthController extends Controller
{
    protected $user;
    protected $userConnect;
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->userConnect = Auth::user();
    }

    public function show()
    {
        $user = $this->userConnect;
        $userEmail = $user->email;

        $this->sendEmail($user);
        $error = "";
        return view('auth.tfauth', compact('user', 'error'));
    }

    public function postAuth(Request $request)
    {
        $user = $this->user->findorfail($this->userConnect->id);
        if ($request->code == $this->caesar_decipher_int($user->opt, 3)) {
            $user->opt = NULL;
            $user->save();
            Session::put('user_2fa', Auth::user()->id);
            // return response()->json('200');
            return redirect()->intended(route('dashboard', absolute: false));
        } else {
            return redirect()->back()->with('error', 'Le code saisi est incorrecte');
        }
    }

    public function sendEmail(User $user)
    {
        $opt = rand(100000, 999999);
        $user = $user->findorfail($this->userConnect->id);
        $user->opt = $this->caesar_cipher_int($opt, 3);
        $user->save();

        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'opt' => $this->caesar_decipher_int($user->opt, 3),
        ];
        #3. Envoi du mail
        try {
            $k =  Mail::to($user->email)->send(new TFAuthMail($data));
        } catch (\Throwable $ex) {
            dd($ex->getMessage());
            Log::error($ex->getMessage());
        }
    }

    /**
     * Fonction pour chiffrer un entier à 6 chiffres en utilisant l'algorithme de César
     * avec un décalage (shift) spécifié.
     *
     * @param int $number - L'entier à chiffrer.
     * @param int $shift - Le décalage (shift) à utiliser pour chiffrer l'entier.
     * @return int - L'entier chiffré.
     */
    function caesar_cipher_int($number, $shift)
    {
        // Formatte l'entier avec des zéros à gauche pour avoir 6 chiffres.
        $message = sprintf("%06d", $number);

        // Initialise une variable pour stocker le message chiffré.
        $ciphered_message = "";

        // Boucle à travers chaque chiffre de l'entier.
        $length = strlen($message);
        for ($i = 0; $i < $length; $i++) {
            // Obtient le code ASCII du chiffre.
            $char = ord($message[$i]);

            // Applique le décalage (shift) pour obtenir le nouveau code ASCII chiffré.
            $cipher_char = chr((($char - 48 + $shift) % 10) + 48);

            // Ajoute le chiffre chiffré à la fin du message chiffré.
            $ciphered_message .= $cipher_char;
        }

        // Retourne l'entier chiffré.
        return intval($ciphered_message);
    }

    /**
     * Fonction pour déchiffrer un entier à 6 chiffres chiffré en utilisant l'algorithme de César
     * avec un décalage (shift) spécifié.
     *
     * @param int $ciphered_number - L'entier chiffré à déchiffrer.
     * @param int $shift - Le décalage (shift) à utiliser pour déchiffrer l'entier.
     * @return int - L'entier déchiffré.
     */
    function caesar_decipher_int($ciphered_number, $shift)
    {
        // Formatte l'entier chiffré avec des zéros à gauche pour avoir 6 chiffres.
        $ciphered_message = sprintf("%06d", $ciphered_number);

        // Initialise une variable pour stocker le message déchiffré.
        $deciphered_message = "";

        // Boucle à travers chaque chiffre de l'entier chiffré.
        $length = strlen($ciphered_message);
        for ($i = 0; $i < $length; $i++) {
            // Obtient le code ASCII du chiffre chiffré.
            $char = ord($ciphered_message[$i]);

            // Applique le décalage (shift) négatif pour obtenir le nouveau code ASCII déchiffré.
            $decipher_char = chr(((($char - 48) - $shift + 10) % 10) + 48);

            // Ajoute le chiffre déchiffré à la fin du message déchiffré.
            $deciphered_message .= $decipher_char;
        }

        // Retourne l'entier déchiffré.
        return intval($deciphered_message);
    }
}
