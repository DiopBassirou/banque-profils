<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MonProfilController extends Controller
{
    public function index(Request $request)
    {
        $email = $request->attributes->get('magic_email');
        $profils = Profil::where('email_gestion', $email)->get();
        return response()->json($profils);
    }

    public function update(Request $request, Profil $profil)
    {
        Gate::authorize('update', $profil);

        $validated = $request->validate([
            'nom_affiche' => 'sometimes|required|string|min:2|max:255',
            'niveau' => 'sometimes|required|string|max:255',
            'type_recherche' => 'sometimes|required|in:Stage,CDI,CDD,Alternance',
            'domaine' => 'sometimes|required|string|max:255',
            'region' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|min:10|max:1000',
            'email_contact' => 'nullable|email',
            'whatsapp' => 'nullable|regex:/^[0-9]{8,15}$/',
        ]);

        if (empty($request->email_contact) && empty($request->whatsapp) && empty($profil->email_contact) && empty($profil->whatsapp)) {
            return response()->json(['message' => 'Un contact est requis.'], 422);
        }

        $profil->update($validated);
        return response()->json(['message' => 'Profil mis à jour.', 'profil' => $profil]);
    }

    public function destroy(Profil $profil)
    {
        Gate::authorize('delete', $profil);
        $profil->delete();
        return response()->json(['message' => 'Profil supprimé.']);
    }
}
