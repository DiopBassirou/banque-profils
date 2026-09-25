<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function index(Request $request)
    {
        $query = Profil::where('status', 'publie');

        if ($request->filled('type_recherche')) {
            $query->where('type_recherche', $request->type_recherche);
        }
        if ($request->filled('domaine')) {
            $query->where('domaine', 'like', '%' . $request->domaine . '%');
        }
        if ($request->filled('experience')) {
            $query->where('experience', $request->experience);
        }
        if ($request->filled('competences')) {
            // On peut chercher une compétence spécifique avec LIKE
            $query->where('competences', 'like', '%' . $request->competences . '%');
        }
        return response()->json($query->orderBy('created_at', 'desc')->paginate(12));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_affiche' => 'required|string|min:2|max:255',
            'niveau' => 'required|string|max:255',
            'type_recherche' => ['required', 'string', 'max:255', function ($attribute, $value, $fail) {
                $allowed = ['Stage', 'CDI', 'CDD', 'Alternance'];
                $types = array_map('trim', explode(',', $value));
                foreach ($types as $type) {
                    if (!in_array($type, $allowed)) {
                        $fail("Le type de recherche '$type' n'est pas valide.");
                    }
                }
            }],
            'domaine' => 'required|string|max:255',
            'experience' => 'required|string|max:255',
            'competences' => 'nullable|string|max:1000',
            'region' => 'required|string|max:255',
            'description' => 'required|string|min:10|max:1000',
            'email_contact' => 'nullable|email',
            'whatsapp' => 'nullable|regex:/^[0-9]{8,15}$/',
            'email_gestion' => 'nullable|email'
        ]);

        if (empty($validated['email_contact']) && empty($validated['whatsapp'])) {
            return response()->json(['message' => 'Un email de contact ou un WhatsApp est requis.'], 422);
        }

        // Filtre anti-spam basique : URLs ou mots interdits
        $description = strtolower($validated['description']);
        $hasLinks = preg_match('/(http|https|www\.)/', $description);
        $badWords = ['arnaque', 'viagra', 'casino', 'sexe', 'porno', 'investir'];
        $hasBadWords = false;
        
        foreach ($badWords as $word) {
            if (str_contains($description, $word)) {
                $hasBadWords = true;
                break;
            }
        }

        $status = ($hasLinks || $hasBadWords) ? 'en_attente' : 'publie';

        $profil = Profil::create(array_merge($validated, [
            'status' => $status,
        ]));

        $message = $status === 'publie' 
            ? 'Profil publié avec succès.' 
            : 'Profil reçu, il est en attente de modération car il contient des liens ou mots sensibles.';

        return response()->json([
            'message' => $message,
            'profil' => $profil
        ], 201);
    }

    public function signaler(Request $request, Profil $profil)
    {
        // Enregistre un log de modération avec ID admin null (car action publique)
        \App\Models\ModerationLog::create([
            'profil_id' => $profil->id,
            'action' => 'signale',
            'raison' => 'Signalé par un utilisateur public (IP: ' . $request->ip() . ')'
        ]);

        return response()->json(['message' => 'Merci pour votre signalement. Nos équipes vont vérifier ce profil.']);
    }
}
