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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('numeroPolice');
            $table->string('typePolice'); 
            $table->foreignId('formule_id')->constrained('formules')->onDelete('cascade');
            
            // Informations médicales
            $table->text('antecedents_medicaux')->nullable();
            $table->text('medicaments_actuels')->nullable();
            $table->text('allergies')->nullable();
            $table->text('habitudes_vie')->nullable();

            $table->string('couverture');
            $table->date('dateDebut');
            $table->date('dateFin');
            $table->decimal('primeMensuelle', 10, 2);
            $table->enum('statut', ['en_attente', 'rendez_vous_planifie', 'actif', 'suspendu', 'resilie', 'expire'])->default('en_attente');
            $table->dateTime('date_rendez_vous')->nullable();
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
