<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profil extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom_affiche', 'niveau', 'experience', 'type_recherche', 'domaine', 'competences', 'region', 
        'description', 'email_contact', 'email_gestion', 'whatsapp', 
        'status', 'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
