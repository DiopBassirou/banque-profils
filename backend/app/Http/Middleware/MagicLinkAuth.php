<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MagicLinkAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('magic_email')) {
            return response()->json(['message' => 'Non autorisé. Veuillez utiliser un Magic Link valide.'], 401);
        }

        // Ajouter l'email aux attributs de la requête pour y accéder facilement dans les contrôleurs
        $request->attributes->add(['magic_email' => $request->session()->get('magic_email')]);

        return $next($request);
    }
}
