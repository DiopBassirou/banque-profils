<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profils', function (Blueprint $table) {
            $table->id();
            $table->string('nom_affiche');
            $table->string('niveau');
            $table->string('type_recherche');
            $table->string('domaine');
            $table->string('region');
            $table->text('description');
            $table->string('email_contact')->nullable();
            $table->string('email_gestion')->nullable();
            $table->string('whatsapp')->nullable();
            $table->enum('status', ['en_attente', 'publie', 'rejete', 'expire'])->default('en_attente');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('domaine');
            $table->index('type_recherche');
            $table->index('region');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profils');
    }
};
