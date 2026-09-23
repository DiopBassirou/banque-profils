<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use App\Models\ModerationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProfilController extends Controller
{
    // Liste tous les profils (tous statuts) avec recherche
    public function index(Request $request)
    {
        $query = Profil::query();

        // Recherche par nom, email ou whatsapp
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom_affiche', 'like', "%$search%")
                  ->orWhere('email_contact', 'like', "%$search%")
                  ->orWhere('whatsapp', 'like', "%$search%")
                  ->orWhere('domaine', 'like', "%$search%");
            });
        }

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->orderBy('created_at', 'desc')->paginate(20));
    }

    // Modifier un profil (à la demande d'un candidat qui a contacté l'admin)
    public function update(Request $request, Profil $profil)
    {
        $validated = $request->validate([
            'nom_affiche' => 'sometimes|string|min:2|max:255',
            'niveau' => 'sometimes|string|max:255',
            'type_recherche' => 'sometimes|string|max:255',
            'domaine' => 'sometimes|string|max:255',
            'region' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|min:10|max:1000',
            'email_contact' => 'sometimes|nullable|email',
            'whatsapp' => 'sometimes|nullable|regex:/^[0-9]{8,15}$/',
        ]);

        $profil->update($validated);

        ModerationLog::create([
            'profil_id' => $profil->id,
            'admin_id' => Auth::id(),
            'action' => 'modifie_sur_demande',
            'raison' => $request->raison ?? 'Modification à la demande du candidat',
        ]);

        return response()->json(['message' => 'Profil modifié.', 'profil' => $profil]);
    }

    // Changer le statut d'un profil
    public function updateStatus(Request $request, Profil $profil)
    {
        $request->validate([
            'status' => 'required|in:en_attente,publie,rejete,expire',
            'raison' => 'nullable|string'
        ]);

        $profil->update(['status' => $request->status]);

        ModerationLog::create([
            'profil_id' => $profil->id,
            'admin_id' => Auth::id(),
            'action' => $request->status,
            'raison' => $request->raison
        ]);

        return response()->json(['message' => 'Statut mis à jour.', 'profil' => $profil]);
    }

    // Supprimer un profil (soft delete)
    public function destroy(Profil $profil)
    {
        ModerationLog::create([
            'profil_id' => $profil->id,
            'admin_id' => Auth::id(),
            'action' => 'supprime',
            'raison' => 'Suppression par l\'admin',
        ]);

        $profil->delete();

        return response()->json(['message' => 'Profil supprimé.']);
    }
}
