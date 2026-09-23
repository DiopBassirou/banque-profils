<?php

namespace App\Http\Controllers;

use App\Models\MagicLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MagicLinkController extends Controller
{
    public function send(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $token = Str::random(64);
        
        MagicLink::create([
            'email' => $request->email,
            'token_hash' => Hash::make($token),
            'expires_at' => Carbon::now()->addMinutes(15),
        ]);

        // TODO: Envoyer l'email avec le lien : http://frontend/compte/verify?token=...&email=...
        // Pour les tests, on retourne le token en JSON
        return response()->json([
            'message' => 'Si cet email est associé à un profil, un lien de connexion a été envoyé.',
            'debug_token' => $token // À SUPPRIMER EN PROD
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string'
        ]);

        $magicLinks = MagicLink::where('email', $request->email)
            ->whereNull('used_at')
            ->where('expires_at', '>', Carbon::now())
            ->get();

        foreach ($magicLinks as $magicLink) {
            if (Hash::check($request->token, $magicLink->token_hash)) {
                $magicLink->update(['used_at' => Carbon::now()]);
                
                // Ouvrir la session (Cookie)
                $request->session()->put('magic_email', $request->email);

                return response()->json(['message' => 'Authentification réussie.']);
            }
        }

        return response()->json(['message' => 'Lien invalide ou expiré.'], 401);
    }
}
