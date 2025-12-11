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
        Schema::create('police', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Le client
            $table->string('numeroPolice')->unique();
            $table->string('typePolice'); // Gardé pour compatibilité ou comme alias de formule
            $table->foreignId('formule_id')->constrained('formules')->onDelete('cascade');
            
            // Informations médicales
            $table->text('antecedents_medicaux')->nullable();
            $table->text('medicaments_actuels')->nullable();
            $table->text('allergies')->nullable();
            $table->text('habitudes_vie')->nullable();

            $table->string('couverture'); // Peut être redondant avec formule, mais on garde
            $table->date('dateDebut');
            $table->date('dateFin');
            $table->decimal('primeMensuelle', 10, 2);
            $table->enum('frequence_paiement', ['Mensuel', 'Trimestriel', 'Annuel'])->default('Mensuel');
            $table->enum('statut', ['en_attente', 'actif', 'suspendu', 'resilie'])->default('en_attente');
            $table->string('etat')->default('Actif'); // Actif / Inactif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('police');
    }
};
