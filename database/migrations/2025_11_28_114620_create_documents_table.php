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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('police_id')->nullable()->constrained('police')->onDelete('cascade');
            $table->foreignId('sinistre_id')->nullable()->constrained('sinistres')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');

            $table->string('nomDocument')->nullable();
            $table->enum('typeDocument', ['certificat_medical', 'facture', 'examen', 'ordonnance', 'rapport_medical', 'identite', 'autre'])->default('autre');
            $table->string('cheminDocument');
            $table->enum('statutDocument', ['actif', 'archive'])->default('actif');
            $table->date('dateTeleversementDocument')->useCurrent();
            $table->unsignedBigInteger('tailleDocument');
            $table->string('formatDocument')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
