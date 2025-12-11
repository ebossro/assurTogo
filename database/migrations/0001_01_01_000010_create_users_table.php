<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('telephone')->nullable();
            
            // Informations d'identification
            $table->date('date_naissance')->nullable();
            $table->enum('sexe', ['M', 'F'])->nullable();
            $table->string('photo_profil')->nullable(); // Obligatoire dans le formulaire, mais nullable ici pour éviter les erreurs sur les anciens seeds si nécessaire, ou on le met non nullable si on veut être strict. Le user a dit "La photo aussi est obligatoire".
            $table->string('type_piece')->nullable();
            $table->string('numero_piece')->nullable();
            $table->date('date_expiration_piece')->nullable();

            // Coordonnées
            $table->string('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('quartier')->nullable();

            // Situation familiale
            $table->enum('statut_matrimonial', ['celibataire', 'marie', 'divorce', 'veuf'])->default('celibataire');
            $table->integer('nombre_enfants')->default(0);

            // Situation professionnelle
            $table->string('profession')->nullable();
            $table->string('employeur')->nullable();
            $table->decimal('revenu_mensuel', 15, 2)->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
