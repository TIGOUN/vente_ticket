<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login');  // Ou toute autre redirection vers la page de connexion
        }

        // Vérifier si l'utilisateur a le type 'controller'
        if (Auth::user()->type_user === 'controller') {
            // Retourner un 403 avec un message personnalisé
            abort(403, 'Accès interdit. Vous ne pouvez pas accéder à cette ressource.');
        }

        return $next($request);
    }
}
