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
        Schema::create('sinistres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('police_id')->constrained('police')->onDelete('cascade');
            $table->foreignId('beneficiaire_id')->nullable()->constrained('beneficiaires')->nullOnDelete();
            
            $table->string('reference')->unique();
            $table->enum('type_sinistre', ['maladie', 'accident', 'hospitalisation', 'maternite', 'chirurgie']);
            
            $table->string('lieu_sinistre')->nullable();
            $table->string('ville_pays')->nullable();
            $table->date('date_sinistre');

            // Détails médicaux
            $table->boolean('premiere_consultation')->default(true);
            $table->enum('gravite', ['leger', 'moyen', 'grave'])->default('leger');
            $table->text('description');
            $table->string('diagnostic')->nullable();
            $table->string('medecin_traitant')->nullable();
            $table->text('traitement_prescrit')->nullable();

            // Financier
            $table->decimal('montant_total', 15, 2)->default(0);



            // Declarant
            $table->boolean('is_declarant_different')->default(false);
            $table->string('declarant_nom')->nullable();
            $table->string('declarant_relation')->nullable();

            $table->text('commentaires')->nullable();
            $table->boolean('consentement')->default(false);

            $table->enum('statut', ['en_attente', 'en_analyse', 'approuve', 'rejete'])->default('en_attente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sinistres');
    }
};
